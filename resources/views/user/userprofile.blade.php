@extends('layout.master')
<div class="user-profile">
    <div class="user-profile-top">
        <div class="ava">
            <div class="profile-pic-div">
                @if ($user['avatar'])
                <img src="{{$isStorage == 1 ? $user['avatar'] : asset('storage/'.$user['avatar'])}}" id="photo">
                @elseif (!$user['avatar'])
                <img src="{{asset('images/user.jpg')}}" id="photo">
                @endif
                @if (!isset($other))
                <form action="{{route('edit_avatar', ['id' => $user['id']])}}" method="post" enctype="multipart/form-data" id="profile_change_avatar">
                    @csrf
                    @method('PUT')
                    <input type="file" id="file" name="avatar" accept="image/png, image/jpeg" onchange="submitChangeAvatar()">
                    <label for="file" id="uploadBtn">
                        <i class='bx bx-camera'></i>
                    </label>
                </form>
                @endif
            </div>
        </div>

        <div class="user-name">
            <h1>{{$user['name']}}</h1>
            @if (!isset($other))
            <button type="button" class="change-name-user-button" data-toggle="modal" data-target="#changenameusermmodal">
                <i class='bx bx-pencil'></i>
            </button>
            @endif
        </div>
        <div class="tab-header">
            <div class="tab-indicator active" onclick="opentab('tab1', this)">
                Infor User
            </div>
            @if (!isset($other))
            <div class="tab-indicator" onclick="opentab('tab2', this)">
                Password
            </div>
            @endif
        </div>
    </div>
    <div class="user-profile-content">
        <!-- user infor -->
        <div class="tab-body" id="tab1">
            <div class="tab-body-header">
                <h2>Over view</h2>
                @if (!isset($other))
                <button type="button" data-toggle="modal" data-target="#editprofilemmodal">
                    <i class='bx bx-edit'>Edit</i>
                </button>
                @endif
            </div>
            <ul>
                <li>
                    <div class="user-icon">
                        <i class='bx bx-user'></i>
                    </div>
                    <a href="#">Title: {{$user['title']}}</a>
                </li>
                <li>
                    <div class="user-icon">
                        <i class='bx bx-envelope'></i>
                    </div>
                    <a href="#">Email: {{$user['email']}}</a>
                </li>
                <li>
                    <div class="user-icon">
                        <i class='bx bx-phone'></i>
                    </div>
                    <a href="#">Phone: {{$user['phone']}}</a>
                </li>
                <li>
                    <div class="user-icon">
                        <i class='bx bx-map'></i>
                    </div>
                    <a href="#">Location: {{$user['location']}}</a>
                </li>

                <li>
                    <div class="user-icon">
                        <i class='bx bx-gift'></i>
                    </div>
                    <a href="#">Birthday: {{$user['brithday']}}</a>
                </li>
            </ul>
            <hr />
            <h2>Team</h2>
            <ul>
                @if (isset($teams))
                @foreach($teams as $team)
                <li>
                    <div class="team-icon">
                        <i class='bx bxs-circle'></i>
                    </div>
                    <a href="#">{{$team->name}}</a>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
        <div class="tab-body" id="tab2" style="display:none">
            <h2>Change password</h2>
            <form action="{{route('change.password')}}" class="change-password-form" autocomplete="off" method="POST">
                @csrf
                @method('PUT')
                @if (auth()->user()->password)
                <div class="input-form">
                    <label for="name" class="change-password-label">Current password</label>
                    <input type="password" id="password" class="change-password-input" placeholder="Current password" name="current_password">
                </div>
                @endif
                <div class="input-form">
                    <label for="name" class="change-password-label">New password</label>
                    <input type="password" id="password" class="change-password-input" placeholder="New password" name="new_password" required>
                </div>
                <div class="input-form">
                    <label for="name" class="change-password-label">Confirm new password</label>
                    <input type="password" id="password" class="change-password-input" placeholder="Confirm password" name="new_confirm_password" required>
                </div>
                <hr />
                <button type="submit" class="change-password-submit">Save</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal change name user-->
<div class="modal fade" id="changenameusermmodal" tabindex="-1" role="dialog" aria-labelledby="changenameusermmodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('edit_name', ['id' => $user['id']])}}" method="POST" id="form_edit_name">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="formGroupExampleInput">
                            User name
                        </label>
                        <input type="text" class="form-control" id="formGroupExampleInput" name="user_name" placeholder="Enter name" value="{{$user['name']}}">
                        <span style="display: none; color: red;" id="span_err_name">Tên không được để trống</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitEditName()">Change</button>
            </div>
        </div>
    </div>
</div>

@if ($errors->any())
<p style="color: red;">{{$errors}}</p>
@endif

<!-- Modal edit profile user-->
<div class="modal fade" id="editprofilemmodal" tabindex="-1" role="dialog" aria-labelledby="editprofilemmodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('update_profile')}}" method="POST" id="profile_form_edit">
                    @csrf
                    @method('PUT')
                    <span style="display: none; color: red;" id="profile_err_edit">Hãy nhập đầy đủ thông tin</span>
                    <div class="form-group">
                        <label for="profile_title_edit">
                            Title
                        </label>
                        <input type="text" class="form-control" id="profile_title_edit" placeholder="Enter title" name="user_title" value="{{$user['title']}}">
                    </div>
                    <div class="form-group">
                        <label for="profile_phone_edit">
                            Phone
                        </label>
                        <input type="email" class="form-control" id="profile_phone_edit" placeholder="Enter title" name="user_phone" value="{{$user['phone']}}">
                    </div>
                    <div class="form-group">
                        <label for="profile_location_edit">
                            Location
                        </label>
                        <input type="email" class="form-control" id="profile_location_edit" placeholder="Enter title" name="user_location" value="{{$user['location']}}">
                    </div>
                    <div class="form-group">
                        <label for="profile_birth_edit">
                            Date of birth
                        </label>
                        <input type="date" class="form-control" id="profile_birth_edit" placeholder="Enter title" name="user_brithday" value="{{$user['brithday']}}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitUpdateProfile()">Edit</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function submitEditName() {
        const form = document.getElementById('form_edit_name');
        const inputName = document.getElementById('formGroupExampleInput');
        if (inputName.value) {
            form.submit();
        } else {
            document.getElementById('span_err_name').style.display = 'block';
        }
    }

    function submitChangeAvatar() {
        document.getElementById('profile_change_avatar').submit();
    }

    function submitUpdateProfile() {
        const title = document.getElementById('profile_title_edit').value;
        const phone = document.getElementById('profile_phone_edit').value;
        const location = document.getElementById('profile_location_edit').value;
        const birth = document.getElementById('profile_birth_edit').value;

        if (title && phone && location && birth) {
            document.getElementById('profile_form_edit').submit();
        } else {
            document.getElementById('profile_err_edit').style.display = 'block';
        }
    }
</script>