@extends('project.projectmenu', ['projects' => $projects, 'projectActive' => $projectActive])
<div class="first-level-content-project">
    <div class="board-not-project">
        <img src="/images/ux.png" alt="">
        <h2>Not Project!</h2>
        <p>Please create project now.</p>
        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createprojectmodal" id="modal_new_project">Add new project</button>
        </div>
    </div>
</div>