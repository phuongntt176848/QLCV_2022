@extends('project.projectmenu', ['projects' => $projects, 'projectActive' => $projectActive])
<div class="project-current-view-wapper">
    <div class="current-board-component">
        <div class="current-board-header-wrapper">
            <div class="board-header-content-wrapper">
                <div class="board-header-animated-content-wrapper">
                    <div class="board-header-main">
                        <div class="board-header-top">
                            <div class="board-header-left">
                                <div class="board-name">
                                    <div class="board-title-component">
                                        <h2 class="title-input" type="name" of="project">{{$project['name']}}</h2>
                                    </div>
                                    <div class="board-header-toggle-show-description-wrapper">
                                        <span>
                                            <button type="button" class="board-header-toggle-show-description" data-toggle="modal" data-target="#inforprojectmodal" id="modal_infor_project">
                                                <i class='bx bx-error-circle'></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="board-header-right">
                                <div class="board-actions">
                                    <div class="board-actions-section subscribers-list-wrapper">
                                        <button class="subscribers-images-component" id="open1">
                                            <i class='bx bx-user-plus'></i>
                                            <span>Add member</span>
                                        </button>
                                    </div>
                                    <div class="board-actions-section subscribers-list-wrapper" onclick="openModalActivity(`{{$project['id']}}`);">
                                        <button class="subscribers-images-component" id="openActivityProject">
                                            <i class='bx bxs-binoculars'></i>
                                            <span>Activity</span>
                                        </button>
                                    </div>
                                    <div class="board-menu">
                                        <button class="board-menu-button" onclick="menuAction();">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <div class="menu-action">
                                            <ul>
                                                <li onclick="menuAction();" style="cursor: pointer;" data-toggle="modal" data-target="#projectmember">
                                                    <i class='bx bx-user-circle'></i>
                                                    <span onmouseout="removeActive();">Project member</span>
                                                </li>
                                                <li onclick="menuAction();" style="cursor: pointer;" data-toggle="modal" data-target="#deleteproject">
                                                    <i class='bx bx-trash'></i>
                                                    <span onmouseout="removeActive();">Delete project</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="board-description-hint">
                            <div class="ds-text-component description-content">
                                <span class="title-input" type="description" of="project">{{$project['description']}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="board-subset-toolbar board-subset-toolbar__tabs">
                        <!-- menu -->
                    </div>
                </div>
                <!-- New Sprint -->
                <div class="board-header-view-bar">
                    <div class="board-nav">
                        <div class="board-subset-left-side-container"></div>
                        <div class="add-new-sprint">
                            <button class="add-new-sprint-button" onclick="createSprint();">New sprint</button>
                        </div>
                        <div class="search-project">
                            <input type="text" placeholder="Search item ..." value="{{isset($_GET['iteam_search']) ? $_GET['iteam_search'] : ''}}" onkeyup="searchItemByName(event);">
                            <i class='bx bx-search'></i>
                        </div>
                        <div class="filter-project">
                            <button class="filter-button" data-toggle="modal" data-target="#filtermodalproject">
                                <i class='bx bx-filter-alt'></i>
                                <span>Filter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!empty($tasks))
        <div class="board-content-component">
            <div class="board-content-wrapper board-white-table">
                <div class="board-content-items">
                    <div class="board-content-blocking-overlay">
                    </div>
                    @if (isset($sprints[0]))
                    <div class="sprint-header-wrapper">
                        @foreach($sprints as $sprint)
                        <!-- Sprint Info -->
                        <div>
                            <div class="sprint-header-component-wrapper">
                                <div class="sprint-header-component-title-row">
                                    <div class="grid-floating-cells-row-component ">
                                        <div class="action-project">
                                            <div class="pulse-menu-component" data-toggle="modal" data-sprint="{{$sprint->id}}" disabled data-target="#deletesprint" onclick="setModalDeleteSprint(this);">
                                                <i class='bx bx-dots-horizontal-rounded'></i>
                                            </div>
                                        </div>
                                        <!-- Sprint Title -->
                                        <div class="sprint-title">
                                            <i class='bx bx-caret-down-circle'></i>
                                            <h4 class="title-input" type="name" of="sprint" idElement="{{$sprint->id}}">{{$sprint->title}}</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Sprint Table -->
                                <div class="sprint-header-component">
                                    <div class="sprint-content-wrapper">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%"></th>
                                                    <th style="width: 40%">Item</th>
                                                    <th style="width: 10%">Person</th>
                                                    <th style="width: 10%">Status</th>
                                                    <th style="width: 10%">Date</th>
                                                    <th style="width: 10%">Tag</th>
                                                    <th style="width: 5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="sprintTable_{{$sprint->id}}">
                                                @if (count($tasks[$sprint->id]) > 0)
                                                @foreach($tasks[$sprint->id] as $task)
                                                <tr>
                                                    <td>
                                                        <div class="form-check align-self-center">
                                                            <input class="form-check-input" type="checkbox" value="{{$task['id']}}" id="defaultCheck{{$sprint->id}}" onchange="setModalSelectedItem(event, this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="item-component">
                                                            <div class="item-left">
                                                                <span class="title-input" type="content" of="item" idElement="{{$task['id']}}">{{$task['content']}}</span>
                                                            </div>
                                                            <div class="item-right" task_name="{{$task['content']}}" task_id="{{$task['id']}}" id="open_{{$sprint->id}}" onclick="openModal(this);">
                                                                <i class='bx bx-message-rounded-add'></i>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="person-component">
                                                            @foreach ($task['users'] as $index => $user)
                                                            @if ($index > 1)
                                                            @break
                                                            @endif
                                                            <img class="person-infor" style="cursor: pointer;" src="{{!$user['user_avt'] ? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $user['user_avt']) ? $user['user_avt'] : asset('storage/'.$user['user_avt']))}}" alt="Avatar" onclick="menuPersonToggle(`person_{{$task['id']}}`, `{{$user['user_id']}}`);">

                                                            <!-- <div class="person-infor-content">
                                                                <div style="display: flex;">
                                                                    <div class="ava-person">
                                                                        <img class="person-infor-ava" src="{{!$user['user_avt'] ? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $user['user_avt']) ? $user['user_avt'] : asset('storage/'.$user['user_avt']))}}" alt="Avatar">
                                                                    </div>
                                                                    <div class="infor-person">
                                                                        <h6>{{$user['user_name']}}</h6>
                                                                        <span>{{$user['user_email']}}</span>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                            @endforeach
                                                            @if (count($task['users']) > 2)
                                                            <div class="hidden-team">
                                                                +{{count($task['users']) - 2}}
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="change-person" style="z-index: 100;" id="person_{{$task['id']}}">
                                                            <div class="search-person">
                                                                <input type="text" name="name" id="person_input_search_{{$task['id']}}" task_id="{{$task['id']}}" onkeyup="searchMember(event, this, `{{auth()->user()->id}}`);" placeholder="Search...">
                                                                <i class='bx bx-search'></i>
                                                            </div>
                                                            <div class="person-list">
                                                                <span>Suggested people</span>
                                                                <div class="person-list-scroll">
                                                                    <ul id="person_ul_{{$task['id']}}">
                                                                        <li>
                                                                            <div class="person-detail">
                                                                                <img class="person-infor-ava" src="/images/user.jpg" alt="">
                                                                                <span>Nguyen Phuong</span>
                                                                            </div>
                                                                            <div class="delete-person">
                                                                                <i class='bx bx-x'></i>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="person-list">
                                                                <span>Selected people</span>
                                                                <div class="person-list-scroll">
                                                                    <ul id="person_ul_selected_{{$task['id']}}">
                                                                        <li>
                                                                            <div class="person-detail">
                                                                                <img class="person-infor-ava" src="/images/user.jpg" alt="">
                                                                                <span>Nguyen Phuong</span>
                                                                            </div>

                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <div class="status-component">
                                                            <select class="form-control" id="exampleFormControlSelect1" task="{{$task['id']}}" onchange="changStatus(event, this);">
                                                                <option {{$task['status'] == 1 ? 'selected' : ''}} value="1">Done</option>
                                                                <option {{$task['status'] == 2 ? 'selected' : ''}} value="2">Working on it</option>
                                                                <option {{$task['status'] == 3 ? 'selected' : ''}} value="3">Review</option>
                                                                <option {{$task['status'] == 4 ? 'selected' : ''}} value="4">Test</option>

                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="date-component ml-2">
                                                            <input type="datetime-local" id="birthday" name="end_time" current="{{date('Y-m-d', strtotime($task['end_time']))}}" value="{{date('Y-m-d H:m', strtotime($task['end_time']))}}" task="{{$task['id']}}" onchange="changeEndTime(event,this);">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="status-component">
                                                            <select class="form-control" id="exampleFormControlSelect1" task="{{$task['id']}}" onchange="changTag(event, this);">
                                                                @if (count($tags) > 0)
                                                                @foreach($tags as $tag)
                                                                <option {{$tag->id == $task['idTag'] ? 'selected' : ''}} value="{{$tag->id}}">{{$tag->name}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="item-action">
                                                            <button type="button" data-toggle="modal" task_id="{{$task['id']}}" data-target="#deleteitem" onclick="setModalDeleteItem(this);">
                                                                <i class='bx bx-trash-alt'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @elseif (isset($_GET['iteam_search']))
                                                <tr>
                                                    <th colspan="7">No results found for '{{isset($_GET['iteam_search']) ? $_GET['iteam_search'] : ''}}'</th>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    @if(!isset($_GET['iteam_search']))
                                    <div class="add-item">
                                        <div class="add-item-button">
                                            <button onclick="addNewItem('{{$sprint->id}}')">
                                                + Add Item
                                            </button>
                                        </div>
                                    </div>
                                    <div class="delete-item-choosed" data-toggle="modal" data-target="#deleteitemselected">
                                        @else
                                        <div class="delete-item-choosed" style="margin-top: 10px;" data-toggle="modal" data-target="#deleteitemselected">
                                            @endif
                                            <button id="btn_item_selected" disabled>
                                                Delete selected items
                                            </button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- End sprint -->
                            @endforeach
                        </div>
                        @elseif(!isset($sprints[0]) && (isset($_GET['iteam_search'])||isset($_GET['filter'])))
                        <div class="search-not-found">
                            <img src="{{asset('images/no-results.png')}}" alt="">
                            <h2> No results found</h2>
                            <p>Try using a different search item.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @elseif(empty($tasks) && (isset($_GET['iteam_search'])||isset($_GET['filter'])))
            <div class="search-not-found">
                <img src="{{asset('images/no-results.png')}}" alt="">
                <h2> No results found</h2>
                <p>Try using a different search item.</p>
            </div>
            @endif
        </div>
    </div>
    <!-- modal information project -->
    <div class="modal fade" id="inforprojectmodal" tabindex="-1" role="dialog" aria-labelledby="inforprojectmodal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="infor-project-container">
                        <div class="infor-project">
                            <div class="content-infor">
                                <h5 class="title-input" type="name" of="project">{{$project['name']}}</h5>
                            </div>
                        </div>
                        <div class="infor-project">
                            <div class="content-infor">
                                <span class="title-input" type="description" of="project">{{$project['description']}}</span>
                            </div>
                        </div>
                        <div class="infor-project">
                            <hr>
                            <label>Owners</label>
                            <div class="content-infor">
                                <img src="{{!$project['owner_avt'] ? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $project['owner_avt']) ? $project['owner_avt'] : asset('storage/'.$project['owner_avt']))}}" alt="Avatar">
                                <span>{{$project['owner']}}</span>
                            </div>
                        </div>
                        <div class="infor-project">
                            <label>Created by</label>
                            <div class="content-infor">
                                <img src="{{asset('images/clock.jpeg')}}" alt="History">
                                <span>{{date("F d, Y", strtotime($project['created_at']))}}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- delete comment modal -->
    <div class="modal fade" id="commentaction" tabindex="-1" aria-labelledby="commentactionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Delete this comment?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('comment.delete')}}" id="project_comment_delete_id" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="idTask" id="input_comment_task_id">
                        <input type="hidden" name="idComment" id="input_comment_id">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal delete sprint  -->

    <div class="modal fade" id="deletesprint" tabindex="-1" aria-labelledby="deletesprintLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Remove this sprint?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('sprint.delete')}}" id="sprint_delete_form" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="idProject" id="sprint_project_id">
                        <input type="hidden" name="id" id="sprint_id">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal delete item -->

    <div class="modal fade" id="deleteitem" tabindex="-1" aria-labelledby="deleteitemLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Remove this item?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('task.delete')}}" id="team_delete_team_member" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="idItem" id="item_delete_input">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal delete all item selceted -->

    <div class="modal fade" id="deleteitemselected" tabindex="-1" aria-labelledby="deleteitemselectedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Remove all selected items?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('task.delete')}}" id="item_delete_selected_id" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="idItem" id="item_selected_delete_input">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal activity item -->
    <div class="modal1" id="activitymodal">
        <div class="modal-activity ">
            <div class="modal-content">
                <div class="pulse_container">
                    <div class="flexible-header">
                        <div class="pulse_title">
                            <div class="pulse_actions_wrapper">
                                <button id="close" class="close-modal ">
                                    <span class="close">&times;</span>
                                </button>
                            </div>
                            <div class="title-wrapper">
                                <div class="title-editable">
                                    <h4 id="modal_task_name">Item 1</h4>
                                </div>
                                <div class="ds-menu-button-container">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                            </div>
                        </div>
                        <div class="item-board-subsets-tabs-wrapper">
                            <div class="item-board-subset-tabs-component">
                                <div class="board-subsets-tabs" id="navbar">

                                    <li>
                                        <a class="btn active" aria-hidden="true">Comment</a>
                                    </li>
                                    <li>
                                        <a class="btn" aria-hidden="true">Activity</a>
                                    </li>
                                    <li>
                                        <a class="btn" aria-hidden="true">Last viewer</a>
                                    </li>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pulse_content_wrapper">
                        <div class="comment_content">
                            <div class="add-comment">
                                <button class="add-comment-button" style="display: block;" id="btnAddcomment" onclick="addComment();">Write a comment...</button>
                                <div class="add-comment-content" style="display: none;" id="showAddcomment">
                                    <form action="{{route('comment.create')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="idTask" id="comment_task_id">
                                        <textarea class="form-control" id="comment_textarea" name="content" placeholder="Enter comment ..." required></textarea>
                                        <div class="comment-button">
                                            <button type="button" class="btn btn-secondary mr-2" onclick="cancelAddComment();">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="list-comment">
                                <div class="comment-post" id="comment-post-id"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- modal add member -->
    <div class="modal2" id="addmembermodal">
        <div class="modal-add-member">
            <div class="modal-content">
                <div class="close-button">
                    <button id="close1" class="close-modal">
                        <span class="close">&times;</span>
                    </button>
                </div>
                <div class="add-member-container">
                    <h4>Add member</h4>
                    <div class="search-input">
                        <input type="text" placeholder="Enter name or email" onkeyup="searchMemberModal(event);" id="add_member_input_id">
                        <div class="list-people" id="modal_list" style="display: none;">
                            <span>People</span>
                            <ul id="modal_list_ul"></ul>
                            <span>Team</span>
                            <ul id="modal_list_team_ul"></ul>
                        </div>
                        <div class="list-people-slected" onmousemove="jQuery('div#modal_list').css('display', 'none');">
                            <label>People selected</label>
                            <ul id="modal_list_selected"></ul>
                        </div>
                        <hr>
                        <form action="{{route('project.add-member')}}" method="post">
                            @csrf
                            <input type="hidden" name="idProject" value="{{$project['id']}}">
                            <input type="hidden" name="members" id="modal_form_member_id">
                            <input type="hidden" name="idTeam" id="modal_form_team_id">
                            <div class="add-member-button">
                                <button type="submit">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete project modal -->
    <div class="modal fade" id="deleteproject" tabindex="-1" aria-labelledby="deleteprojectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Remove this project?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('project.delete', ['id' => $project['id']])}}" id="team_delete_team_member" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- delete project modal -->
    <div class="modal fade" id="deleteproject" tabindex="-1" aria-labelledby="deleteprojectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Remove this project?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('project.delete', ['id' => $project['id']])}}" id="team_delete_team_member" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal project member -->
    <div class="modal fade" id="projectmember" tabindex="-1" role="dialog" aria-labelledby="projectmemberTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Project members</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 5%"></th>
                                <th style="width: 30%">Name</th>
                                <th style="width: 35%">Email</th>
                                <th style="width: 20%">Tile</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($users) > 0)
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="form-check align-self-center">
                                        <input class="form-check-input" value="{{$user->id}}" type="checkbox" onchange="setModalSelectedUser(event, this);" {{$roleUsers[$user->id] == 1 ? 'disabled' : ''}}>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{auth()->user()->id !== $user->id ? route('other_profile', ['id' => $user->id]) : route('my-profile')}}">
                                        <img src="{{!$user->avatar? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $user->avatar) ? $user->avatar : asset('storage/'.$user->avatar))}}">
                                    </a>
                                    <a href="{{auth()->user()->id !== $user->id ? route('other_profile', ['id' => $user->id]) : route('my-profile')}}">{{$user->name}}</a>
                                </td>
                                <td>{{$user->email}}</td>
                                <!-- cho nay hien owner neu la nguoi tao project. con lai la member -->
                                <td>{{$roleUsers[$user->id] == 1 ? 'Owner' : 'Member'}}</td>
                                <td>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" user_id="{{$user->id}}" data-toggle="modal" data-target="#deleteteamuser" onclick="setModalDeleteMember(this)" {{$roleUsers[$user->id] == 1 ? 'disabled' : ''}}>
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end ">
                        <div class="delete-user-choosed" style="margin-top: 10px;" data-toggle="modal" data-target="#deleteuserselected">
                            <button id="btn_user_selected" data-dismiss="modal" disabled>
                                Delete selected user
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal activity project -->
    <div class="modal1" id="activityprojectmodal">
        <div class="modal-activity ">
            <div class="modal-content">
                <div class="pulse_container">
                    <div class="flexible-header">
                        <div class="pulse_title">
                            <div class="pulse_actions_wrapper">
                                <button id="close2" class="close-modal ">
                                    <span class="close">&times;</span>
                                </button>
                            </div>
                            <div class="title-wrapper">
                                <div class="title-editable">
                                    <h4 id="modal_task_name">{{$project['name']}}</h4>
                                </div>
                                <div class="ds-menu-button-container">
                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                </div>
                            </div>
                        </div>
                        <div class="item-board-subsets-tabs-wrapper">
                            <div class="item-board-subset-tabs-component">
                                <div class="board-subsets-tabs" id="navbar">
                                    <li>
                                        <a class="btn active" aria-hidden="true">Comment</a>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pulse_content_wrapper">
                        <div class="comment_content">
                            <div class="list-comment">
                                <div class="comment-post" id="comment-activity-post-id"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- modal delete all user selceted -->

    <div class="modal fade" id="deleteuserselected" tabindex="-1" aria-labelledby="deleteuserselectedLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Remove all selected users?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('member.delete', ['id' => $project['id']])}}" id="item_delete_selected_id" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="idUsers" id="user_selected_delete_input">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal delete member -->

    <div class="modal fade" id="deleteteamuser" tabindex="-1" aria-labelledby="deleteteamuserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <span>Remove this member?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{route('member.delete', ['id' => $project['id']])}}" id="team_delete_team_member" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="idUsers" id="user_delete_input">
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal filter in project -->
    <div class="modal fade" id="filtermodalproject" tabindex="-1" aria-labelledby="filtermodalproject" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Filter in project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <h6 class="ml-3">All columns</h6>
                        <div class="row ml-1">
                            <div class="col-md-3 list filter-sprint">
                                <label>Sprint</label>
                                <div class="filter-list">
                                    <ul>
                                        @foreach($listSprintFilter as $sprint)
                                        <li id="sprint_{{$sprint['sprint_id']}}" onclick="myFunction('sprint',`{{$sprint['sprint_id']}}`)">{{$sprint['title']}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3 list filter-task">
                                <label>Task</label>
                                <div class="filter-list">
                                    <ul>
                                        @foreach($lisTaskFilter as $task)
                                        <li id="task_{{$task['task_id']}}" onclick="myFunction('task',`{{$task['task_id']}}`)">{{$task['content']}}</li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3 list filter-person">
                                <label>Person</label>
                                <div class="filter-list">
                                    <ul>
                                        @if (count($listUserFilter) > 0)
                                        @foreach($listUserFilter as $user)
                                        <li id="user_{{$user->id}}" onclick="myFunction('user',`{{$user->id}}`)">
                                            <img src="{{!$user->avatar? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $user->avatar) ? $user->avatar : asset('storage/'.$user->avatar))}}">
                                            <span>{{$user->name}}</span>
                                        </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-2 list filter-status">
                                <label>Status</label>
                                <div class="filter-list">
                                    <ul>
                                        @foreach($listStatusFilter as $status)
                                        <li id="status_{{$status}}" onclick="myFunction('status','{{$status}}')">{{$status}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- <div class="col-md-2 list filter-date">
                                <label>Date</label>
                                <div class="filter-list">
                                    <ul>
                                        <li id="datetime_3" onclick="myFunction('datetime', 3)">Yesterday</li>
                                        <li id="datetime_0" onclick="myFunction('datetime', 0)">Today</li>
                                        <li id="datetime_1" onclick="myFunction('datetime', 1)">This Week</li>
                                        <li id="datetime_2" onclick="myFunction('datetime', 2)">Next Week</li>
                                    </ul>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="hidden" name="idUsers" id="user_delete_input">
                    <button type="submit" class="btn btn-primary" onclick="myFunctionFilter()">Filter</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('sprint.create')}}" method="post" id="form_create_sprint">
        @csrf
        <input type="hidden" name="idProject" value="{{$project['id']}}">
    </form>

    <form action="{{route('project.edit.info', ['id'=>$project['id']])}}" method="post" id="project_edit_info_form">
        @csrf
        @method('PATCH')
    </form>

    <form action="{{route('sprint.update')}}" method="post" id="sprint_edit_info_form">
        @csrf
        @method('PUT')
    </form>

    <form action="{{route('task.update.info')}}" method="post" id="item_edit_info_form">
        @csrf
        @method('PATCH')
    </form>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        let teamSelect, memberSelected = [],
            itemSelected = [];
        let userSelected = [];
        let suggestions = [<?php
                            foreach ($users as $user) {
                                echo "{user_id: $user->id, user_name: '$user->name', user_avt: '$user->avatar'},";
                            };
                            ?>];

        let allUser = [];
        let teamAll = [];
        let filter = {
            'status': [],
            'sprint': [],
            'task': [],
            'user': [],
            // 'datetime': [],
        };

        function clearFilterModal() {
            const keys = ['status', 'sprint', 'task', 'user'];

            for (let i = 0; i < keys.length; i++) {
                for (let j = 0; j < filter[keys[i]].length; j++) {
                    document.getElementById(`${keys[i]}_${filter[keys[i]][j].value}`).style.backgroundColor = "#f5f6f8";
                    document.getElementById(`${keys[i]}_${filter[keys[i]][j].value}`).style.color = "black";
                }
            }

            filter['status'] = [];
            filter['sprint'] = [];
            filter['task'] = [];
            filter['user'] = [];
            // filter['datetime'] = [];
        }

        function myFunction(key, value) {
            console.log(filter[key], key, value)
            if (filter[key].findIndex(value1 => value1.value == value) !== -1) {
                if (filter[key][filter[key].findIndex(value1 => value1.value == value)].status == 0) filter[key][filter[key].findIndex(value1 => value1.value == value)].status = 1;
                else
                    filter[key][filter[key].findIndex(value1 => value1.value == value)].status = 0;
            } else {
                filter[key] = [...filter[key], {
                    value: value,
                    status: 1
                }]
            }
            for (let i = 0; i < filter[key].length; i++) {
                if (filter[key][i].status == 1) {
                    document.getElementById(`${key}_${filter[key][i].value}`).style.backgroundColor = "blue";
                    document.getElementById(`${key}_${filter[key][i].value}`).style.color = "white";
                } else {
                    document.getElementById(`${key}_${filter[key][i].value}`).style.backgroundColor = "#f5f6f8";
                    document.getElementById(`${key}_${filter[key][i].value}`).style.color = "black";
                }
            }

        }

        function myFunctionFilter() {
            const form = jQuery("<form/>", {
                action: "{{route('project.show', ['id' => $project['id']])}}",
                method: 'GET',
                data: filter
            });

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'filter',
                    value: JSON.stringify(filter)
                })
            );

            jQuery('body').append(form);
            form.submit();
        }
        
        var originalForm = document.getElementsByClassName("title-input");
        const form_edit = document.getElementById('project_edit_info_form');
        const sprint_edit = document.getElementById('sprint_edit_info_form');
        const item_edit = document.getElementById('item_edit_info_form');

        for (var i = 0; i < originalForm.length; i++) {
            originalForm[i].onclick = function() {
                let type = this.getAttribute('type');
                let of_ele = this.getAttribute('of');
                let current_text = '';
                let id = 1;
                if (this.hasAttribute('idElement')) {
                    id = this.getAttribute('idElement');
                }
                if (this.hasAttribute('data-clicked')) {
                    return;
                }
                this.setAttribute('data-clicked', 'yes');
                this.setAttribute('data-text', this.innerHTML);

                var input = document.createElement('input');
                input.setAttribute('type', 'text');
                input.value = this.innerHTML;

                input.onblur = function() {
                    var h2 = input.parentElement;
                    var ori_text = input.parentElement.getAttribute('data-text');
                    current_text = this.value;

                    if (ori_text != current_text) {
                        //there are changes in the h1 text
                        //save to Db with ajax
                        h2.removeAttribute('data-clicked');
                        h2.removeAttribute('data-text')
                        if (current_text) {
                            h2.innerHTML = current_text;
                        }
                    } else {
                        h2.removeAttribute('data-clicked');
                        h2.removeAttribute('data-text');
                        h2.innerHTML = ori_text;
                        console.log('No changes');
                    }
                }

                input.onkeypress = function() {
                    if (event.keyCode == 13) {
                        var h2 = input.parentElement;
                        var ori_text = input.parentElement.getAttribute('data-text');
                        this.blur();
                        if (!current_text) {
                            alert("Don't leave the input field blank !!!")
                            h2.innerHTML = ori_text;
                            return;
                        }

                        submitChange(id, type, of_ele, current_text);
                    }
                }

                this.innerHTML = '';
                this.append(input);
                this.firstElementChild.select();
            }
        }


        function submitChange(id, type, of_ele, value) {
            const input = document.createElement('input');
            switch (of_ele) {
                case "project":
                    input.type = 'hidden';
                    input.name = `project_${type}`;
                    input.value = value;

                    form_edit.append(input);

                    setTimeout(() => {
                        form_edit.submit();
                    }, 500);
                    break;
                case "sprint":
                    const input_id = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `sprint_${type}`;
                    input.value = value;
                    input_id.type = 'hidden';
                    input_id.name = `sprint_id`;
                    input_id.value = id;

                    sprint_edit.append(input_id);
                    sprint_edit.append(input);

                    setTimeout(() => {
                        sprint_edit.submit();
                    }, 500);
                    break;
                case "item":
                    const item_id = document.createElement('input');
                    const item_type = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `item_${type}`;
                    input.value = value;

                    item_id.type = 'hidden';
                    item_id.name = `task_id`;
                    item_id.value = id;

                    item_type.type = 'hidden';
                    item_type.name = `type`;
                    item_type.value = 'content';

                    item_edit.append(input);
                    item_edit.append(item_id);
                    item_edit.append(item_type);

                    setTimeout(() => {
                        item_edit.submit();
                    }, 500);
                    break;
                default:
                    break;
            }
        }

        function menuPersonToggle(id, user_id) {
            const task_id = id.split('_')[1];
            const ul_all = jQuery(`ul#person_ul_${task_id}`);
            const ul_selected = jQuery(`ul#person_ul_selected_${task_id}`);
            const activeList = document.getElementsByClassName('change-person active');
            for (let index = 0; index < activeList.length; index++) {
                if (activeList[index].id != id) {
                    activeList[index].classList.remove('active');
                }
            }

            $.ajax({
                url: `{{url('/task/get_user')}}/${task_id}?project_id={{$project['id']}}`,
                method: 'GET',
                success: (result) => {
                    const render_all = result.users.map(item => {
                        const regex = /(http)(.+)/g;
                        const src = !item.avatar ? "{{asset('images/user.jpg')}}" : (regex.test(item.avatar) ? item.avatar : `{{asset('storage')}}/${item.avatar}`);
                        return (
                            `
                                <li onclick="changeMember(${task_id}, ${item.id}, ${user_id});">
                                    <div class="person-detail">
                                        <img class="person-infor-ava" src="${src}" alt="Avatar">
                                        <span>${item.name}</span>
                                    </div>
                                </li>
                            `
                        );
                    });

                    const render_selected = result.userInTasks.map(item => {
                        const regex = /(http)(.+)/g;
                        const src = !item.avatar ? "{{asset('images/user.jpg')}}" : (regex.test(item.avatar) ? item.avatar : `{{asset('storage')}}/${item.avatar}`);
                        return (
                            `
                                <li onclick="deleteMemberInTask(${task_id}, ${item.id});">
                                    <div class="person-detail">
                                        <img class="person-infor-ava" src="${src}" alt="Avatar">
                                        <span>${item.name}</span>
                                    </div>
                                    <div class="delete-person">
                                        <i class='bx bx-x'></i>
                                    </div>
                                </li>
                            `
                        );
                    });

                    ul_all.empty();
                    ul_all.append(render_all);
                    ul_selected.empty();
                    ul_selected.append(render_selected);
                }
            });

            var toggleMenu = document.getElementById(id);
            toggleMenu.classList.toggle('active');
        };

        function deleteMemberInTask(task_id, user_id) {
            const form = jQuery("<form/>", {
                action: "{{route('task.deleteMemberInTask')}}",
                method: 'POST',
                id: 'form_task_delete_member'
            });

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_token',
                    value: "{{csrf_token()}}"
                })
            );

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_method',
                    value: "DELETE"
                })
            );

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'task_id',
                    value: task_id
                })
            );

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'user_id',
                    value: user_id
                })
            );

            jQuery('body').append(form);

            jQuery('#form_task_delete_member').submit();
        }

        // Comment
        function renderComment(comments) {
            const div = jQuery('div#comment-post-id');
            const render = comments.map((comment, index) => {
                const regex = /(http)(.+)/g;
                const src = !comment.user_avt ? "{{asset('images/user.jpg')}}" : (regex.test(comment.user_avt) ? comment.user_avt : `{{asset('storage')}}/${comment.user_avt}`);
                return (
                    `
                <div class="post">
                    <div class="post-header">
                        <div class="post-header-name">
                            <img src="${src}" alt="Avatar">
                            <h6>${comment.user_name}</h6>
                        </div>
                        <div class="post-header-content">
                            <div class="time">
                                <i class='bx bx-alarm'></i>
                                <span>${comment.time}</span>
                            </div>
                            <div class="action">
                                <button onclick="menuCommentAction(${comment.id});"><i class='bx bxs-down-arrow'></i></button>
                                <div class="menu-comment-action" id="comment_menu_${comment.id}" style="top: ${75 + index * 160}px;">
                                    <ul>
                                        <li onclick="editComment(${comment.id});" style="cursor: pointer;">
                                            <i class='bx bx-pencil'></i>
                                            <span>Edit comment</span>
                                        </li>
                                        <li onclick="setModalDeleteComment(${comment.idTask}, ${comment.id});" style="cursor: pointer;" data-toggle="modal" data-target="#commentaction">
                                            <i class='bx bx-trash'></i>
                                            <span onmouseout="removeCommentActive();">Delete comment</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="post_body_wrapper">
                        <div class="post-body-container" id="commentcontent_${comment.id}" style="display: block;">
                            <p id="comment_${comment.id}_content">${comment.content}</p>
                        </div>
                        <div class="edit-comment" id="showEditcomment_${comment.id}" style="display: none;">
                            <textarea type="text" class="form-control" id="comment_text_${comment.id}">${comment.content}</textarea>
                            <div class="button-edit d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-secondary mr-2" onclick="cancelEditComment(${comment.id})">Close</button>
                                <button type="button" class="btn btn-primary" onclick="submitSaveComment(${comment.id})">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            `
                );
            });

            div.empty();
            div.append(render);
        }

        function openModal(open) {
            const task_name = open.getAttribute('task_name');
            const task_id = open.getAttribute('task_id');
            $.ajax({
                type: 'GET',
                url: `{{route('comment.read')}}?idTask=${task_id}`,
                success: (data) => {
                    console.log(data);
                    jQuery('h4#modal_task_name').text(task_name);
                    jQuery('input#comment_task_id').val(task_id);
                    renderComment(data.comments);
                    modal.style.display = "block";
                }
            });
        }

        // Comment Activity

        function renderCommentActivity(comments) {
            const div = jQuery('div#comment-activity-post-id');
            const render = comments.map((comment, index) => {
                const regex = /(http)(.+)/g;
                const src = !comment.user_avt ? "{{asset('images/user.jpg')}}" : (regex.test(comment.user_avt) ? comment.user_avt : `{{asset('storage')}}/${comment.user_avt}`);
                return (
                    `
                    <div class="post">
                        <div class="post-header" style="padding: 10px;">
                            <div class="post-header-name">
                                <img src="${src}" alt="Avatar">
                                <div class="name-infor">
                                    <span class="user_name">${comment.user_name}</span>
                                    <span class="projectt_link">
                                        <a href="{{route('project.show', ['id' => $project['id']])}}">{{$project['name']}}</a>
                                        <i class='bx bx-chevron-right'></i>
                                        <a href="#">${comment.sprint_title}</a>
                                        <i class='bx bx-chevron-right'></i>
                                        <a href="#">${comment.task_content}</a>
                                    </span>
                                </div>
                            </div>
                            <div class="post-header-content">
                                <div class="time">
                                    <i class='bx bx-alarm'></i>
                                    <span>${comment.time}</span>
                                </div>
                                <div class="action">
                                    <button onclick="menuCommentAction(${comment.id});"><i class='bx bxs-down-arrow'></i></button>
                                    <div class="menu-comment-action" id="comment_menu_${comment.id}" style="top: ${0 + index * 170}px;">
                                        <ul>
                                            <li onclick="editComment(${comment.id});" style="cursor: pointer;">
                                                <i class='bx bx-pencil'></i>
                                                <span>Edit comment</span>
                                            </li>
                                            <li onclick="setModalDeleteComment(${comment.task_id}, ${comment.id});" style="cursor: pointer;" data-toggle="modal" data-target="#commentaction">
                                                <i class='bx bx-trash'></i>
                                                <span>Delete comment</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post_body_wrapper">
                            <div class="post-body-container" id="commentcontent_${comment.id}" style="display: block;">
                                <p id="comment_${comment.id}_content">${comment.content}</p>
                            </div>
                            <div class="edit-comment" id="showEditcomment_${comment.id}" style="display: none;">
                                <textarea type="text" class="form-control" id="comment_text_${comment.id}">${comment.content}</textarea>
                                <div class="button-edit d-flex justify-content-end mt-2">
                                    <button type="button" class="btn btn-secondary mr-2" onclick="cancelEditComment(${comment.id})">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="submitSaveComment(${comment.id})">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    `
                );
            });

            div.empty();
            div.append(render);
        }

        function openModalActivity(project_id) {
            console.log(`{{route('comment.read_activity')}}?idProject=${parseInt(project_id)}`);
            $.ajax({
                type: 'GET',
                url: `{{route('comment.read_activity')}}?idProject=${parseInt(project_id)}`,
                success: (data) => {
                    console.log(data);
                    renderCommentActivity(data.comments);
                },
                error: (error) => {
                    console.log(error);
                }
            })
        }

        const modal = document.getElementById("activitymodal");
        const close = document.getElementById("close");

        close.onclick = function() {
            modal.style.display = "none";
        }

        // add new item
        function addNewItem(id) {
            const form = jQuery("<form/>", {
                action: "{{route('task.create')}}",
                method: 'POST',
                id: 'form_create_task'
            });

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_token',
                    value: "{{csrf_token()}}"
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'idSprint',
                    value: id
                })
            );

            jQuery('body').append(form);

            jQuery('#form_create_task').submit();
        }

        function createSprint() {
            jQuery('#form_create_sprint').submit();
        }


        // modal add member project
        const modal1 = document.getElementById("addmembermodal");
        const close1 = document.getElementById("close1");
        const open1 = document.getElementById("open1");

        function renderMember(members) {
            const ul = jQuery('ul#modal_list_ul');
            const render = members.map(item => {
                const regex = /(http)(.+)/g;
                const src = !item.user_avt ? "{{asset('images/user.jpg')}}" : (regex.test(item.user_avt) ? item.user_avt : `{{asset('storage')}}/${item.user_avt}`);
                return (
                    `
            <li style="cursor: pointer;" onclick="setSelectMember(${item.user_id}, '${item.user_avt}', '${item.user_name}')">
                <div class="person-detail">
                    <img class="person-infor-ava" src="${src}" alt="Avatar">
                    <span>${item.user_name}</span>
                </div>
            </li>
            `
                );
            });

            ul.empty();
            ul.append(render);
        }

        function renderTeam(teams) {
            const ul_team = jQuery('ul#modal_list_team_ul');

            const render_team = teams.map((item) => {
                const src = `{{asset('storage')}}/${item.team_avt}`;
                return (
                    `
                <li style="cursor: pointer;" onclick="setSelectTeam(${item.team_id}, '${item.team_name}', '${item.team_avt}')">
                    <div class="person-detail">
                        <img class="person-infor-ava" src="${src}" alt="">
                        <span>${item.team_name}</span>
                    </div>
                </li>
            `
                )
            });

            ul_team.empty();
            ul_team.append(render_team);
        }

        function renderMemberSelected(members) {
            const ul = jQuery('ul#modal_list_selected');
            const render = members.map(item => {
                const regex = /(http)(.+)/g;
                const src = !item.user_avt ? "{{asset('images/user.jpg')}}" : (regex.test(item.user_avt) ? item.user_avt : `{{asset('storage')}}/${item.user_avt}`);
                return (
                    `
                <li id="member_li_${item.user_id}">
                    <div class="person-detail">
                        <div class="info">
                            <img class="person-infor-ava" src="${src}" alt="Avatar">
                            <span>${item.user_name}</span>
                        </div>
                        <button type="button" onclick="deleteMember(${item.user_id}, '${item.user_avt}', '${item.user_name}');"><i class='bx bx-x'></i></button>
                    </div>
                </li>
            `
                );
            });

            ul.empty();
            ul.append(render);
        }

        open1.onclick = function() {
            allUser = [<?php
                        foreach ($allUser as $user) {
                            echo "{user_id: $user->id, user_name: '$user->name', user_avt: '$user->avatar', user_email: '$user->email'},";
                        };
                        ?>];
            teamAll = [<?php
                        foreach ($teams as $team) {
                            echo "{team_id: $team->id, team_name: '$team->name', team_avt: '$team->img'},";
                        };
                        ?>];
            jQuery('ul#modal_list_selected').empty();
            jQuery('div#modal_list').css('display', 'none');
            teamSelect = undefined;
            memberSelected = [];
            const searchWrapper = document.querySelector(".search-input");

            renderMember(allUser);
            renderTeam(teamAll);

            searchWrapper.classList.add("active");
            modal1.style.display = "block";
        }
        close1.onclick = function() {
            modal1.style.display = "none";
        }

        // Member select
        function searchMemberModal(event) {
            const value = event.target.value;
            const teams = teamAll.filter(team => team.team_name.toLocaleLowerCase().startsWith(value.toLocaleLowerCase()));
            const ul = jQuery('ul#modal_list_ul');
            const members = allUser.filter(item => {
                const checkName = item.user_name.toLocaleLowerCase().startsWith(value.toLocaleLowerCase());
                const checkEmail = item.user_email.toLocaleLowerCase().startsWith(value.toLocaleLowerCase());
                if (checkName) return item;
                else if (checkEmail) return item;
                if (!value) return item;
            });

            const render = members.map(item => {
                const checkName = item.user_name.toLocaleLowerCase().startsWith(value.toLocaleLowerCase());
                const checkEmail = item.user_email.toLocaleLowerCase().startsWith(value.toLocaleLowerCase());
                const regex = /(http)(.+)/g;
                const src = !item.user_avt ? "{{asset('images/user.jpg')}}" : (regex.test(item.user_avt) ? item.user_avt : `{{asset('storage')}}/${item.user_avt}`);
                return (
                    `
            <li style="cursor: pointer;" onclick="setSelectMember(${item.user_id}, '${item.user_avt}', '${item.user_name}')">
                <div class="person-detail">
                    <img class="person-infor-ava" src="${src}" alt="Avatar">
                    ${checkName ? `<span>${item.user_name}</span>` : checkEmail && `<span>${item.user_email}</span>`}
                </div>
            </li>
            `
                );
            });

            ul.empty();
            ul.append(render);
            renderTeam(teams);
        }

        function setSelectMember(user_id, user_avt, user_name) {
            const input_member = jQuery('input#modal_form_member_id');
            memberSelected.push({
                user_id,
                user_name,
                user_avt,
            });
            const idMember = memberSelected.map(item => item.user_id);
            input_member.val(idMember.join(','));
            renderMemberSelected(memberSelected);
            allUser = allUser.filter(item => item.user_id !== user_id);
            renderMember(allUser);
            if (teamSelect) renderTeamSelected(teamSelect);
        }

        function deleteMember(user_id, user_avt, user_name) {
            const input_member = jQuery('input#modal_form_member_id');
            jQuery(`ul#modal_list_selected li#member_li_${user_id}`).remove();
            memberSelected = memberSelected.filter(item => item.user_id !== user_id);
            const idMember = memberSelected.map(item => item.user_id);
            input_member.val(idMember.join(','));
            renderMemberSelected(memberSelected);
            allUser.push({
                user_id,
                user_name,
                user_avt
            });
            renderMember(allUser);
            if (teamSelect) renderTeamSelected(teamSelect);
        }

        // Team select

        function renderTeamSelected({
            team_id,
            team_name,
            team_avt
        }) {
            const ul = jQuery('ul#modal_list_selected');
            const src = `{{asset('storage')}}/${team_avt}`;

            const render =
                `
            <li id="team_li_${team_id}">
                <div class="person-detail">
                    <div class="info">
                        <img class="person-infor-ava" src="${src}" alt="Avatar">
                        <span>${team_name}</span>
                    </div>
                    <button type="button" onclick="deleteTeam(${team_id}, '${team_name}', '${team_avt}');"><i class='bx bx-x'></i></button>
                </div>
            </li>
        `;
            ul.append(render);
        }

        function setSelectTeam(team_id, team_name, team_avt) {
            teamAll = teamAll.filter(team => team.team_id !== team_id);
            teamSelect = {
                team_id,
                team_name,
                team_avt
            };
            jQuery('input#modal_form_team_id').val(team_id);
            jQuery(`ul#modal_list_selected li#team_li_${team_id}`).remove();
            renderTeam(teamAll);
            renderMemberSelected(memberSelected);
            renderTeamSelected(teamSelect);
        }

        function deleteTeam(team_id, team_name, team_avt) {
            teamAll.push({
                team_id,
                team_name,
                team_avt
            });
            teamSelect = undefined;
            jQuery('input#modal_form_team_id').val('');
            jQuery(`ul#modal_list_selected li#team_li_${team_id}`).remove();
            renderTeam(teamAll);
            renderMemberSelected(memberSelected);
        }

        // search member in modal add member project

        function searchMember(event, element, user_id) {
            const id = element.getAttribute('task_id');
            const ul = jQuery(`ul#person_ul_${id}`);
            const value = event.target.value;
            const members = suggestions.filter(item => item.user_name.toLocaleLowerCase().startsWith(value.toLocaleLowerCase()));
            const render = members.map(item => {
                const regex = /(http)(.+)/g;
                const src = !item.user_avt ? "{{asset('images/user.jpg')}}" : (regex.test(item.user_avt) ? item.user_avt : `{{asset('storage')}}/${item.user_avt}`);
                return (
                    `
                    <li onclick="changeMember(${id}, ${item.user_id}, ${user_id});">
                        <div class="person-detail">
                            <img class="person-infor-ava" src="${src}" alt="Avatar">
                            <span>${item.user_name}</span>
                        </div>
                    </li>
                    `
                );
            });

            ul.empty();
            ul.append(render);
        }

        // delete project
        function menuAction() {
            userSelected = [];
            const actionMenu = document.querySelector('.menu-action');
            actionMenu.classList.toggle('active');
        }

        function removeActive() {
            document.querySelector('.menu-action').classList.remove('active');
        }
        // delete comment
        function setModalDeleteComment(task_id, coment_id) {
            jQuery('input#input_comment_task_id').val(task_id);
            jQuery('input#input_comment_id').val(coment_id);
        }

        function menuCommentAction(id) {
            const actionMenu = document.querySelector(`div#comment_menu_${id}`);
            actionMenu.classList.toggle('active');
        }

        function removeCommentActive() {
            document.querySelector('.menu-comment-action').classList.remove('active');
        }

        function setModalDeleteSprint(element) {
            const inputIdProject = jQuery('#sprint_project_id');
            const inputId = jQuery('#sprint_id');
            inputIdProject.val("{{$project['id']}}");
            inputId.val(element.getAttribute('data-sprint'));
        }

        function changStatus(event, element) {
            const form = jQuery("<form/>", {
                action: "{{route('task.update.info')}}",
                method: 'POST',
                id: 'form_update_task'
            });

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_token',
                    value: "{{csrf_token()}}"
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_method',
                    value: "PATCH"
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'task_id',
                    value: element.getAttribute('task')
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'type',
                    value: 'status'
                })
            );

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'item_status',
                    value: event.target.value
                })
            );

            jQuery('body').append(form);

            jQuery('#form_update_task').submit();
        }

        function changTag(event, element) {
            const form = jQuery("<form/>", {
                action: "{{route('task.update.info')}}",
                method: 'POST',
                id: 'form_update_task'
            });

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_token',
                    value: "{{csrf_token()}}"
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_method',
                    value: "PATCH"
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'task_id',
                    value: element.getAttribute('task')
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'type',
                    value: 'tag'
                })
            );

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'item_tag',
                    value: event.target.value
                })
            );

            jQuery('body').append(form);

            jQuery('#form_update_task').submit();
        }

        function changeMember(task_id, idUser, currentMember) {
            if (idUser !== currentMember) {
                const form = jQuery("<form/>", {
                    action: "{{route('task.update.info')}}",
                    method: 'POST',
                    id: 'form_update_task'
                });

                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: '_token',
                        value: "{{csrf_token()}}"
                    })
                );
                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: '_method',
                        value: "PATCH"
                    })
                );
                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: 'task_id',
                        value: task_id
                    })
                );
                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: 'type',
                        value: 'idUser'
                    })
                );

                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: 'idUser',
                        value: idUser
                    })
                );

                jQuery('body').append(form);

                jQuery('#form_update_task').submit();
            }
        }

        function changeEndTime(event, element) {
            const d = new Date();
            const endTime = new Date(event.target.value);
            const current = [d.getFullYear(), d.getMonth() + 1, d.getDate()].join('-');
            const end_time = [endTime.getFullYear(), endTime.getMonth() + 1, endTime.getDate()].join('-');

            console.log(current, end_time);

            if (current <= end_time) {
                const form = jQuery("<form/>", {
                    action: "{{route('task.update.info')}}",
                    method: 'POST',
                    id: 'form_update_task'
                });

                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: '_token',
                        value: "{{csrf_token()}}"
                    })
                );
                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: '_method',
                        value: "PATCH"
                    })
                );
                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: 'task_id',
                        value: element.getAttribute('task')
                    })
                );
                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: 'type',
                        value: 'end_time'
                    })
                );

                form.append(
                    $("<input>", {
                        type: 'hidde',
                        name: 'end_time',
                        value: event.target.value
                    })
                );

                jQuery('body').append(form);

                jQuery('#form_update_task').submit();
            } else {
                alert('You must select a date greater than or equal to the current date.');
                element.value = element.getAttribute('current');
            }
        }

        // add comment
        function addComment() {
            const btnAddcomment = document.getElementById("btnAddcomment");
            const showAddcomment = document.getElementById("showAddcomment");
            btnAddcomment.style.display = "none";
            showAddcomment.style.display = "block";
        }

        function cancelAddComment() {
            jQuery('#comment_textarea').val('');
            const btnAddcomment1 = document.getElementById("btnAddcomment");
            const showAddcomment1 = document.getElementById("showAddcomment");
            btnAddcomment1.style.display = "block";
            showAddcomment1.style.display = "none";
        }

        // edit comment
        function editComment(id) {
            const form = jQuery("<form/>", {
                action: "{{route('comment.edit')}}",
                method: 'POST',
                id: `form_edit_comment_${id}`
            });

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_token',
                    value: "{{csrf_token()}}"
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: '_method',
                    value: "PUT"
                })
            );

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'idComment',
                    value: id
                })
            );

            jQuery('body').append(form);
            const commentcontent = document.getElementById(`commentcontent_${id}`);
            const showEditcomment = document.getElementById(`showEditcomment_${id}`);
            commentcontent.style.display = "none";
            showEditcomment.style.display = "block";
        }

        function cancelEditComment(id) {
            const value = jQuery(`p#comment_${id}_content`).text();
            jQuery(`textarea#comment_text_${id}`).val(value);
            jQuery(`form#form_edit_comment_${id}`).remove();
            const commentcontent = document.getElementById(`commentcontent_${id}`);
            const showEditcomment = document.getElementById(`showEditcomment_${id}`);
            commentcontent.style.display = "block";
            showEditcomment.style.display = "none";
        }

        // Search Item
        function searchItemByName(event) {
            if (event.keyCode === 13) {
                const form = jQuery("<form/>", {
                    action: "{{route('project.show', ['id' => $project['id']])}}",
                    method: 'GET',
                });
                if (event.target.value) {
                    form.append(
                        $("<input>", {
                            type: 'hidde',
                            name: 'iteam_search',
                            value: event.target.value
                        })
                    );
                }
                jQuery('body').append(form);
                form.submit();
            }
        }

        function submitSaveComment(id) {
            const form = jQuery(`form#form_edit_comment_${id}`);
            const comment = jQuery(`textarea#comment_text_${id}`).val();
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'comment',
                    value: comment
                })
            );

            form.submit();
        }

        jQuery('input#add_member_input_id').click(() => {
            jQuery('div#modal_list').css('display', 'block');
        });
        // Delete Item
        function setModalDeleteItem(element) {
            const task_id = element.getAttribute('task_id');
            jQuery('input#item_delete_input').val(task_id);
        }

        function setModalSelectedItem(event, element) {
            const checked = event.target.checked;
            if (checked) {
                itemSelected.push(event.target.value);
            } else {
                itemSelected = itemSelected.filter(item => item !== event.target.value);
            }

            jQuery('input#item_selected_delete_input').val(itemSelected.join(','));

            if (itemSelected.length > 0) {
                jQuery('button#btn_item_selected').prop('disabled', false);
            } else {
                jQuery('button#btn_item_selected').prop('disabled', true);
            }
        }

        // open modal activity project
        var modalActivity = document.getElementById('activityprojectmodal');
        var openActivityProject = document.getElementById('openActivityProject');
        var close2 = document.getElementById('close2');
        openActivityProject.onclick = function() {
            modalActivity.style.display = "block";
        }

        close2.onclick = function() {
            modalActivity.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modalActivity) {
                modalActivity.style.display = "none";
            }
        }

        // edit comment project
        function addComment() {
            const btnAddcomment = document.getElementById("btnAddcomment");
            const showAddcomment = document.getElementById("showAddcomment");
            btnAddcomment.style.display = "none";
            showAddcomment.style.display = "block";
        }
        // Modal delete member
        function setModalSelectedUser(event, element) {
            const checked = event.target.checked;
            if (checked) {
                userSelected.push(event.target.value);
            } else {
                userSelected = userSelected.filter(item => item !== event.target.value);
            }

            jQuery('input#user_selected_delete_input').val(userSelected.join(','));

            if (userSelected.length > 0) {
                jQuery('button#btn_user_selected').prop('disabled', false);
            } else {
                jQuery('button#btn_user_selected').prop('disabled', true);
            }
        }

        function setModalDeleteMember(element) {
            jQuery('input#user_delete_input').val(element.getAttribute('user_id'));
        }
    </script>