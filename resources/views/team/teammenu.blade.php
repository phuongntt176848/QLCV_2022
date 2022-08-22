@extends('layout.master')
<div class="menu-team">
    <div class="team-list-header">
        <h2>Teams</h2>
        <button type="button" class="add-new-team-button" data-toggle="modal" data-target="#createteammodal" id="modal_new_team">
            <i class='bx bx-plus'></i>
            New Team
        </button>

        <!-- Modal -->
        <div class="modal fade" id="createteammodal" tabindex="-1" role="dialog" aria-labelledby="createteammodal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create team</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('teams.create')}}" method="POST" enctype="multipart/form-data" id="form_team_create">
                            @csrf
                            <div class="form-group">
                                <label for="formGroupExampleInput">Team's name</label>
                                <input id="form_create_name" type="text" class="form-control" name="team_name" id="formGroupExampleInput" placeholder="Enter name" value="{{old('team_name')}}">
                                <span style="display: none; color: red;" id="name_err">Enter Team's name</span>
                            </div>
                            <div class="form-group">
                                <label for="disabledSelect">Team members</label>
                                <select id="disabledSelect" class="form-control" name="member">
                                    <option value="">Enter name</option>
                                    @if ($count_user > 0)
                                    @foreach ($users as $user)
                                    @auth
                                    @if ($user->id != auth()->user()->id)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endif
                                    @endauth
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <input type="file" id="file-ava" name="image" accept="image/png, image/jpeg" />
                            <label for="file-ava" id="img-ava">
                                <i class='bx bx-upload'></i>
                                Upload team's avatar image
                            </label>
                            <span style="display: none; color: red;" id="image_err">Hãy nhập ảnh team</span>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="handlerSubmit">Create</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
    <form action="{{route('teams.search.team')}}" method="get" id="team_form_search_team">
        <div class="teams-list-search-container">
            <i class='bx bx-search'></i>
            <input type="text" name="name" placeholder="Search teams" oninput="fetchAll(this.value);" onkeydown="submitSearch(this.value);" value="{{isset($_GET['name']) ? $_GET['name'] : ''}}">
        </div>
    </form>
    <hr>
    <div class="teams-list-wrapper">
        <ul>
            @if (!isset($_GET['name']))
            <li>
                <a style="text-decoration: none;" href="{{route('my-team')}}">
                    @if ($idTeam == 0)
                    <div class="teams-all-user-item active">
                        @else
                        <div class="teams-all-user-item">
                            @endif
                            <div class="all-user-teams-list-item">
                                <div class="team-image-and-name">
                                    <i class='bx bx-group'></i>
                                    <span>All users</span>
                                </div>
                                <span class="team-subscribers-count">
                                    <div class="count-user-team">
                                        <span id="counter">{{ ucfirst($count_user ?? '') }}</span>
                                    </div>
                                </span>
                            </div>
                        </div>
                </a>
            </li>
            @endif
            @if (count($teamOfMes) > 0)
            @foreach($teamOfMes as $team)
            <li>
                <a style="text-decoration: none;" href="{{route('team.show', ['id' => $team['id']])}}">
                    @if ($idTeam == $team['id'])
                    <div class="teams-all-user-item active">
                        @else
                        <div class="teams-all-user-item">
                            @endif
                            <div class="all-user-teams-list-item">
                                <div class="team-image-and-name">
                                    <img src="{{asset('storage/'.$team['img'])}}">
                                    <!-- <i class='bx bxs-group'></i> -->
                                    <span>{{$team['team_name']}}</span>
                                </div>
                                <span class="team-subscribers-count">
                                    <div class="count-user-team">
                                        <span>{{$team['count_member']}}</span>
                                    </div>
                                </span>
                            </div>
                        </div>
                </a>
            </li>
            @endforeach
            @else
            @if (isset($_GET['name']))
            <li>No results found for '{{$_GET['name']}}'</li>
            @endif
            @endif
        </ul>
    </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    function fetchAll(value) {
        if (!value) {
            const a = document.createElement("a");
            document.body.appendChild(a);
            a.setAttribute('href', "{{route('my-team')}}");
            a.click();
        }
    }

    function submitSearch(event, value) {
        event.preventDefault();
        if (event.keyCode === 13 && value != '') {
            document.getElementById('team_form_search_team').submit();
        }
    }

    jQuery(document).ready(function($) {
        $(".teams-all-user-item").click(function() {

            // Select all list items
            var listItems = $(".teams-all-user-item");

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