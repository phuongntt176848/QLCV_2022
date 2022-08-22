@extends('team.teammenu', ['count_user' => $count_user, 'users' => $users, 'teamOfMes' => $teamOfMes, 'idTeam' => $currentTeam['id']])
<div class="team-current-view-wapper">
    <div class="current-team-component">
        <div class="current-team-header-wrapper">
            <div class="current-team-header-component">
                <div class="current-team-cover-component" style="background-repeat: no-repeat;background-size:cover; background-image: url({{asset('storage/'.$currentTeam['bg_img'])}});">
                    <form action="{{route('team.update.bg_team', ['id' => $currentTeam['id']])}}" method="post" enctype="multipart/form-data" id="form_change_team_background">
                        @csrf
                        @method('PUT')
                        <div class="add-cover-button-wrapper">
                            <input type="file" id="file-cover" name="team_background" accept="image/png, image/jpeg" onchange="submitChangeTeamBackground()" />
                            <label for="file-cover" id="img-cover">
                                + Add cover
                            </label>
                        </div>
                    </form>
                </div>
                <div class="team-header-wrapper">
                    <div class="team-avatar-and-title">
                        <div class="team-avatar-container">
                            <img src="{{asset('storage/'.$currentTeam['img'])}}">
                            <div class="team-avatar-replacement-container">
                                <div class="team-avatar-replacement">
                                    <label for="input_file_team_avatar" style="margin-bottom: 0; cursor: pointer;" class="team-avatar-replacement-icon">
                                        <i class='bx bx-pencil'></i>
                                    </label>
                                    <label for="input_file_team_avatar" style="cursor: pointer;" class="team-avatar-replacement-text">Change</label>
                                    <form action="{{route('team.update.avatar', ['id' => $currentTeam['id']])}}" method="post" enctype="multipart/form-data" id="form_change_team_avatar">
                                        @csrf
                                        @method('PUT')
                                        <input type="file" name="team_avatar" id="input_file_team_avatar" class="team-avatar-replacement-file-input" onchange="submitChangeTeamAvatar()" accept="image/png, image/jpeg">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="team-title-wrapper">
                            <div class="team-name-editable-heading">
                                <h2>{{$currentTeam['team_name']}}</h2>
                                <button type="button" class="change-name-team-button" data-toggle="modal" data-target="#changenameteammmodal">
                                    <i class='bx bx-pencil'></i>
                                </button>
                            </div>
                            <h6>{{$currentTeam['count_member']}} member</h6>
                        </div>
                    </div>
                    <div class="team-header-actions">
                        <button class="add-team-member-button" type="button" data-toggle="modal" data-target="#addteammembermodal">
                            + Add team members
                        </button>
                        <button class="team-actions-menu-button" onclick="menuAction();">
                            <i class='bx bx-dots-horizontal-rounded'></i>
                        </button>
                        <div class="menu-action" data-toggle="modal" data-target="#deleteteam">
                            <ul>
                                <li onclick="menuAction();" style="cursor: pointer;">
                                    <i class='bx bx-trash'></i>
                                    <span onmouseout="removeActive();">Delete team</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="current-team-filters-wrapper">
            <div class="all-users-search">
                <form action="{{route('teams.search', ['id'=>$currentTeam['id']])}}" method="get" id="team_form_search">
                    <div class="teams-search-user-container">
                        <input type="search" id="team_search_input" name="search" placeholder="Search by name or email" oninput="fetchAll(this.value);" onkeydown="submitSearch(this.value);" value="{{isset($_GET['search']) ? $_GET['search'] : ''}}">
                        <i class='bx bx-search'></i>
                    </div>
                </form>
            </div>
        </div>
        <div class="team-members-table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 30%">Name</th>
                        <th style="width: 30%">Email</th>
                        <th style="width: 20%">Tile</th>
                        <th style="width: 40%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($currentTeam['members']))
                    @foreach($currentTeam['members'] as $member)
                    <tr>
                        <td>
                            <a href="{{auth()->user()->id !== $member['id'] ? route('other_profile', ['id' => $member['id']]) : route('my-profile')}}">
                                <img src="{{!$member['avatar']? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $member['avatar']) ? $member['avatar'] : asset('storage/'.$member['avatar']))}}">
                            </a>
                            <!-- <i class='bx bxs-group'></i> -->
                            <a href="#">{{$member['name']}}</a>
                        </td>
                        <td>{{$member['email']}}</td>
                        <td>{{$member['title']}}</td>
                        <td>
                            @if (auth()->user()->id == $member['id'])
                            <button type="button" class="btn btn-danger" data-toggle="modal" disabled data-target="#deleteteamuser" onclick="setModalDeleteTeam('{{$member['name']}}', {{$member['id']}});"><i class='bx bx-trash'></i></button>
                            @else
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteteamuser" onclick="setModalDeleteTeam('{{$member['name']}}', {{$member['id']}});"><i class='bx bx-trash'></i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal change name team-->
<div class="modal fade" id="changenameteammmodal" tabindex="-1" role="dialog" aria-labelledby="changenameteammmodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change team's name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('team.update.name', ['id' => $currentTeam['id']])}}" method="POST" id="form_change_team_name">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="input_team_name">
                            Team's name
                        </label>
                        <input type="text" class="form-control" id="input_team_name" name="team_name" placeholder="Enter name" value="{{$currentTeam['team_name']}}">
                        <span id="span_err_team_name" style="display: none; color: red;">Tên không được để trống</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitChangeTeamName();">Change</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal add member team-->
<div class="modal fade" id="addteammembermodal" tabindex="-1" role="dialog" aria-labelledby="addteammembermodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add team members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('team.add.member', ['id'=>$currentTeam['id']])}}" method="POST" id="team_form_add_member">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <select id="disabledSelect" class="form-control" name="member">
                            <option value="">Enter name</option>
                            @if ($count_user > 0)
                            @foreach ($users as $user)
                            @auth
                            @if (!in_array($user->id, $currentTeam['memberIds']))
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endif
                            @endauth
                            @endforeach
                            @endif
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitFormAddMember();">Add</button>
            </div>
        </div>
    </div>
</div>
<!-- modal delete team -->
<div class="modal fade" id="deleteteam" tabindex="-1" aria-labelledby="deleteteamLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="deleteteamLabel">Delete team {{$currentTeam['team_name']}}?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="">Cancel</button>
                <form action="{{route('team.delete.team', ['id' => $currentTeam['id']])}}" id="team_delete_team" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal delete user team -->
<div class="modal fade" id="deleteteamuser" tabindex="-1" aria-labelledby="deleteteamuserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="deleteteamUserLabel"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{route('team.delete.member', ['id' => $currentTeam['id']])}}" id="team_delete_team_member" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="member" id="team_input_id_member">
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setModalDeleteTeam(name, idUser) {
        document.getElementById('deleteteamUserLabel').innerText = `Remove ${name} from team ?`;
        document.getElementById('team_input_id_member').value = idUser;
    }

    function submitSearch(event, value) {
        event.preventDefault();
        if (event.keyCode === 13 && value != '') {
            document.getElementById('team_form_search').submit();
        }
    }

    function menuAction() {
        const actionMenu = document.querySelector('.menu-action');
        actionMenu.classList.toggle('active')
    }

    function removeActive() {
        document.querySelector('.menu-action').classList.remove('active');
    }

    function submitChangeTeamAvatar() {
        document.getElementById('form_change_team_avatar').submit();
    }

    function submitChangeTeamBackground() {
        document.getElementById('form_change_team_background').submit();
    }

    function submitChangeTeamName() {
        const input = document.getElementById('input_team_name').value;
        if (input) {
            document.getElementById('form_change_team_name').submit();
        } else {
            document.getElementById('span_err_team_name').style.display = 'block';
        }
    }

    function submitFormAddMember() {
        document.getElementById('team_form_add_member').submit();
    }

    function fetchAll(value) {
        console.log(value);
        if (!value) {
            const a = document.createElement("a");
            document.body.appendChild(a);
            a.setAttribute('href', "{{route('team.show', ['id'=>$currentTeam['id']])}}");
            a.click();
        }
    }
</script>