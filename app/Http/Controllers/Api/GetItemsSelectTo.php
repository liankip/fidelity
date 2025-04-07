<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Project;
use Illuminate\Http\Request;

class GetItemsSelectTo extends Controller
{
    public function index(Request $request, Project $project)
    {
        if (count($project->boqs_edit_not_approved)) {
            $getlas = $project->boqs_edit_not_approved->sortByDesc("revision");
            // dd($getlas);
            foreach ($getlas as $value) {
                $getrevison = $value->revision;
                break;
            }
            $existingItemIds = $project->boqs_edit_not_approved->where("revision", $getrevison)->pluck("item_id")->toArray();
        }else {
            $existingItemIds = $project->boqs_not_approved()->pluck('item_id')->toArray();
        }

        if ($request->term) {
            return Item::available()->where("name", "like", "%" . $request->term . "%")->whereNotIn('id', $existingItemIds)->with('itemPrice')->get();
        } else {
            return Item::available()->whereNotIn('id', $existingItemIds)->with('itemPrice')->get();
        }
    }
}
