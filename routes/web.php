<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Sprint\SprintController;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Team\TeamController;
use App\Http\Controllers\User\ChangePasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home')->middleware(['user_exits']);

Route::prefix('/')->middleware(['user_exits:/'])->group(function () {
    // Login
    Route::get('login', [AuthController::class, 'view_login'])->name('login');
    Route::post('login', [AuthController::class, 'post_login'])->name('post.login');
    // Signup
    Route::get('signup', [AuthController::class, 'view_signup'])->name('signup');
    Route::post('signup', [AuthController::class, 'post_signup'])->name('post.signup');
    Route::get('user/activation/{token}', [AuthController::class, 'activate_user'])->name('user.activate');

    // Login-Signup Google
    Route::post('login-google', [AuthController::class, 'login_google'])->name('login-google');
    Route::get('google/callback', [AuthController::class, 'callback_google']);

    // Forget Password
    Route::get('forgot-password', [ResetPasswordController::class, 'forgot_password'])->name('forgot-password');
    Route::post('forgot_password', [ResetPasswordController::class, 'sendMail'])->name('post.forgot-password');
    // Reset Password
    Route::get('reset-password', [ResetPasswordController::class, 'reset_password'])->name('reset-password');
    Route::put('update-password', [ResetPasswordController::class, 'update_password'])->name('update-password');
});

Route::prefix('/')->middleware(['auth'])->group(function () {
    // Logout
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    //User page
    Route::get('/my-profile', [UserController::class, 'view_profile'])->name('my-profile');
    Route::put('/{id}/edit-name', [UserController::class, 'edit_name'])->name('edit_name');
    Route::put('/{id}/edit-avatar', [UserController::class, 'edit_avatar'])->name('edit_avatar');
    Route::get('/{id}/profile', [UserController::class, 'other_profile'])->name('other_profile');
    Route::put('/update-profile', [UserController::class, 'update_profile'])->name('update_profile');
    // Change Password
    Route::put('/change-password', [ChangePasswordController::class, 'store'])->name('change.password');

    // Invite Member
    Route::post('/invite', [UserController::class, 'invite'])->name('invite.member');

    Route::prefix('/teams')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('my-team');
        Route::post('/', [TeamController::class, 'store'])->name('teams.create');
        Route::get('/search', [TeamController::class, 'search_all'])->name('teams.search.all');
        Route::get('/search_team', [TeamController::class, 'search_team'])->name('teams.search.team');
        Route::get('/{id}/search', [TeamController::class, 'search'])->name('teams.search');
        Route::get('/{id}', [TeamController::class, 'show'])->name('team.show');
        Route::put('/{id}/edit_avatar', [TeamController::class, 'edit_avatar'])->name('team.update.avatar');
        Route::put('/{id}/edit_background', [TeamController::class, 'edit_background'])->name('team.update.bg_team');
        Route::put('/{id}/edit_name', [TeamController::class, 'edit_name'])->name('team.update.name');
        Route::put('/{id}/add_member', [TeamController::class, 'add_member'])->name('team.add.member');
        Route::delete('/{id}/delete_member', [TeamController::class, 'delete_member'])->name('team.delete.member');
        Route::delete('/{id}/delete', [TeamController::class, 'delete_team'])->name('team.delete.team');
    });

    // Project
    Route::get('/project', [ProjectController::class, 'index'])->name('menuproject');
    Route::post('/project/create', [ProjectController::class, 'create'])->name('project.create');
    Route::post('/project/add-member', [ProjectController::class, 'add_member'])->name('project.add-member');
    Route::get('/project/{id}', [ProjectController::class, 'show'])->name('project.show');
    Route::post('/project/{id}', [ProjectController::class, 'search'])->name('project.search');
    Route::patch('/project/{id}', [ProjectController::class, 'edit_info'])->name('project.edit.info');
    Route::delete('/project/{id}', [ProjectController::class, 'delete_project'])->name('project.delete');
    Route::delete('/project/{id}/delete-member', [ProjectController::class, 'delete_member'])->name('member.delete');

    // Sprint
    Route::post('/sprint/create', [SprintController::class, 'store'])->name('sprint.create');
    Route::put('/sprint/edit', [SprintController::class, 'update'])->name('sprint.update');
    Route::delete('/sprint/delete', [SprintController::class, 'delete'])->name('sprint.delete');

    // Task
    Route::post('/task/create', [TaskController::class, 'store'])->name('task.create');
    Route::patch('/task/update', [TaskController::class, 'update'])->name('task.update.info');
    Route::delete('/task/delete', [TaskController::class, 'delete'])->name('task.delete');
    Route::get('/task/get_user/{id}', [TaskController::class, 'getUserForTask']);
    Route::delete('/task/delete_member', [TaskController::class, 'deleteMemberInTask'])->name('task.deleteMemberInTask');
    // Comment
    Route::get('/comment/read', [CommentController::class, 'readComments'])->name('comment.read');
    Route::post('/comment/create', [CommentController::class, 'store'])->name('comment.create');
    Route::put('/comment/edit', [CommentController::class, 'edit_comment'])->name('comment.edit');
    Route::delete('/comment/delete', [CommentController::class, 'deleteComment'])->name('comment.delete');
    Route::get('/comment/read-activity', [CommentController::class, 'readCommentActivity'])->name('comment.read_activity');
    Route::get('/comment/read-mywork', [CommentController::class, 'readCommentMyWork'])->name('comment.read.mywork');
    // My work
    Route::get('/my-work', [TaskController::class, 'my_work'])->name('mywork');
});
