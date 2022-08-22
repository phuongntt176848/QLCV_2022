@extends('team.teammenu', ['count_user' => $count_user, 'users' => $users, 'teamOfMes' => $teamOfMes, 'idTeam' => $idTeam])
<div class="team-current-view-wapper">
    <div class="team-current-view-container">
        <div class="all-users-header">
            <div class="all-users-title-wrapper">
                <h2>All users</h2>
                <div class="all-users-action">
                    <button type="button" class="add-new-member-button" data-toggle="modal" data-target="#inviteusers">
                        <i class='bx bx-user-plus'></i>
                        Invite
                    </button>
                </div>
            </div>
            <div class="all-users-search">
                <form action="{{route('teams.search.all')}}" method="get" id="team_form_search_all">
                    <div class="teams-search-user-container">
                        <input type="search" id="team_search_all_input" name="email" placeholder="Search by email" oninput="fetchAll(this.value);" onkeydown="submitSearch(this.value);" value="{{isset($_GET['email']) ? $_GET['email'] : ''}}">
                        <i class='bx bx-search'></i>
                    </div>
                </form>
            </div>
        </div>
        <div class="team-members-table-component">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 30%">Name</th>
                        <th style="width: 30%">Email</th>
                        <th style="width: 20%">Tile</th>
                        <th style="width: 40%">Team</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($count_user > 0)
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="{{auth()->user()->id !== $user->id ? route('other_profile', ['id' => $user->id]) : route('my-profile')}}"><img src="{{!$user->avatar ? asset('images/user.jpg') : (preg_match('/(http)(.+)/', $user->avatar) ? $user->avatar : asset('storage/'.$user->avatar))}}"></a>
                            <a href="{{auth()->user()->id !== $user->id ? route('other_profile', ['id' => $user->id]) : route('my-profile')}}">{{$user->name}}</a>
                        </td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->title}}</td>
                        <td>
                            @if (!empty($teamOfOthers[$user->id]))
                            <div class="list-team-user">
                                @foreach ($teamOfOthers[$user->id] as $key => $team)
                                @if ($key == 5) @break
                                @endif
                                <div class="team-user">
                                    <a href="#" data-toggle="tooltip" title="{{$team['team_name']}}" data-placement="top">
                                        <img src="{{asset('storage/'.$team['img'])}}">
                                    </a>
                                </div>
                                @endforeach
                                @if (count($teamOfOthers[$user->id]) > 5)
                                <div class="hidden-team">
                                    + {{count($teamOfOthers[$user->id]) - 5}}
                                </div>
                                @endif
                            </div>
                            @else
                            <p>No teams</p>
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


<script>
    function submitSearch(event, value) {
        event.preventDefault();
        if (event.keyCode === 13 && value != '') {
            document.getElementById('team_form_search_all').submit();
        }
    }

    function fetchAll(value) {
        if (!value) {
            const a = document.createElement("a");
            document.body.appendChild(a);
            a.setAttribute('href', "{{route('my-team')}}");
            a.click();
        }
    }

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>