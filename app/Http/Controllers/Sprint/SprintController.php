<?php

namespace App\Http\Controllers\Sprint;

use App\Http\Controllers\Controller;
use App\Models\ProjectUser;
use App\Models\Sprint;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function store(Request $request)
    {
        Sprint::create([
            'title' => 'Sprint',
            'idProject' => $request->idProject
        ]);

        return back();
    }

    public function update(Request $request)
    {
        $sprint = Sprint::find($request->sprint_id);
        if (empty($sprint)){
            return back();
        }

        $sprint->title = $request->sprint_name;
        $sprint->save();
        return back();
    }

    public function delete(Request $request)
    {
        $idUser = auth()->user()->id;
        $permission = ProjectUser::where([
            'idUser' => $idUser,
            'idProject' => $request->idProject
        ])->first();
        if ($permission->idRole == 1) {
            Sprint::destroy($request->id);
            return back()->with(['message' => 'Deleted successfully.']);
        }

        return back()->with(['warning' => 'You do not have permission to delete.']);
    }
}
