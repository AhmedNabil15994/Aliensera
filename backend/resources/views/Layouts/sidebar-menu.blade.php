  <div class="col-md-3 left_col">
    <div class="scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ URL::to('/') }}" class="site_title"><i class="fa fa-book"></i> <span>AlienSera !</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ asset('assets/images/avatar.png') }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ FULL_NAME }} <i class="label bg-green online">{{ trans('dashboard.online') }}</i></h2>
                <span>{{ GROUP_NAME }}</span>
            </div>
        </div>
        <!-- /menu profile quick info -->
        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li><a href="{{ URL::to('/') }}"><i class="fa fa-home"></i> Dashboard</a></li>

                    @if(\Helper::checkRules('list-users,list-groups'))
                    <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-users'))
                                <li><a href="{{ URL::to('/users') }}">Users</a></li>
                            @endif
                            @if(\Helper::checkRules('list-groups'))
                                <li><a href="{{ URL::to('/groups') }}">Groups</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-courses,list-lessons'))
                    <li><a><i class="fa fa-certificate"></i> Courses<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-courses'))
                                <li><a href="{{ URL::to('/courses') }}">Courses {!! App\Models\Course::getCount() ? "<span class='badge bg-green'>".App\Models\Course::getCount()."</span>" :'' !!}</a></li>
                            @endif
                            @if(\Helper::checkRules('list-lessons'))
                                <li><a href="{{ URL::to('/lessons') }}">Lessons {!! App\Models\Lesson::getCount() ? "<span class='badge bg-green'>".App\Models\Lesson::getCount()."</span>" : '' !!}</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-universities,list-faculties'))
                    <li><a><i class="fa fa-building"></i> University & Faculty<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-universities'))
                                <li><a href="{{ URL::to('/universities') }}">University </a></li>
                            @endif

                            @if(\Helper::checkRules('list-faculties'))
                                <li><a href="{{ URL::to('/faculties') }}">Faculty </a></li>
                            @endif

                            @if(\Helper::checkRules('list-fields'))
                                <li><a href="{{ URL::to('/fields') }}">Fields </a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-variables'))
                    <li><a><i class="fa fa-cogs"></i> Settings <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-variables'))
                                <li><a href="{{ URL::to('/variables') }}">Variables</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings" href="{{ IS_ADMIN == true ?  URL::to('/variables') : '#' }}">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Profile" href="#">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Courses" href="{{ URL::to('/courses') }}">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ URL::to('/logout') }}">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
