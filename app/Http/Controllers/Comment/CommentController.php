<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $idUser = auth()->user()->id;
        Comment::create([
            'content' => $request->content,
            'idUser' => $idUser,
            'idTask' => $request->idTask
        ]);

        return redirect()->back();
    }
    // lay comment theo task
    public function readComments()
    {
        $task = Task::find($_GET['idTask']);
        $comments = $task->comments()->get();
        $resComments = [];
        $time_now = strtotime(date('m/d/Y h:i:s a', time()));
        foreach ($comments as $value) {
            $comment = [];
            $time = date("F d, Y", strtotime($value->created_at));
            $checkTime = abs($time_now - strtotime($value->created_at));

            if (($checkTime / 3600) < 24) {
                $renTime = floor($checkTime / 3600);
                if ($renTime > 1) $time = strval($renTime) . "h";
                else if (($checkTime / 60) > 1) $time = strval(floor($checkTime / 60)) . "p";
                else $time = strval($checkTime) . "s";
            }

            $user = $value->user()->select('id', 'name', 'email', 'avatar')->first();
            $comment['idTask'] = $_GET['idTask'];
            $comment['id'] = $value->id;
            $comment['content'] = $value->content;
            $comment['user_id'] = $user->id;
            $comment['user_name'] = $user->name;
            $comment['time'] = $time;
            $comment['user_avt'] = $user->avatar;

            array_push($resComments, $comment);
        }

        return response()->json(['comments' => $resComments], 200);
    }

    // lay toan bo comment cua project

    public function readCommentActivity()
    {
        if (isset($_GET['idProject'])){
            $datas = [];
            $project = Project::find($_GET['idProject']);
            if (!empty($project)) {
                $sprints = $project->sprints()->get();

                foreach ($sprints as $sprint) {
                    $tasks = $sprint->tasks()->get();
                    foreach ($tasks as $task) {
                        $sprint['task_id'] = $task->id;
                        $sprint['task_content'] = $task->content;

                        $comments = $task->comments()->get();

                        $time_now = strtotime(date('m/d/Y h:i:s a', time()));
                        foreach ($comments as $value) {
                            $comment = [];
                            $time = date("F d, Y", strtotime($value->created_at));
                            $checkTime = abs($time_now - strtotime($value->created_at));

                            if (($checkTime / 3600) < 24) {
                                $renTime = floor($checkTime / 3600);
                                if ($renTime > 1) $time = strval($renTime) . "h";
                                else if (($checkTime / 60) > 1) $time = strval(floor($checkTime / 60)) . "p";
                                else $time = strval($checkTime) . "s";
                            }

                            $user = $value->user()->select('id', 'name', 'email', 'avatar')->first();
                            $comment['task_id'] = $task->id;
                            $comment['task_content'] = $task->content;
                            $comment['id'] = $value->id;
                            $comment['content'] = $value->content;
                            $comment['user_id'] = $user->id;
                            $comment['user_name'] = $user->name;
                            $comment['time'] = $time;
                            $comment['user_avt'] = $user->avatar;
                            $comment['sprint_id'] = $sprint->id;
                            $comment['sprint_title'] = $sprint->title;

                            array_push($datas, $comment);
                        }
                    }
                }

                return response()->json(['comments' => $datas], 200);
            }
        }
    }

    public function deleteComment(Request $request)
    {
        $comment = Comment::find($request->idComment);
        if (!empty($comment)) {
            Comment::destroy($request->idComment);

            return back();
        }
        return back()->with(['error' => 'Comment does not exits']);
    }

    public function edit_comment(Request $request)
    {
        $comment = Comment::find($request->idComment);
        if (!empty($comment)) {
            $comment->content = $request->comment;
            $comment->save();
            return back();
        }

        return back()->with(['error' => 'Comment does not exits']);
    }


    // lay comment ra mywork
    public function readCommentMyWork()
    {
        if (isset($_GET['idTask'])) {
            $infoTask = [];
            $task = Task::find($_GET['idTask']);
            $userTask = $task->user()->first();
            $sprint = $task->sprint()->first();
            $sprintProject = Sprint::find($sprint->id);
            $project = $sprintProject->project()->first();
            $comments = $task->comments()->get();
            $resComments = [];
            $time_now = strtotime(date('m/d/Y h:i:s a', time()));
            foreach ($comments as $value) {
                $comment = [];
                $time = date("F d, Y", strtotime($value->created_at));
                $checkTime = abs($time_now - strtotime($value->created_at));

                if (($checkTime / 3600) < 24) {
                    $renTime = floor($checkTime / 3600);
                    if ($renTime > 1) $time = strval($renTime) . "h";
                    else if (($checkTime / 60) > 1) $time = strval(floor($checkTime / 60)) . "p";
                    else $time = strval($checkTime) . "s";
                }

                $user = $value->user()->select('id', 'name', 'email', 'avatar')->first();
                $comment['task_id'] = $_GET['idTask'];
                $comment['id'] = $value->id;
                $comment['content'] = $value->content;
                $comment['user_id'] = $user->id;
                $comment['user_name'] = $user->name;
                $comment['time'] = $time;
                $comment['user_avt'] = $user->avatar;

                array_push($resComments, $comment);
            }

            $infoTask['id'] = $task->id;
            $infoTask['content'] = $task->content;
            $infoTask['status'] = $task->status;
            $infoTask['end_time'] = $task->end_time;
            $infoTask['sprint_title'] = $sprint->title;
            $infoTask['user_avt'] = $userTask->avatar;
            $infoTask['project_id'] = $project->id;
            $infoTask['project_name'] = $project->name;

            return response()->json(['comments' => $resComments, 'task' => $infoTask], 200);
        }
    }
}
