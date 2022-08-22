@extends('layout.master')
<div class="menu-project">
    <div class="project-list-header">
        <h2>Project</h2>
        <button type="button" class="add-new-project-button" data-toggle="modal" data-target="#createprojectmodal" id="modal_new_project">
            <i class='bx bx-plus'></i>
            New project
        </button>

        <!-- Modal -->
        <div class="modal fade" id="createprojectmodal" tabindex="-1" role="dialog" aria-labelledby="createprojectmodal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearForm();">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('project.create')}}" method="POST" enctype="multipart/form-data" id="form_project_create">
                            @csrf
                            <div class="form-group">
                                <label for="form_create_name">Project name</label>
                                <input id="form_create_name" type="text" class="form-control" name="project_name" placeholder="Enter name" value="{{old('project_name')}}">
                                <span style="display: none; color: red;" id="project_name_err">Enter project name</span>
                            </div>
                            <div class="form-group">
                                <label for="form_create_description">Project description</label>
                                <textarea id="form_create_description" class="form-control" name="project_description" placeholder="Enter description">{{old('project_description')}}</textarea>
                                <span style="display: none; color: red;" id="project_description_err">Enter project name</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="clearForm();">Cancel</button>
                        <button type="button" class="btn btn-primary" id="handlerSubmit" onclick="submitCreateProject();">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
    <div class="projects-list-search-container">
        <i class='bx bx-search'></i>
        <input type="text" name="name" placeholder="Search projects" id="project_search_input">
    </div>
    <hr>
    <div class="projects-list-wrapper">
        <ul id="project_render_id">
            @if(isset($project) && !empty($projects))
            @foreach($projects as $project)
            <li>
                <a href="{{route('project.show', ['id' => $project['id']])}}">
                    @if($projectActive == $project['id'])
                    <div class="projects-all-user-item active">
                        @else
                        <div class="projects-all-user-item">
                            @endif
                            <div class="projects-all-user-item">
                                <div class="all-user-projects-list-item">
                                    <div class="project-image-and-name">
                                        <i class='bx bx-layout'></i>
                                        <span>{{$project['name']}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                </a>
            </li>
            @endforeach
            @endif
        </ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script>
    let projectAll = [<?php
                        foreach ($projects as $project) {
                            echo "{id: " . $project['id'] . ", name: '" . $project['name'] . "'},";
                        }
                        ?>];

    function clearForm() {
        document.getElementById('form_create_name').value = '';
        document.getElementById('form_create_description').value = '';
    }

    function submitCreateProject() {
        const form = document.getElementById('form_project_create');
        const name = document.getElementById('form_create_name').value;
        const description = document.getElementById('form_create_description').value;
        const desc_err = document.getElementById('project_description_err');
        const name_err = document.getElementById('project_name_err');

        name_err.style.display = 'none';
        desc_err.style.display = 'none';

        if (name && description) {
            form.submit();
        } else if (!name && !description) {
            name_err.style.display = 'block';
            desc_err.style.display = 'block';
        } else if (!description) {
            desc_err.style.display = 'block';
        } else if (!name) {
            name_err.style.display = 'block';
        }
    }

    jQuery("input#project_search_input").keyup(event => {
        const ul = $('ul#project_render_id');
        const value = event.target.value;
        let render;
        const projects = projectAll.filter(item => item.name.toLocaleLowerCase().startsWith(value.toLocaleLowerCase()));
        if (!projects.length) {
            render = (`
                <li>
                    <a href="#">
                        <div class="projects-all-user-item">
                            <div class="projects-all-user-item">
                                <div class="all-user-projects-list-item">
                                    <div class="project-image-and-name">
                                        <span>No results found for '${value}'</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            `)
        } else render = projects.map((item) => (
            `
                <li>
                    <a href="{{url('/project/')}}/${item.id}">
                        <div class="projects-all-user-item">
                            <div class="projects-all-user-item">
                                <div class="all-user-projects-list-item">
                                    <div class="project-image-and-name">
                                        <i class='bx bx-layout'></i>
                                        <span>${item.name}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            `
        ))
        ul.empty();
        ul.append(render);
    });

    jQuery(document).ready(function($) {
        $(".projects-all-user-item").click(function() {

            // Select all list items
            var listItems = $(".projects-all-user-item");

            // Remove 'active' tag for all list items
            for (let i = 0; i < listItems.length; i++) {
                listItems[i].classList.remove("active");
            }

            // Add 'active' tag for currently selected item
            this.classList.add("active");
        });

        $("#handlerSubmit").click(function() {
            $('span#name_err').css('display', 'none');
            $('span#image_err').css('display', 'none');
            const name = $('input#form_create_name').val();
            const lengthFile = $('input#file-ava').get(0).files.length;

            if (name && lengthFile) {
                $('form#form_team_create').submit();
            } else if (!name && !lengthFile) {
                $('span#name_err').css('display', 'block');
                $('span#image_err').css('display', 'block');
            } else if (!lengthFile) {
                $('span#image_err').css('display', 'block');
            } else if (!name) {
                $('span#name_err').css('display', 'block');
            }
        });
    });
</script>