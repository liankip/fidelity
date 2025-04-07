<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use App\Models\CategoryItem;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\ItemCreated;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ItemCRUDController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:it|top-manager|purchasing|manager|finance|adminlapangan');
    }

    public function index(Request $request)
    {
        if ($request->search) {
            $searchcompact = $request->search;
            $items = Item::available()
                ->where(function ($query) use ($request) {
                    $query->where('item_code', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('brand', 'like', '%' . $request->search . '%')
                        ->orWhere('type', 'like', '%' . $request->search . '%')
                        ->orWhere('unit', 'like', '%' . $request->search . '%')
                        ->orWhere('item_code', 'like', '%' . $request->search . '%');
                })
                ->paginate(8);
            $items->appends(['search' => $request->search]);
        } else {
            $searchcompact = "";
            $items = Item::available()->orderBy('id', 'desc')->paginate(10);
        }

        $userId = auth()->user()->id;
        $carts  = Cart::session($userId)->getContent();
        $itemNeedApproval = Item::where('is_approved', false)->get();

        return view('masterdata.items.index', compact(['carts', 'items', "searchcompact", "itemNeedApproval"]));
    }

    public function create()
    {
        $units = Unit::all();
        $categories = CategoryItem::all();
        $projects = Project::all();
        return view('masterdata.items.create', [
            'units' => $units,
            'categories' => $categories,
            'projects' => $projects
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'unique:items,name', 'max:255'],
            'lead_time'  => ['required'],
            'unit'  => ['required', 'exists:units,id'],
            'brand' => ['nullable', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'file_upload.*' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
        ], [], [
            'name'  => 'Item Name',
            'unit'  => 'Unit',
            'image' => 'Image',
        ]);

        $unit = Unit::findOrFail($request->unit);

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $extension  = $image->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;
            $imagePath  = $image->storeAs('images/items', $randomName, 'public');

            $image_name = $imagePath;
        } else {
            $image_name = "images/no_image.png";
        }

        $fileMetadata = [];
        if ($request->hasFile('file_upload')) {
            foreach ($request->file('file_upload') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileType = $file->getMimeType();
                $filePath = $file->store('file_upload', 'public');

                $fileMetadata[] = [
                    'file_type' => $fileType,
                    'file_path' => $filePath,
                    'original_name' => $originalName
                ];
            }
        }

        $newItem = null;

        DB::transaction(function () use ($request, $unit, $image_name, $fileMetadata, &$newItem) {
            $item = new Item;
            $item->name = $request->name;
            $item->brand = $request->brand;
            $item->item_code = "N/A";
            $item->type = $request->type;
            $item->category_id = $request->category_id;
            $item->unit = $unit->name;
            $item->created_by = auth()->user()->id;
            $item->image = $image_name;
            $item->notes_k3 = $request->notes_k3;
            $item->is_approved = (bool)auth()->user()->hasTopLevelAccess();
            $item->approved_by = auth()->user()->hasTopLevelAccess() ? auth()->user()->id : null;
            $item->approved_by_2 = auth()->user()->hasTopLevelAccess() ? auth()->user()->id : null;
            $item->file_upload = json_encode($fileMetadata);
            $item->rfa = $request->rfa;
            $item->lead_time = $request->lead_time;
            $item->save();

            $newItem = ItemUnit::create([
                'item_id'         => $item->id,
                'unit_id'         => $unit->id,
                'conversion_rate' => 1,
            ]);

            Item::findOrFail($item->id)->update([
                'item_code' => "1" . str_pad($item->id, 5, "0", STR_PAD_LEFT)
            ]);
        });

        if (auth()->user()->hasTopLevelAccess()) {
            return redirect()->route('items.index')->with('success', 'Item has been created successfully.');
        }

        $data = [
            'action_by' => auth()->user()->name,
            'name' => $request->name,
            'item_code' => "1" . str_pad($newItem?->id, 5, "0", STR_PAD_LEFT),
        ];

        Notification::send(User::role(['manager', 'it'])->get(), new ItemCreated($data));

        return redirect()->route('items.index')->with('success', 'Item has been created successfully. Please wait for approval.');
    }

    public function show(Item $item)
    {
        return view('masterdata.items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $projects = Project::all();
        $existingProjects = json_decode($item->rfa, true);
        return view('masterdata.items.edit', [
            'item'  => $item,
            'units' => Unit::all(),
            'categories' => CategoryItem::all(),
            'projects' => $projects,
            'existingProjects' => $existingProjects
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => ['required', 'max:255', Rule::unique('items')->ignore($id)],
            'lead_time'  => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'file_upload.*' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:2048'],
        ], [], [
            'name'  => 'Item Name',
            'image' => 'Image'
        ]);

        $item = Item::find($id);

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $extension  = $image->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;
            $imagePath  = $image->storeAs('images/items', $randomName, 'public');

            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            $item->image = $imagePath;
        }


        $item->name       = $request->name;
        $item->brand = $request->brand;
        $item->notes_k3 = $request->notes_k3;
        $item->updated_by = auth()->user()->id;
        $item->type = $request->type ? $request->type : "NA";
        $item->category_id = $request->category_id;
        $item->rfa = $request->rfa;
        $item->lead_time = $request->lead_time;

        $item->save();

        if ($request->approval) {
            return redirect()->route('items.index', ['tab' => 'need-approval'])->with('success', 'Item has been updated successfully');
        }

        return redirect()->route('items.index')->with('success', 'Item has been updated successfully');
    }

    public function destroy(Item $item)
    {
        $item->update([
            'is_disabled' => true,
        ]);
        return redirect()->route('items.index')->with('success', 'Item has been removed successfully');
    }

    public function export()
    {
        return Excel::download(new ItemsExport, 'sne-masterdata-items.xlsx');
    }

    public function import()
    {
        Excel::import(new ItemsImport, request()->file('file'));

        return back();
    }

    public function unit(Request $request)
    {
        $request->validate([
            'unit_name' => ['required', 'unique:units,name']
        ], [], [
            'unit_name' => 'Unit Name'
        ]);

        $attr = $request->all();

        $unit = Unit::create([
            'name' => $attr['unit_name'],
        ]);

        return response()->json([
            'option_value' => $unit->id,
            'option_text'  => $unit->name,
        ]);
    }

    public function sync_unit()
    {
        foreach (Item::all() as $item) {

            if ($item->unit == null or $item->unit == "") {
                $item->update([
                    'unit' => "Unit",
                ]);
            }

            $cek_item_unit = ItemUnit::where('item_id', $item->id)->count();

            if ($cek_item_unit == 0) {

                $cek_unit = Unit::where('name', $item->unit)->count();

                if ($cek_unit == 0) {
                    $unit = Unit::create([
                        'name' => $item->unit,
                    ]);
                } else {
                    $unit = Unit::where('name', $item->unit)->first();
                }

                ItemUnit::create([
                    'item_id'         => $item->id,
                    'unit_id'         => $unit->id,
                    'conversion_rate' => 1,
                ]);
            }
        }

        return redirect()->route('items.index')->with('success', 'Items and Units have been successfully synced');
    }

    public function item_unit(Request $request)
    {
        $request->validate([
            'insert_unit_id'  => ['required', 'exists:units,id'],
            'conversion_rate' => ['required', 'numeric', 'min:1'],
        ], [], [
            'insert_unit_id'  => 'Unit',
            'conversion_rate' => 'Conversion Rate',
        ]);

        $attr = $request->all();

        $isExist = ItemUnit::where('item_id', $attr['item_id'])
            ->where('unit_id', $attr['insert_unit_id'])
            ->first();

        if ($isExist) {
            return redirect()->back()->with('exists', 'Item Telah Memiliki Unit yang di Input');
        }

        ItemUnit::create([
            'item_id'         => $attr['item_id'],
            'unit_id'         => $attr['insert_unit_id'],
            'conversion_rate' => $attr['conversion_rate'],
        ]);


        return redirect()->back();
    }

    public function delete_item_unit(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:item_units,id'],
        ], [], [
            'id' => 'Item Unit',
        ]);

        $unit = ItemUnit::where('id', $request->id)->first();

        if ($unit->conversion_rate == 1) {
            return response()->json([
                'message' => 'Cannot delete this unit because this unit is the main unit of this item',
            ], 400);
        }

        $unit->delete();

        return redirect()->back()->with('success', 'Item Unit has been deleted successfully');
    }

    public function updateProductImage(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $fileMetadata = json_decode($item->file_upload, true) ?? [];

        if ($request->hasFile('file_upload')) {
            foreach ($request->file('file_upload') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileType = $file->getMimeType();
                $filePath = $file->store('file_upload', 'public');

                $fileMetadata[] = [
                    'file_type' => $fileType,
                    'file_path' => $filePath,
                    'original_name' => $originalName
                ];
            }
        }
        $item->file_upload = json_encode($fileMetadata);
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item has been updated.');
    }

    public function removeFile(Request $request)
    {
        $item = Item::find($request->item_id);
        if ($item) {
            $fileUploads = json_decode($item->file_upload, true);
            $fileUploads = array_filter($fileUploads, function ($file) use ($request) {
                return $file['file_path'] !== $request->file_path;
            });
            $item->file_upload = json_encode(array_values($fileUploads));
            $item->save();

            Storage::delete('public/' . $request->file_path);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

}
