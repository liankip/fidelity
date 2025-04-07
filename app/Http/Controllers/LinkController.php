<?php

namespace App\Http\Controllers;

use App\Models\LinkModel;
use App\Models\Task;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function store(Request $request){
        $link = new LinkModel();
 
        $link->type = $request->type;
        $link->source = $request->source;
        $link->target = $request->target;

        $projectId = Task::find($request->source)->project_id;
        $link->project_id = $projectId;
 
        $link->save();
 
        return response()->json([
            "action"=> "inserted",
            "tid" => $link->id
        ]);
    }
 
    public function update($id, Request $request){
        $link = LinkModel::find($id);
 
        $link->type = $request->type;
        $link->source = $request->source;
        $link->target = $request->target;
 
        $link->save();
 
        return response()->json([
            "action"=> "updated"
        ]);
    }
 
    public function destroy($id){
        $link = LinkModel::find($id);
        $link->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
}
