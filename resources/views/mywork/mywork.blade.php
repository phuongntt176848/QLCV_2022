@extends('layout.master')
<div class="my-work-container">
    <div class="my-work-level-content-wrapper">
        <div class="my-work-level-content">
            <div class="overview-component">
                <div class="overview-wrapper">
                    <div class="my-work-overview-header">
                        <div class="overview-header-content-wrapper">
                            <div class="top-block">
                                <div class="header">
                                    <span>
                                        <h1>My work</h1>
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <div class="bottom-block">
                                <div class="actions-section">
                                    <!-- Search -->
                                    <div class="search">
                                        <input type="text" id="searchMywork" placeholder="Search..." value="{{isset($_GET['item_search']) ? $_GET['item_search'] : ''}}" onkeyup="searchItemMyWork(event, this)" onchange="searchItemMyWork(event, this)" />
                                        <span class="icon"><i class='bx bx-search'></i></span>
                                    </div>
                                    <div class="filter-project">
                                        <button class="filter-button" data-toggle="modal" data-target="#filtermodalmywork">
                                            <i class='bx bx-filter-alt'></i>
                                            <span>Filter</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (empty($overdues) && empty($todays) && empty($thisWeeks) && empty($nextWeeks) && empty($laters) && isset($_GET['item_search']))
                    <div class="search-not-found">
                        <img src="{{asset('images/no-results.png')}}" alt="">
                        <h2> No results found for '{{isset($_GET['item_search']) ? $_GET['item_search'] : ''}}'</h2>
                        <p>Try using a different search item.</p>
                    </div>
                    @else
                    <div class="overview-section-wrapper">
                        <div class="board-wrapper">
                            <div class="overview-section-inview-wrapper">
                                <div class="board-content-component">
                                    <div class="list-view-overview-section-component">
                                        <div class="list-view-overview-section-container">
                                            <!-- Overdue -->
                                            <div class="section-wrapper">
                                                <div class="section-header-component">
                                                    <div class="toggle-wrapper">
                                                        <button {{count($overdues) <= 0 ? 'disabled' : ''}} id="btn_overdue_id" onclick="openListItem('section_overdue_id', 'overdue_div_id', 'btn_overdue_id');"><i class='bx bx-chevron-right'></i></button>
                                                    </div>
                                                    <span class="section-header">
                                                        <span>Overdue / </span>
                                                        <span class="items-count">{{isset($overdues) ? count($overdues)." items": ''}}</span>
                                                    </span>
                                                </div>
                                                @if (count($overdues) > 0)
                                                <div class="section-content-component" count="{{count($overdues)}}" id="section_overdue_id" style="display: none;">
                                                    <div class="title-item">
                                                        <span class="my-work-column-header" style="width: 260px; text-align: -webkit-match-parent;">Item</span>
                                                        <span class="my-work-column-header" style="width: 210px; display: inline-block;">Project</span>
                                                        <span class="my-work-column-header" style="width: 180px; display: inline-block;">Sprint</span>
                                                        <span class="my-work-column-header" style="width: 120px; display: inline-block;">People</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Status</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Date</span>
                                                        <span class="my-work-column-header" style="width: 100px; display: inline-block;"> Action</span>
                                                    </div>
                                                    <div class="item-container" id="overdue_div_id">
                                                        @foreach ($overdues as $overdue)
                                                        <div class="item-content">
                                                            <div class="item-content-left">
                                                                <div class="item-left">
                                                                    <div class="grid-cell-component-inner">
                                                                        <div class="item-content">
                                                                            <input type="text" current_value="{{$overdue['content']}}" value="{{$overdue['content']}}" task_id="{{$overdue['id']}}" onkeyup="submitChangeItemContent(event, this);">
                                                                        </div>
                                                                        <div class="comment-item" data-toggle="modal" data-target="#commentmywork" task_id="{{$overdue['id']}}" onclick="setModalComment(this);">
                                                                            <i class='bx bx-message-rounded-add'></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="item-content-right">
                                                                <div class="grid-cell-component-wrapper" style="width: 210px;">
                                                                    <div class="cell-component" style="max-width: 210px;">
                                                                        <a href="{{route('project.show', ['id' => $overdue['project_id']])}}">
                                                                            <span class="project-name">{{$overdue['project_name']}}</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 180px;">
                                                                    <div class="cell-component" style="max-width: 180px;">
                                                                        <div class="sprint-name">
                                                                            <li>
                                                                                <span>{{$overdue['sprint_title']}}</span>
                                                                            </li>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 120px;">
                                                                    <div class="cell-component">
                                                                        <div class="people-ava-my-work">
                                                                            <a href="{{auth()->user()->id !== $overdue['idUser'] ? route('other_profile', ['id' => $overdue['idUser']]) : route('my-profile')}}">
                                                                                <img src="{{!$overdue['user_avt']? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $overdue['user_avt']) ? $overdue['user_avt'] : asset('storage/'.$overdue['user_avt']))}}" alt="Avatar">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="status-component-my-work">
                                                                            <select class="form-control status" id="exampleFormControlSelect1" task="{{$overdue['id']}}" onchange="changStatus(event, this);">
                                                                                <option {{$overdue['status'] == 1 ? 'selected' : ''}} value="1">Done</option>
                                                                                <option {{$overdue['status'] == 2 ? 'selected' : ''}} value="2">Working on it</option>
                                                                                <option {{$overdue['status'] == 3 ? 'selected' : ''}} value="3">Review</option>
                                                                                <option {{$overdue['status'] == 4 ? 'selected' : ''}} value="4">Test</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="date-component-my-work">
                                                                            <input type="datetime-local" id="birthday" name="end_time" current="{{date('Y-m-d', strtotime($overdue['end_time']))}}" value="{{date('Y-m-d H:m', strtotime($overdue['end_time']))}}" task="{{$overdue['id']}}" onchange="changeEndTime(event,this);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 100px;">
                                                                    <div class="cell-component">
                                                                        <div class="item-action-my-work">
                                                                            <button type="button" data-toggle="modal" data-target="#deleteitemmywork" task_id="{{$overdue['id']}}" onclick="setModalDeleteItem(this);">
                                                                                <i class='bx bx-trash-alt'></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <!-- Today -->
                                            <div class="section-wrapper" id="todayListItem">
                                                <div class="section-header-component">
                                                    <div class="toggle-wrapper">
                                                        <button {{count($todays) <= 0 ? 'disabled' : ''}} id="btn_today_id" onclick="openListItem('section_today_id', 'today_div_id', 'btn_today_id');"><i class='bx bx-chevron-right'></i></button>
                                                    </div>
                                                    <span class="section-header">
                                                        <span>Today / </span>
                                                        <span class="items-count">{{isset($todays) ? count($todays)." items": ''}}</span>
                                                    </span>
                                                </div>
                                                @if (count($todays) > 0)
                                                <div class="section-content-component" count="{{count($todays)}}" id="section_today_id" style="display: none;">
                                                    <div class="title-item">
                                                        <span class="my-work-column-header" style="width: 260px; text-align: -webkit-match-parent;">Item</span>
                                                        <span class="my-work-column-header" style="width: 210px; display: inline-block;">Project</span>
                                                        <span class="my-work-column-header" style="width: 180px; display: inline-block;">Sprint</span>
                                                        <span class="my-work-column-header" style="width: 120px; display: inline-block;">People</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Status</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Date</span>
                                                        <span class="my-work-column-header" style="width: 100px; display: inline-block;"> Action</span>
                                                    </div>
                                                    <div class="item-container" id="today_div_id">
                                                        @foreach ($todays as $today)
                                                        <div class="item-content">
                                                            <div class="item-content-left">
                                                                <div class="item-left">
                                                                    <div class="grid-cell-component-inner">
                                                                        <div class="item-content">
                                                                            <input type="text" value="{{$today['content']}}" current_value="{{$today['content']}}" task_id="{{$today['id']}}" onkeyup="submitChangeItemContent(event, this);">
                                                                        </div>
                                                                        <div class="comment-item" data-toggle="modal" data-target="#commentmywork" task_id="{{$today['id']}}" onclick="setModalComment(this);">
                                                                            <i class='bx bx-message-rounded-add'></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="item-content-right">
                                                                <div class="grid-cell-component-wrapper" style="width: 210px;">
                                                                    <div class="cell-component" style="max-width: 210px;">
                                                                        <a href="{{route('project.show', ['id' => $today['project_id']])}}">
                                                                            <span class="project-name">{{$today['project_name']}}</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 180px;">
                                                                    <div class="cell-component" style="max-width: 180px;">
                                                                        <div class="sprint-name">
                                                                            <li>
                                                                                <span>{{$today['sprint_title']}}</span>
                                                                            </li>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 120px;">
                                                                    <div class="cell-component">
                                                                        <div class="people-ava-my-work">
                                                                            <a href="{{auth()->user()->id !== $today['idUser'] ? route('other_profile', ['id' => $today['idUser']]) : route('my-profile')}}">
                                                                                <img src="{{!$today['user_avt']? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $today['user_avt']) ? $today['user_avt'] : asset('storage/'.$today['user_avt']))}}" alt="Avatar">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="status-component-my-work">
                                                                            <select class="form-control status" id="exampleFormControlSelect1" task="{{$today['id']}}" onchange="changStatus(event, this);">
                                                                                <option {{$today['status'] == 1 ? 'selected' : ''}} value="1">Done</option>
                                                                                <option {{$today['status'] == 2 ? 'selected' : ''}} value="2">Working on it</option>
                                                                                <option {{$today['status'] == 3 ? 'selected' : ''}} value="3">Review</option>
                                                                                <option {{$today['status'] == 4 ? 'selected' : ''}} value="4">Test</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="date-component-my-work">
                                                                            <input type="datetime-local" id="birthday" name="end_time" current="{{date('Y-m-d', strtotime($today['end_time']))}}" value="{{date('Y-m-d H:m', strtotime($today['end_time']))}}" task="{{$today['id']}}" onchange="changeEndTime(event,this);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 100px;">
                                                                    <div class="cell-component">
                                                                        <div class="item-action-my-work">
                                                                            <button type="button" data-toggle="modal" data-target="#deleteitemmywork" task_id="{{$today['id']}}" onclick="setModalDeleteItem(this);">
                                                                                <i class='bx bx-trash-alt'></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <!-- This Week -->
                                            <div class="section-wrapper" id="thisWeekListItems">
                                                <div class="section-header-component">
                                                    <div class="toggle-wrapper">
                                                        <button {{count($thisWeeks) <= 0 ? 'disabled' : ''}} id="btn_thisweek_id" onclick="openListItem('section_thisweek_id', 'thisweek_div_id', 'btn_thisweek_id');"><i class='bx bx-chevron-right'></i></button>
                                                    </div>
                                                    <span class="section-header">
                                                        <span>This week / </span>
                                                        <span class="items-count">{{isset($thisWeeks) ? count($thisWeeks)." items": ''}}</span>
                                                    </span>
                                                </div>
                                                @if (count($thisWeeks) > 0)
                                                <div class="section-content-component" count="{{count($thisWeeks)}}" id="section_thisweek_id" style="display: none;">
                                                    <div class="title-item">
                                                        <span class="my-work-column-header" style="width: 260px; text-align: -webkit-match-parent;">Item</span>
                                                        <span class="my-work-column-header" style="width: 210px; display: inline-block;">Project</span>
                                                        <span class="my-work-column-header" style="width: 180px; display: inline-block;">Sprint</span>
                                                        <span class="my-work-column-header" style="width: 120px; display: inline-block;">People</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Status</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Date</span>
                                                        <span class="my-work-column-header" style="width: 100px; display: inline-block;"> Action</span>
                                                    </div>
                                                    <div class="item-container" id="thisweek_div_id">
                                                        @foreach ($thisWeeks as $thisWeek)
                                                        <div class="item-content">
                                                            <div class="item-content-left">
                                                                <div class="item-left">
                                                                    <div class="grid-cell-component-inner">
                                                                        <div class="item-content">
                                                                            <input type="text" current_value="{{$thisWeek['content']}}" value="{{$thisWeek['content']}}" task_id="{{$thisWeek['id']}}" onkeyup="submitChangeItemContent(event, this);">
                                                                        </div>
                                                                        <div class="comment-item" data-toggle="modal" data-target="#commentmywork" task_id="{{$thisWeek['id']}}" onclick="setModalComment(this);">
                                                                            <i class='bx bx-message-rounded-add'></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="item-content-right">
                                                                <div class="grid-cell-component-wrapper" style="width: 210px;">
                                                                    <div class="cell-component" style="max-width: 210px;">
                                                                        <a href="{{route('project.show', ['id' => $thisWeek['project_id']])}}">
                                                                            <span class="project-name">{{$thisWeek['project_name']}}</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 180px;">
                                                                    <div class="cell-component" style="max-width: 180px;">
                                                                        <div class="sprint-name">
                                                                            <li>
                                                                                <span>{{$thisWeek['sprint_title']}}</span>
                                                                            </li>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 120px;">
                                                                    <div class="cell-component">
                                                                        <div class="people-ava-my-work">
                                                                            <a href="{{auth()->user()->id !== $thisWeek['idUser'] ? route('other_profile', ['id' => $thisWeek['idUser']]) : route('my-profile')}}">
                                                                                <img src="{{!$thisWeek['user_avt']? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $thisWeek['user_avt']) ? $thisWeek['user_avt'] : asset('storage/'.$thisWeek['user_avt']))}}" alt="Avatar">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="status-component-my-work">
                                                                            <select class="form-control status" id="exampleFormControlSelect1" task="{{$thisWeek['id']}}" onchange="changStatus(event, this);">
                                                                                <option {{$thisWeek['status'] == 1 ? 'selected' : ''}} value="1">Done</option>
                                                                                <option {{$thisWeek['status'] == 2 ? 'selected' : ''}} value="2">Working on it</option>
                                                                                <option {{$thisWeek['status'] == 3 ? 'selected' : ''}} value="3">Review</option>
                                                                                <option {{$thisWeek['status'] == 4 ? 'selected' : ''}} value="4">Test</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="date-component-my-work">
                                                                            <input type="datetime-local" id="birthday" name="end_time" current="{{date('Y-m-d', strtotime($thisWeek['end_time']))}}" value="{{date('Y-m-d H:m', strtotime($thisWeek['end_time']))}}" task="{{$thisWeek['id']}}" onchange="changeEndTime(event,this);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 100px;">
                                                                    <div class="cell-component">
                                                                        <div class="item-action-my-work">
                                                                            <button type="button" data-toggle="modal" data-target="#deleteitemmywork" task_id="{{$thisWeek['id']}}" onclick="setModalDeleteItem(this);">
                                                                                <i class='bx bx-trash-alt'></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <!-- This Next Week -->
                                            <div class="section-wrapper" id="">
                                                <div class="section-header-component">
                                                    <div class="toggle-wrapper">
                                                        <button {{count($nextWeeks) <= 0 ? 'disabled' : ''}} id="btn_nextweek_id" onclick="openListItem('section_nextweek_id', 'nextweek_div_id', 'btn_nextweek_id');"><i class='bx bx-chevron-right'></i></button>
                                                    </div>
                                                    <span class="section-header">
                                                        <span>Next week / </span>
                                                        <span class="items-count">{{isset($nextWeeks) ? count($nextWeeks)." items": ''}}</span>
                                                    </span>
                                                </div>
                                                @if (count($nextWeeks) > 0)
                                                <div class="section-content-component" count="{{count($nextWeeks)}}" id="section_nextweek_id" style="display: none;">
                                                    <div class="title-item">
                                                        <span class="my-work-column-header" style="width: 260px; text-align: -webkit-match-parent;">Item</span>
                                                        <span class="my-work-column-header" style="width: 210px; display: inline-block;">Project</span>
                                                        <span class="my-work-column-header" style="width: 180px; display: inline-block;">Sprint</span>
                                                        <span class="my-work-column-header" style="width: 120px; display: inline-block;">People</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Status</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Date</span>
                                                        <span class="my-work-column-header" style="width: 100px; display: inline-block;"> Action</span>
                                                    </div>
                                                    <div class="item-container" id="nextweek_div_id">
                                                        @foreach ($nextWeeks as $nextWeek)
                                                        <div class="item-content">
                                                            <div class="item-content-left">
                                                                <div class="item-left">
                                                                    <div class="grid-cell-component-inner">
                                                                        <div class="item-content">
                                                                            <input type="text" current_value="{{$nextWeek['content']}}" value="{{$nextWeek['content']}}" task_id="{{$nextWeek['id']}}" onkeyup="submitChangeItemContent(event, this);">
                                                                        </div>
                                                                        <div class="comment-item" data-toggle="modal" data-target="#commentmywork" task_id="{{$nextWeek['id']}}" onclick="setModalComment(this);">
                                                                            <i class='bx bx-message-rounded-add'></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="item-content-right">
                                                                <div class="grid-cell-component-wrapper" style="width: 210px;">
                                                                    <div class="cell-component" style="max-width: 210px;">
                                                                        <a href="{{route('project.show', ['id' => $nextWeek['project_id']])}}">
                                                                            <span class="project-name">{{$nextWeek['project_name']}}</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 180px;">
                                                                    <div class="cell-component" style="max-width: 180px;">
                                                                        <div class="sprint-name">
                                                                            <li>
                                                                                <span>{{$nextWeek['sprint_title']}}</span>
                                                                            </li>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 120px;">
                                                                    <div class="cell-component">
                                                                        <div class="people-ava-my-work">
                                                                            <a href="{{auth()->user()->id !== $nextWeek['idUser'] ? route('other_profile', ['id' => $nextWeek['idUser']]) : route('my-profile')}}">
                                                                                <img src="{{!$nextWeek['user_avt']? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $nextWeek['user_avt']) ? $nextWeek['user_avt'] : asset('storage/'.$nextWeek['user_avt']))}}" alt="Avatar">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="status-component-my-work">
                                                                            <select class="form-control status" id="exampleFormControlSelect1" task="{{$nextWeek['id']}}" onchange="changStatus(event, this);">
                                                                                <option {{$nextWeek['status'] == 1 ? 'selected' : ''}} value="1">Done</option>
                                                                                <option {{$nextWeek['status'] == 2 ? 'selected' : ''}} value="2">Working on it</option>
                                                                                <option {{$nextWeek['status'] == 3 ? 'selected' : ''}} value="3">Review</option>
                                                                                <option {{$nextWeek['status'] == 4 ? 'selected' : ''}} value="4">Test</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="date-component-my-work">
                                                                            <input type="datetime-local" id="birthday" name="end_time" current="{{date('Y-m-d', strtotime($nextWeek['end_time']))}}" value="{{date('Y-m-d H:m', strtotime($nextWeek['end_time']))}}" task="{{$nextWeek['id']}}" onchange="changeEndTime(event,this);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 100px;">
                                                                    <div class="cell-component">
                                                                        <div class="item-action-my-work">
                                                                            <button type="button" data-toggle="modal" data-target="#deleteitemmywork" task_id="{{$nextWeek['id']}}" onclick="setModalDeleteItem(this);">
                                                                                <i class='bx bx-trash-alt'></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <!-- Later -->
                                            <div class="section-wrapper">
                                                <div class="section-header-component">
                                                    <div class="toggle-wrapper">
                                                        <button {{count($laters) <= 0 ? 'disabled' : ''}} id="btn_later_id" onclick="openListItem('section_later_id', 'later_div_id', 'btn_later_id');"><i class='bx bx-chevron-right'></i></button>
                                                    </div>
                                                    <span class="section-header">
                                                        <span>Later / </span>
                                                        <span class="items-count">{{isset($laters) ? count($laters)." items": ''}}</span>
                                                    </span>
                                                </div>
                                                @if (count($laters) > 0)
                                                <div class="section-content-component" count="{{count($laters)}}" id="section_later_id" style="display: none;">
                                                    <div class="title-item">
                                                        <span class="my-work-column-header" style="width: 260px; text-align: -webkit-match-parent;">Item</span>
                                                        <span class="my-work-column-header" style="width: 210px; display: inline-block;">Project</span>
                                                        <span class="my-work-column-header" style="width: 180px; display: inline-block;">Sprint</span>
                                                        <span class="my-work-column-header" style="width: 120px; display: inline-block;">People</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Status</span>
                                                        <span class="my-work-column-header" style="width: 140px; display: inline-block;">Date</span>
                                                        <span class="my-work-column-header" style="width: 100px; display: inline-block;"> Action</span>
                                                    </div>
                                                    <div class="item-container" id="today_div_id">
                                                        @foreach ($laters as $later)
                                                        <div class="item-content">
                                                            <div class="item-content-left">
                                                                <div class="item-left">
                                                                    <div class="grid-cell-component-inner">
                                                                        <div class="item-content">
                                                                            <input type="text" current_value="{{$later['content']}}" value="{{$later['content']}}" task_id="{{$later['id']}}" onkeyup="submitChangeItemContent(event, this);">
                                                                        </div>
                                                                        <div class="comment-item" data-toggle="modal" data-target="#commentmywork" task_id="{{$later['id']}}" onclick="setModalComment(this);">
                                                                            <i class='bx bx-message-rounded-add'></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="item-content-right">
                                                                <div class="grid-cell-component-wrapper" style="width: 210px;">
                                                                    <div class="cell-component" style="max-width: 210px;">
                                                                        <a href="{{route('project.show', ['id' => $later['project_id']])}}">
                                                                            <span class="project-name">{{$later['project_name']}}</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 180px;">
                                                                    <div class="cell-component" style="max-width: 180px;">
                                                                        <div class="sprint-name">
                                                                            <li>
                                                                                <span>{{$later['sprint_title']}}</span>
                                                                            </li>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 120px;">
                                                                    <div class="cell-component">
                                                                        <div class="people-ava-my-work">
                                                                            <a href="{{auth()->user()->id !== $later['idUser'] ? route('other_profile', ['id' => $later['idUser']]) : route('my-profile')}}">
                                                                                <img src="{{!$later['user_avt']? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $later['user_avt']) ? $later['user_avt'] : asset('storage/'.$later['user_avt']))}}" alt="Avatar">
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="status-component-my-work">
                                                                            <select class="form-control status" id="exampleFormControlSelect1" task="{{$later['id']}}" onchange="changStatus(event, this);">
                                                                                <option {{$later['status'] == 1 ? 'selected' : ''}} value="1">Done</option>
                                                                                <option {{$later['status'] == 2 ? 'selected' : ''}} value="2">Working on it</option>
                                                                                <option {{$later['status'] == 3 ? 'selected' : ''}} value="3">Review</option>
                                                                                <option {{$later['status'] == 4 ? 'selected' : ''}} value="4">Test</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 140px;">
                                                                    <div class="cell-component">
                                                                        <div class="date-component-my-work">
                                                                            <input type="datetime-local" id="birthday" name="end_time" current="{{date('Y-m-d', strtotime($later['end_time']))}}" value="{{date('Y-m-d H:m', strtotime($later['end_time']))}}" task="{{$later['id']}}" onchange="changeEndTime(event,this);">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid-cell-component-wrapper" style="width: 100px;">
                                                                    <div class="cell-component">
                                                                        <div class="item-action-my-work">
                                                                            <button type="button" data-toggle="modal" data-target="#deleteitemmywork" task_id="{{$later['id']}}" onclick="setModalDeleteItem(this);">
                                                                                <i class='bx bx-trash-alt'></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal delete item -->
<div class="modal fade" id="deleteitemmywork" tabindex="-1" aria-labelledby="deleteitemmyworkLabel" aria-hidden="true">
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
<!-- modal cooment item -->
<div class="modal fade" id="commentmywork" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="content-wrapper">
                <div class="values-and-header-wrapper">
                    <div class="page-header-component">
                        <button type="button" class="close-pulse-button style-button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="page-name-wrapper">
                            <div class="ds-text-component">
                                <span class="span_task_content_id"></span>
                            </div>
                        </div>
                        <div class="link-project">
                            <span>in <i class='bx bx-arrow-back bx-flip-horizontal'></i></span>
                            <a id="a_project_name_id"></a>
                        </div>
                    </div>
                    <div class="page-values-wrapper">
                        <div class="card-wrapper-component">
                            <div class="card-component">
                                <div class="card-cell-wrapper">
                                    <div class="column-title">
                                        <div class="column-icon">
                                            <span><i class='bx bxs-circle'></i></span>
                                            Sprint
                                        </div>
                                    </div>
                                    <div class="card-cell-wrapper-component">
                                        <div class="cell-wrapper">
                                            <li>
                                                <span id="span_sprint_title_id"></span>
                                            </li>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-cell-wrapper">
                                    <div class="column-title">
                                        <div class="column-icon">
                                            <span><i class='bx bx-text'></i></i></span>
                                            Name
                                        </div>
                                    </div>
                                    <div class="card-cell-wrapper-component">
                                        <div class="cell-wrapper">
                                            <div class="item-content">
                                                <span class="span_task_content_id"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-cell-wrapper">
                                    <div class="column-title">
                                        <div class="column-icon">
                                            <span><i class='bx bx-user-circle'></i></span>
                                            Person
                                        </div>
                                    </div>
                                    <div class="card-cell-wrapper-component">
                                        <div class="cell-wrapper">
                                            <div class="person-ava">
                                                <img id="img_person_id" alt="Avatar">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-cell-wrapper">
                                    <div class="column-title">
                                        <div class="column-icon">
                                            <span><i class='bx bx-menu'></i></span>
                                            Status
                                        </div>
                                    </div>
                                    <div class="card-cell-wrapper-component">
                                        <div class="cell-wrapper">
                                            <div class="status" style="background-color: #5cc976;">
                                                <span id="span_status_id"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-cell-wrapper">
                                    <div class="column-title">
                                        <div class="column-icon">
                                            <span><i class='bx bx-calendar'></i></span>
                                            Date
                                        </div>
                                    </div>
                                    <div class="card-cell-wrapper-component">
                                        <div class="cell-wrapper">
                                            <div class="date">
                                                <span id="span_date_id"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-seperator"></div>
                <div class="comment_container">
                    <div class="flexible-header">
                        <div class="item-board-subsets-tabs-wrapper">
                            <div class="title-content">
                                <span>Comment</span>
                            </div>
                        </div>
                    </div>
                    <div class="comment_content_wrapper">
                        <div class="comment_content">
                            <div class="new_post_wrapper">
                                <button class="add-comment-button" style="display: block;" id="btnAddcommentMywork" onclick="addCommentMyWork();">Write a comment...</button>
                                <div class="add-comment-content" style="display: none;" id="showAddComment1">
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
</div>

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

<div class="modal fade" id="filtermodalmywork" tabindex="-1" aria-labelledby="filtermodalmywork" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter in My work</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <h6 class="ml-3">All columns</h6>
                    <div class="row ml-1">
                        <!-- <div class="clear-data">
                                <button></button>
                            </div> -->
                        <div class="col-md-3 list filter-sprint">
                            <label>Project</label>
                            <div class="filter-list">
                                <ul>
                                    @if (count($projectFilters) > 0)
                                        @foreach ($projectFilters as $project)
                                        <li id="project_{{$project['id']}}" onclick="myFunction('project',`{{$project['id']}}`)">{{$project['name']}}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 list filter-sprint">
                            <label>Sprint</label>
                            <div class="filter-list">
                                <ul>
                                    @if (count($sprintFilters) > 0)
                                    @foreach ($sprintFilters as $sprint)
                                    <li id="sprint_{{$sprint['id']}}" onclick="myFunction('sprint',`{{$sprint['id']}}`)">{{$sprint['title']}}</li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 list filter-task">
                            <label>Task</label>
                            <div class="filter-list">
                                <ul>
                                    @if (count($taskFilters) > 0)
                                    @foreach ($taskFilters as $task)
                                    <li id="task_{{$task['id']}}" onclick="myFunction('task',`{{$task['id']}}`)">{{$task['content']}}</li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2 list filter-status">
                            <label>Status</label>
                            <div class="filter-list">
                                <ul>
                                    @foreach ($statusFilters as $status)
                                    <li id="status_{{$status}}" onclick="myFunction('status','{{$status}}')">{{$status}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="clearFilterModal();">Cancel</button>
                <button type="submit" class="btn btn-primary" onclick="myFunctionFilter()">Filter</button>
            </div>
        </div>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    var filter = {
        'status': [],
        'sprint': [],
        'task': [],
        'project': [],
    };

    function clearFilterModal() {
        const keys = ['status', 'sprint', 'task', 'project'];

        for (let i = 0; i < keys.length; i++) {
            for (let j = 0; j < filter[keys[i]].length; j++) {
                document.getElementById(`${keys[i]}_${filter[keys[i]][j].value}`).style.backgroundColor = "#f5f6f8";
                document.getElementById(`${keys[i]}_${filter[keys[i]][j].value}`).style.color = "black";
            }
        }

        filter['status'] = [];
        filter['sprint'] = [];
        filter['task'] = [];
        filter['project'] = [];
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
                action: "{{route('mywork')}}",
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

    function addCommentMyWork() {
        const btnAddcommentMywork = document.getElementById("btnAddcommentMywork");
        const showAddComment1 = document.getElementById("showAddComment1");
        btnAddcommentMywork.style.display = "none";
        showAddComment1.style.display = "block";
    }

    function cancelAddComment() {
        jQuery('#comment_textarea').val('');
        const btnAddcomment2 = document.getElementById("btnAddcommentMywork");
        const showAddcomment2 = document.getElementById("showAddComment1");
        btnAddcomment2.style.display = "block";
        showAddcomment2.style.display = "none";
    }

    function menuCommentAction(id) {
        const actionMenu = document.querySelector(`div#comment_menu_${id}`);
        actionMenu.classList.toggle('active');
    }

    function removeCommentActive() {
        document.querySelector('.menu-comment-action').classList.remove('active');
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

    // Open list
    function openListItem(id, idDiv, btn) {
        const element = jQuery(`div#${id}`);
        const count = parseInt(element.attr('count'));

        const display = jQuery(`div#${id}`).css('display');
        if (display === 'block') {
            jQuery(`button#${btn}`).empty();
            jQuery(`button#${btn}`).append(`<i class='bx bx-chevron-right'></i>`);
            jQuery(`div#${id}`).css('display', 'none');
        } else {
            jQuery(`div#${id}`).css('display', 'block');
            jQuery(`button#${btn}`).empty();
            jQuery(`button#${btn}`).append(`<i class='bx bx-chevron-down'></i>`);
            if (count > 10) {
                jQuery(`div#${idDiv}`).height(360);
            } else if (count <= 10) {
                // <i class='bx bx-chevron-right'></i>
                jQuery(`div#${idDiv}`).height(count * 36);
            }
        }
    }

    function submitChangeItemContent(event, element) {
        if (event.keyCode === 13) {
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
                    value: element.getAttribute('task_id')
                })
            );
            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'type',
                    value: 'content'
                })
            );

            form.append(
                $("<input>", {
                    type: 'hidde',
                    name: 'item_content',
                    value: event.target.value
                })
            );

            jQuery('body').append(form);

            jQuery('#form_update_task').submit();
        } else if (event.keyCode === 27) {
            element.value = element.getAttribute('current_value');
        }
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

    // Delete Item
    function setModalDeleteItem(element) {
        const task_id = element.getAttribute('task_id');
        jQuery('input#item_delete_input').val(task_id);
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
                                <div class="menu-comment-action" style="margin-top: 5px;" id="comment_menu_${comment.id}" style="top: ${(75 + index * 160)}px;">
                                    <ul>
                                        <li onclick="editComment(${comment.id});" style="cursor: pointer;">
                                            <i class='bx bx-pencil'></i>
                                            <span>Edit comment</span>
                                        </li>
                                        <li onclick="setModalDeleteComment(${comment.task_id}, ${comment.id});" style="cursor: pointer;" data-dismiss="modal" data-toggle="modal" data-target="#commentaction">
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

    // delete comment
    function setModalDeleteComment(task_id, coment_id) {
        jQuery('input#input_comment_task_id').val(task_id);
        jQuery('input#input_comment_id').val(coment_id);
    }

    // Set Modal Comment
    function setModalComment(element) {
        const task_id = element.getAttribute('task_id');
        $.ajax({
            type: 'GET',
            url: `{{route('comment.read.mywork')}}?idTask=${task_id}`,
            success: (data) => {
                console.log(data);
                const status = ['Done', 'Working on it', 'Review', 'Test'];
                const {
                    task,
                    comments
                } = data;
                const endTime = new Date(task.end_time);
                const regex = /(http)(.+)/g;
                const src = !task.user_avt ? "{{asset('images/user.jpg')}}" : (regex.test(task.user_avt) ? task.user_avt : `{{asset('storage')}}/${task.user_avt}`);
                jQuery('span.span_task_content_id').text(task.content);
                jQuery('a#a_project_name_id').text(task.project_name);
                jQuery('a#a_project_name_id').attr('href', `{{url('/project')}}/${task.project_id}`);
                jQuery('span#span_sprint_title_id').text(task.sprint_title);
                jQuery('img#img_person_id').attr('src', src);
                jQuery('span#span_status_id').text(status[task.status - 1]);
                jQuery('span#span_date_id').text([endTime.getFullYear(), endTime.getMonth() + 1, endTime.getDate()].join('-'));
                jQuery('input#comment_task_id').val(task_id);
                renderComment(comments);
            },
            error: (err) => {
                console.log(err);
            }
        })
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

    function searchItemMyWork(event, element) {
        const value = event.target.value;
        if (event.keyCode === 13) {
            console.log("onkeyup");
            window.location = `{{route('mywork')}}?item_search=${value}`;
        } else if (!value) {
            console.log("change");
            window.location = "{{route('mywork')}}";
        }
    }
</script>