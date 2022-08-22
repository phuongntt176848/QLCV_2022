<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Tag;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //
    public function index()
    {
        $user_id = auth()->user()->id;

        $me = User::find($user_id);
        $projects = $me->project()->get();

        if (!empty($projects[0])) {
            return redirect()->route('project.show', ['id' => $projects[0]->id]);
        } else {
            return view('project.notproject', ['projects' => $projects, 'projectActive' => 0]);
        }
    }

    public function search(Request $request)
    {
        dump($request->filter);
    }

    public function show($id)
    {

        $search = isset($_GET['iteam_search']) ? $_GET['iteam_search'] : null;
        $filterStatus = [];
        $filterUser = [];
        $filterSprint = [];
        $filterTask = [];
        $filterDateTime = [];
        if (count($_GET) != 0) {
            $filter = isset($_GET['filter']) ? json_decode($_GET['filter']) : [];

            if ($filter !== []) {
                isset($filter->status) ? $filterStatus = $filter->status : [];
                isset($filter->user) ? $filterUser = $filter->user : [];
                isset($filter->sprint) ? $filterSprint = $filter->sprint : [];
                isset($filter->task) ? $filterTask = $filter->task : [];
                isset($filter->datetime) ? $filterDateTime = $filter->datetime : [];
            }
        }

        $user_id = auth()->user()->id;
        $roleUserProject = [];
        $me = User::find($user_id);
        $projects = $me->project()->get();
        $project = Project::find($id);
        if (!empty($project)) {
            $taskOfSprints = [];
            $tags = Tag::get();
            $teams = $me->team()->get();
            $users = $project->user()->select('users.id', 'users.name', 'users.email', 'users.avatar')->get();
            $admin = $project->user()->select('users.name', 'users.avatar')->where('idRole', '=', 1)->first();

            $sprints = $project->sprints();

            if (count($filterSprint) != 0) {
                $sprintIds = [];
                foreach ($filterSprint as $sprint) {
                    if ($sprint->status == 0) continue;
                    array_push($sprintIds, $sprint->value);
                }

                $sprints = $sprints->whereIn('id', $sprintIds);
            }

            $sprints = $sprints->get();

            $idUsers = [];
            foreach ($users as $user) {
                array_push($idUsers, $user->id);
            }

            $allUser = User::whereNotIn('id', $idUsers)->get();

            foreach ($projects as $key => $value) {
                $projects[$key] = $value->info();
            }

            foreach ($sprints as $sprint) {
                $tasks = $sprint->tasks();
                if ($search) {
                    $tasks = $tasks->where('content', 'like', $search . '%');
                }

                if (count($filterDateTime) > 0) {
                    foreach ($filterDateTime as $datetime) {
                        if ($datetime->status == 0) continue;
                        switch ($datetime->value) {
                            case 0:
                                $time_now = date('m/d/Y', time());
                                $tasks = $tasks->where("end_time", "==", $time_now);
                                break;
                            case 1:
                                $day = date('w', strtotime('Sunday', strtotime(date('m/d/Y h:i:s a', time()))));
                                $thisEndWeek = date('m/d/Y', strtotime('Sunday', strtotime(date('m/d/Y h:i:s a', time()))));
                                $thisStartWeek = date('m/d/Y', strtotime('-' . (6 + $day) . ' days'));

                                $tasks = $tasks->where("end_time", ">=", $thisStartWeek)->where("end_time", "<=", $thisEndWeek);
                                # code...
                                break;
                            case 2:
                                $nextStartWeek = date('m/d/Y', strtotime('Monday', strtotime(date('m/d/Y', time()))));
                                $nextEndWeek = date('m/d/Y', strtotime('Sunday', strtotime($nextStartWeek)));

                                $tasks = $tasks->where("end_time", ">=", $nextStartWeek)->where("end_time", "<=", $nextEndWeek);
                                # code...
                                break;
                            case 3:
                                $time_now = date('m/d/Y', time());
                                $tasks = $tasks->where("end_time", "<", $time_now);
                                break;
                            default:
                                # code...
                                break;
                        }
                    }
                }

                if (count($filterTask) > 0) {
                    $taskIds  = [];
                    foreach ($filterTask as $task) {
                        if ($task->status == 0) continue;
                        array_push($taskIds, $task->value);
                    }

                    $tasks = $tasks->whereIn('id', $taskIds);
                }

                if (count($filterStatus) != 0) {
                    $arFlStatus = [];
                    foreach ($filterStatus as $key => $status) {
                        if ($status->status == 0) break;
                        switch ($status->value) {
                            case "Done":
                                array_push($arFlStatus, 1);
                                break;
                            case "Working on it":
                                array_push($arFlStatus, 2);
                                break;
                            case "Review":
                                array_push($arFlStatus, 3);
                                break;
                            case "Test":
                                array_push($arFlStatus, 4);
                                break;
                            default:
                                break;
                        }
                    }

                    $tasks = $tasks->whereIn('status', $arFlStatus);
                }
                
                $tasks = $tasks->get();

                foreach ($tasks as $key => $task) {
                    $userTasks = [];
                    $comments = $task->comments()->get();
                    $commentOfTasks = [];
                    foreach ($comments as $comment) {
                        $commentInfo = [];
                        $user = $comment->user()->select('id', 'name', 'email', 'avatar')->first();
                        $commentInfo['content'] = $comment->content;
                        $commentInfo['user_id'] = $user->id;
                        $commentInfo['user_name'] = $user->name;
                        $commentInfo['user_email'] = $user->email;
                        $commentInfo['user_avt'] = $user->avatar;

                        array_push($commentOfTasks, $commentInfo);
                    }

                    $info = $task->info();

                    $userInTasks = $task->user()->select('users.id', 'name', 'email', 'avatar');

                    if (count($filterUser) > 0) {
                        $_userIds = [];
                        foreach ($filterUser as $user) {
                            if ($user->status == 0) continue;
                            array_push($_userIds, $user->value);
                        }

                        $userInTasks = $userInTasks->whereIn('users.id', $_userIds);
                    }

                    $userInTasks = $userInTasks->get();
                    if(!isset($userInTasks[0])){
                        unset($tasks[$key]);
                        continue;
                    }
                    foreach ($userInTasks as $user) {
                        $userInTask = [];
                        $userInTask['user_id'] = $user->id;
                        $userInTask['user_name'] = $user->name;
                        $userInTask['user_email'] = $user->email;
                        $userInTask['user_avt'] = $user->avatar;

                        array_push($userTasks, $userInTask);
                    }
                    $info['users'] = $userTasks;
                    $info['comments'] = $commentOfTasks;

                    $tasks[$key] = $info;
                }
                $taskOfSprints[$sprint->id] = $tasks;
            }

            foreach ($users as $value) {
                $role = ProjectUser::where([
                    'idProject' => $id,
                    'idUser' => $value->id
                ])->first();
                $roleUserProject[$value->id] = $role->idRole;
            }
            $listSprint = $sprints;
            foreach ($sprints as $key => $sprint) {
                if (!isset($taskOfSprints[$sprint->id][0]) && count($_GET) != 0) {
                    unset($sprints[$key]);
                }
            }
            if ($search || count($filterStatus) != 0) {
                foreach ($taskOfSprints as $key => $taskSprint) {
                    if (empty($taskSprint[0])) {
                        foreach ($sprints as $key1 => $sprint) {
                            if ($sprint->id === $key) unset($listSprint[$key1]);
                        }
                        unset($taskOfSprints[$key]);
                    }
                }
            }
            $listUserFilter = $users;
            $lisTaskFilter = [];
            $listStatusFilter = ["Done", "Working on it", "Review", "Test"];
            $listSprintFilter = [];
            $_sprints = $project->sprints()->get();
            foreach ($_sprints as $sprint) {
                array_push($listSprintFilter, ["sprint_id" => $sprint->id, "title" => $sprint->title]);
                $tasks = $sprint->tasks()->get();
                foreach ($tasks as $key => $task) {
                    array_push($lisTaskFilter, ["task_id" => $task->id, "content" => $task->content]);
                }
            }

            $project = $project->info();
            $project['owner'] = $admin->name;
            $project['owner_avt'] = $admin->avatar;

            return view('project.detailproject', [
                'users' => $users,
                'project' => $project,
                'projects' => $projects,
                'sprints' => $listSprint,
                'projectActive' => $project['id'],
                'tasks' => $taskOfSprints,
                'tags' => $tags,
                'allUser' => $allUser,
                'teams' => $teams,
                'roleUsers' => $roleUserProject,
                'lisTaskFilter' => $lisTaskFilter,
                'listStatusFilter' => $listStatusFilter,
                'listUserFilter' => $listUserFilter,
                'listSprintFilter' => $listSprintFilter
            ]);
        }

        return back();
    }

    public function create(Request $request)
    {
        // check validate co dien du lieu khong
        $request->validate([
            'project_name' => 'required',
            'project_description' => 'required'
        ]);
        // Tao 1 ban ghi trong bang project
        $project = Project::create([
            'name' => $request->project_name,
            'description' => $request->project_description
        ]);
        // them ban ghi xac dinh ai la nguoi tao ra project nay (chinh minh)
        ProjectUser::create([
            'idUser' => auth()->user()->id,
            'idProject' => $project->id,
            'idRole' => 1
        ]);

        return redirect()->back()->with(['success' => 'Created project!']);
    }
    

    // sua ten va mo ta project
    public function edit_info($id, Request $request)
    { 
        $project = Project::find($id);
        if ($request->project_name) {
            $project->name = $request->project_name;
        } else if ($request->project_description) {
            $project->description = $request->project_description;
        }

        $project->save();

        return back();
    }

    public function add_member(Request $request)
    {
        // tach de lay id member
        $members = explode(',', $request->members);
        // tim project tuong ung
        $project = Project::find($request->idProject);
        
        // neu la team thi them toan bo thanh vien team vao project, 
        if ($request->idTeam) {
            $memberCheck = $members;
            $users = $project->user()->select('users.id')->get();
            foreach ($users as $user) {
                array_push($memberCheck, $user->id);
            }
            $teamMembers = TeamUser::whereNotIn('idUser', $memberCheck)->get();
            if (isset($teamMembers[0])) {
                foreach ($teamMembers as $value) {
                    ProjectUser::create([
                        'idUser' => $value->idUser,
                        'idProject' => $project->id,
                        'idRole' => 2
                    ]);
                }
            }
        }
        // neu la member thi them member day vao project
        foreach ($members as $value) {
            if ($value != '') {
                ProjectUser::create([
                    'idUser' => $value,
                    'idProject' => $project->id,
                    'idRole' => 2
                ]);
            }
        }

        return back();
    }

    public function delete_project($id)
    {   
        // lay id cua user
        $idUser = auth()->user()->id;
        // kiem tra quyen, neu la nguoi tao project thi co the xoa project
        $permission = ProjectUser::where([
            'idUser' => $idUser,
            'idProject' => $id
        ])->first();
        $project = Project::find($id);
        if (!empty($project) && $permission->idRole == 1) {
            Project::destroy($id);
            return redirect('/project')->with(['message' => 'Delete Successfully']);
        } else if (empty($project)) {
            return back()->with(['error' => "Project doesn't exits"]);
        } else if ($permission->idRole != 1) {
            return back()->with(['warning' => 'You do not have permission to delete.']);
        }
    }

    public function delete_member($id, Request $request)
    {
        $idRoles = [];
        $ids = preg_split('/,/', $request->idUsers);

        foreach ($ids as $idUser) {
            $role = ProjectUser::where([
                'idProject' => $id,
                'idUser' => $idUser
            ])->first();

            if (empty($role)) {
                return back()->with(['error' => 'Delete Fail. Users does not exits']);
            }

            array_push($idRoles, $role->id);
        }

        ProjectUser::destroy($idRoles);

        return back();
    }
}
