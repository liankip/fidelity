<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;

        $notifications = Notification::where('notifiable_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $nottif = Notification::where("notifiable_id", $userId)->orderBy('created_at', 'desc')->get();

        foreach ($nottif as $key => $value) {
            if ($value->read_at == null) {
                Notification::where("id", $value->id)->update([
                    "read_at" => date("Y/m/d"),
                ]);
            }
        }

        return view('notifications.index', compact(['notifications']));
    }

    public function create()
    {
        return view('notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required',
            'receiver' => 'required',
            'notes' => 'required',
            'status' => 'required',
            'created_by' => 'required'
        ]);

        $notification = new Notification;
        $notification->event_id = $request->event_id;
        $notification->receiver = $request->receiver;
        $notification->notes = $request->notes;
        $notification->status = $request->status;
        $notification->created_by = $request->created_by;
        $notification->save();

        return redirect()->route('notifications.index')->with('success', 'Notification has been created successfully.');
    }

    public function show(Notification $notification)
    {
        return view('notifications.show', compact('notification'));
    }

    public function edit(Notification $notification)
    {
        return view('notifications.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'event_id' => 'required',
            'receiver' => 'required',
            'notes' => 'required',
            'status' => 'required',
            'updated_by' => 'required'
        ]);

        $notification = Notification::find($id);
        $notification->event_id = $request->event_id;
        $notification->receiver = $request->receiver;
        $notification->notes = $request->notes;
        $notification->status = $request->status;
        $notification->updated_by = $request->updated_by;
        $notification->save();

        return redirect()->route('notifications.index')->with('success', 'Notification has been updated successfully');
    }

    public function destroy(notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification has been deleted successfully');
    }

    public function deleteAll()
    {
        $userId = auth()->user()->id;
        Notification::where("notifiable_id", $userId)->delete();
        return redirect()->route('notifications.index')->with('success', 'All notification has been deleted successfully');
    }
}
