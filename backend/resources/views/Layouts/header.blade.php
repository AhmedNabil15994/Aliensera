<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
    <nav>
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{ App\Models\User::getData(App\Models\User::getOne(USER_ID))->image }}" alt="">{{ ucwords(FULL_NAME) }}
                    <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="{{ URL::to('/profile') }}"><i class="fa fa-user"></i> Edit Profile</a></li>
                    <li><a href="{{ URL::to('/logout') }}"><i class="fa fa-sign-out"></i> Log Out</a></li>
                </ul>
            </li>
            {{-- <li role="presentation" class="dropdown">
                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-envelope-o"></i>
                <span class="badge bg-green">0</span>
                </a>
                <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                        <a>
                        <span>
                            <span>John Smith</span>
                            <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                            Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                        </a>
                    </li>
                    <li>
                        <div class="text-center">
                        <a>
                            <strong>See All Alerts</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                        </div>
                    </li>
                </ul>
            </li> --}}
        </ul>
    </nav>
    </div>
</div>
<!-- /top navigation -->
