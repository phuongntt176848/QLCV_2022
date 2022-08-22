<div class="sidebar">
    <div class="logo">
        <img src="{{asset('images/task.png')}}" alt="logo">
    </div>
    <div class="nav">
        <div class="nav_list1">
            <ul class="nav_list">
                <li>
                    <a href="{{route('menuproject')}}" id="btn">
                        <i class="bx bx-grid-alt"></i>
                    </a>
                    <span class="tooltip">My Project</span>
                </li>
                <li>
                    <a href="{{route('mywork')}}">
                        <i class="bx bx-calendar-check"></i>
                    </a>
                    <span class="tooltip">My word</span>
                </li>
                <li>
                    <a href="{{route('my-team')}}">
                        <i class="bx bx-group"></i>
                    </a>
                    <span class="tooltip">Team</span>
                </li>
            </ul>
        </div>
        <div class="nav_list2">
            <ul class="nav_list">
                <li>
                    <button type="button" data-toggle="modal" data-target="#inviteusers">
                        <i class='bx bx-user-plus'></i>
                    </button>
                    <span class="tooltip">Invite Users</span>
                </li>
                <li>
                    <a href="#">
                        <i class="bx bx-search-alt-2"></i>
                    </a>
                    <span class="tooltip">Search</span>
                </li>
                <li>
                    <a href="#">
                        <i class="bx bx-bell"></i>
                    </a>
                    <span class="tooltip">Notification</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="profile-user" onclick="menuToggle();">
        <i class="bx bxs-user"></i>
    </div>
    <div class="menu-user">
        <h3>Hi,Phuong<br><span>Website Disigner</span></h3>
        <ul>
            <li><i class="bx bxs-user"></i><a href="{{route('my-profile')}}">My Profile</a></li>
            <li><i class="bx bx-group"></i><a href="{{route('my-team')}}">My Team</a></li>
            <li><i class="bx bx-log-out"></i><a href="{{route('logout')}}">Log out</a></li>
        </ul>
    </div>
</div>
</div>