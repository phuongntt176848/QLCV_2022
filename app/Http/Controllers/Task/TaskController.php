<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    public function store(Request $request)
    {
        $sprint = Sprint::find($request->idSprint);
        if (empty($sprint)) {
            return back()->with(['error' => 'Sprint does not exits']);
        }
        try {
            $task = Task::create([
                'content' => 'New Item',
                'description' => 'Description Item',
                'status' => 2,
                'idSprint' => $request->idSprint,
                'idTag' => 1
            ]);

            TaskUser::create([
                'idUser' => auth()->user()->id,
                'idTask' => $task->id
            ]);

            return redirect()->back()->with(['success' => 'Created']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['error' => "Server Error !!!"]);
        }
    }

    public function update(Request $request)
    {
        $task = Task::find($request->task_id);
        if (empty($task)) {
            return redirect()->back()->with(['error' => 'Item does not exits']);
        }

        switch ($request->type) {
            case 'content':
                $task->content = $request->item_content;
                break;
            case 'status':
                $task->status = $request->item_status;
                break;
            case 'tag':
                $task->idTag = $request->item_tag;
                break;
            case 'end_time':
                $task->end_time = $request->end_time;
                break;
            case 'idUser':
                $exits = TaskUser::where([
                    'idUser' => $request->idUser,
                    'idTask' => $task->id
                ])->first();
                if ($exits) {
                    return redirect()->back()->with(['warning' => 'User exits in task']);
                }
                TaskUser::create([
                    'idUser' => $request->idUser,
                    'idTask' => $task->id
                ]);
                break;
            default:
                break;
        }

        $task->save();

        return redirect()->back();
    }

    /**
     * id task
     * content
     * status
     * description
     * end_time
     * idUser
     * user_name
     * user_email
     * user_avt
     * project_id, project_name
     * sprint_title
     */

    public function my_work()
    {
        $overdue = [];
        $today = [];
        $this_week = [];
        $next_week = [];
        $later = [];

        $filterSprints = [];
        $filterProjects = [];
        $filterTasks = [];
        $filterStatus = [];

        $listTaskFilter = [];
        $listSprintFilter = [];
        $listSprintId = [];
        $listProjectFilter = [];
        $listProjectId = [];
        $listStatusFilter = ["Done", "Working on it", "Review", "Test"];

        if (count($_GET) != 0) {
            $filter = isset($_GET['filter']) ? json_decode($_GET['filter']) : [];

            if ($filter !== []) {
                isset($filter->status) ? $filterStatus = $filter->status : [];
                isset($filter->project) ? $filterProjects = $filter->project : [];
                isset($filter->sprint) ? $filterSprints = $filter->sprint : [];
                isset($filter->task) ? $filterTasks = $filter->task : [];
            }
        }

        $time_now = strtotime(date('m/d/Y', time()));
        $thisEndWeek = date('m/d/Y', strtotime('Sunday', strtotime(date('m/d/Y h:i:s a', time()))));
        $nextStartWeek = date('m/d/Y', strtotime('Monday', strtotime(date('m/d/Y', time()))));
        $nextEndWeek = date('m/d/Y', strtotime('Sunday', strtotime($nextStartWeek)));
        $me = User::find(auth()->user()->id);

        $tasks = $me->tasks();

        if (!isset($_GET['item_search'])) {
            if (count($filterTasks) > 0) {
                $taskIds = [];
                foreach ($filterTasks as $task) {
                    if ($task->status == 0) continue;
                    array_push($taskIds, $task->value);
                }
                $tasks = $tasks->whereIn("tasks.id", $taskIds);
            }

            if (count($filterStatus) > 0) {
                $statusList = [];
                foreach ($filterStatus as $status) {
                    if ($status->status == 0) continue;
                    switch ($status->value) {
                        case "Done":
                            array_push($statusList, 1);
                            break;
                        case "Working on it":
                            array_push($statusList, 2);
                            break;
                        case "Review":
                            array_push($statusList, 3);
                            break;
                        case "Test":
                            array_push($statusList, 4);
                            break;
                        default:
                            break;
                    }
                }
                $tasks = $tasks->whereIn("tasks.status", $statusList);
            }

            $tasks = $tasks->get();
        } else $tasks = $me->tasks()->where('content', 'like', $_GET['item_search'] . '%')->get();

        foreach ($tasks as $value) {
            $time_task = strtotime($value->end_time);
            $task = $value->info();
            $user = $value->user()->select('users.id', 'name', 'email', 'avatar')->first();
            $sprint = $value->sprint()->select('id', 'title');

            if (count($filterSprints) > 0) {
                $sprintIds = [];
                foreach ($filterSprints as $s) {
                    if ($s->status == 0) continue;
                    array_push($sprintIds, $s->value); 
                }
                $sprint = $sprint->whereIn("sprints.id", $sprintIds);
            }

            $sprint = $sprint->first();

            if (!isset($sprint)) continue;

            $sprintProject = Sprint::find($sprint->id);

            $project = $sprintProject->project();

            if (count($filterProjects) > 0) {
                $projectIds = [];
                foreach ($filterProjects as $p) {
                    if ($p->status == 0) continue;
                    array_push($projectIds, $p->value);
                }
                $project = $project->whereIn("projects.id", $projectIds);
            }

            $project = $project->first();

            if (!isset($project)) continue;

            array_push($listTaskFilter, ["id" => $value->id, "content" => $value->content]);

            array_push($listSprintId, $sprint->id);
            array_push($listProjectId, $project->id);

            $task['idUser'] = $user->id;
            $task['user_name'] = $user->name;
            $task['user_email'] = $user->email;
            $task['user_avt'] = $user->avatar;
            $task['project_id'] = $project->id;
            $task['project_name'] = $project->name;
            $task['sprint_title'] = $sprint->title;
            $checkTime = abs($time_now - strtotime($value->end_time));
            $checkToday = ($checkTime / 3600) < 24;
            if ($time_task < $time_now) {
                array_push($overdue, $task);
            } else if ($checkToday) {
                array_push($today, $task);
                array_push($this_week, $task);
            } else if (!$checkToday) {
                $checkThisWeek = strtotime($value->end_time) - strtotime($thisEndWeek);
                if ($checkThisWeek <= 0) {
                    array_push($this_week, $task);
                } else {
                    $checkNextStartWeek = strtotime($nextStartWeek);
                    $checkNextEndWeek = strtotime($nextEndWeek);
                    if ($time_task >= $checkNextStartWeek && $time_task <= $checkNextEndWeek) {
                        array_push($next_week, $task);
                    } else if ($time_task > $checkNextEndWeek) {
                        array_push($later, $task);
                    }
                }
            }
        }

        $listSprintId = array_unique($listSprintId);
        foreach ($listSprintId as $sprint_id) {
            $sprint = Sprint::find($sprint_id);
            array_push($listSprintFilter, ["id" => $sprint->id, "title" => $sprint->title]);
        }

        $listProjectId = array_unique($listProjectId);
        foreach ($listProjectId as $project_id) {
            $project = Project::find($project_id);
            array_push($listProjectFilter, ["id" => $project->id, "name" => $project->name]);
        }

        return view('mywork.mywork', [
            'overdues' => $overdue,
            'todays' => $today,
            'thisWeeks' => $this_week,
            'nextWeeks' => $next_week,
            'laters' => $later,
            'statusFilters' => $listStatusFilter,
            'taskFilters' => $listTaskFilter,
            'sprintFilters' => $listSprintFilter,
            'projectFilters' => $listProjectFilter
        ]);
    }

    public function delete(Request $request)
    {
        $ids = preg_split('/,/', $request->idItem);
        foreach ($ids as $id) {
            $task = Task::find($id);
            if (empty($task)) {
                return back()->with(['error' => 'Delete Fail. Task does not exits']);
            }
        }
        Task::destroy($ids);

        return back();
    }

    public function getUserForTask($id)
    {
        $task = Task::find($id);

        if (isset($task)) {
            $idUsers = [];
            $project_id = $_GET["project_id"];

            $project = Project::find($project_id);
            $userInTasks = $task->user()->select('users.id', 'users.name', 'users.email', 'users.avatar')->get();


            foreach ($userInTasks as $user) {
                array_push($idUsers, $user->id);
            }

            $users = $project->user()->select('users.id', 'users.name', 'users.email', 'users.avatar')->whereNotIn('users.id', $idUsers)->get();
            return response()->json(["userInTasks" => $userInTasks, "users" => $users]);
        }
    }

    public function deleteMemberInTask(Request $req)
    {
        $users = TaskUser::where([
            'idTask' => $req->task_id
        ])->get();

        if (count($users) <= 1) {
            return back()->with(["error" => "Not Delete"]);
        }

        TaskUser::where([
            'idTask' => $req->task_id,
            'idUser' => $req->user_id
        ])->delete();

        return back();
    }
}
