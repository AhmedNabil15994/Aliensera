  <div class="col-md-3 left_col">
    <div class="scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ URL::to('/') }}" class="site_title"><i class="fa fa-book"></i> <span>AlienSera !</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ App\Models\User::getData(App\Models\User::getOne(USER_ID))->image }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ ucwords(FULL_NAME) }} <i class="label bg-green online">{{ trans('dashboard.online') }}</i></h2>
                <span>{{ ucwords(GROUP_NAME) }}</span>
            </div>
        </div>
        <!-- /menu profile quick info -->
        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li class="{{ Active(URL::to('/')) }}"><a href="{{ URL::to('/') }}"><i class="fa fa-home"></i> Home</a></li>

                    @if(\Helper::checkRules('list-courses,list-lessons'))
                    <li class="{{ Active(URL::to('/users*')) }} {{ Active(URL::to('/courses*')) }} {{ Active(URL::to('/lessons*')) }}"><a><i class="fa fa-users"></i> Control <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-users'))
                                <li class="{{ Active(URL::to('/users*')) }}"><a href="{{ URL::to('/users') }}">Users</a></li>
                            @endif
                            @if(\Helper::checkRules('list-courses'))
                                <li class="{{ Active(URL::to('/courses*')) }}"><a href="{{ URL::to('/courses') }}">Courses {!! App\Models\Course::getCount() && IS_ADMIN ? "<span class='badge bg-green'>".App\Models\Course::getCount()."</span>" :'' !!}</a></li>
                            @endif
                            @if(\Helper::checkRules('list-lessons'))
                                <li class="{{ Active(URL::to('/lessons*')) }}"><a href="{{ URL::to('/lessons') }}">Lessons {!! App\Models\Lesson::getCount() ? "<span class='badge bg-green'>".App\Models\Lesson::getCount()."</span>" : '' !!}</a></li>
                            @endif
                            @if(\Helper::checkRules('list-quizes'))
                                <li class="{{ Active(URL::to('/quizes*')) }}"><a href="{{ URL::to('/quizes') }}">Independent Quizes </a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <li class="{{ Active(URL::to('/notifications*')) }}"><a><i class="fa fa-certificate"></i> Engage<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li class="{{ Active(URL::to('/messages*')) }}"><a href="{{ URL::to('/messages') }}">Messages</a></li>
                            <li class="{{ Active(URL::to('/comments*')) }}"><a href="{{ URL::to('/comments') }}">Comments</a></li>
                            <li class="{{ Active(URL::to('/notifications*')) }}"><a href="{{ URL::to('/notifications') }}">Notifications</a></li>
                        </ul>
                    </li>  

                    @if(\Helper::checkRules('list-student-requests') && IS_ADMIN)
                    <li class="{{ Active(URL::to('/requests*')) }}"><a><i class="fa fa-info-circle"></i> Manage Requests<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(IS_ADMIN)
                            <li class="{{ Active(URL::to('/upgrade*')) }}"><a href="{{ URL::to('/upgrade') }}">Upgrade Requests {!! App\Models\Course::getCount2() && IS_ADMIN ? "<span class='badge bg-green'>".App\Models\Course::getCount2()."</span>" :'' !!}</a></li>
                            @endif
                            @if(\Helper::checkRules('list-student-requests'))
                                <li class="{{ Active(URL::to('/requests*')) }}"><a href="{{ URL::to('/requests') }}">Student Requests {!! App\Models\StudentRequest::getCount() ? "<span class='badge bg-green'>".App\Models\StudentRequest::getCount()."</span>" :'' !!}</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-variables,list-pages,list-groups,list-universities,list-faculties,list-fields'))
                    <li class="{{ Active(URL::to('/variables*')) }} {{ Active(URL::to('/pages*')) }} {{ Active(URL::to('/list-groups*')) }} {{ Active(URL::to('/list-universities*')) }} {{ Active(URL::to('/list-faculties*')) }} {{ Active(URL::to('/list-fields*')) }}"><a><i class="fa fa-cogs"></i> Settings <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-universities'))
                                <li class="{{ Active(URL::to('/universities*')) }}"><a href="{{ URL::to('/universities') }}">University </a></li>
                            @endif

                            @if(\Helper::checkRules('list-faculties'))
                                <li class="{{ Active(URL::to('/faculties*')) }}"><a href="{{ URL::to('/faculties') }}">Faculty </a></li>
                            @endif

                            @if(\Helper::checkRules('list-fields'))
                                <li class="{{ Active(URL::to('/fields*')) }}"><a href="{{ URL::to('/fields') }}">Fields </a></li>
                            @endif

                            @if(\Helper::checkRules('list-groups'))
                                <li class="{{ Active(URL::to('/groups*')) }}"><a href="{{ URL::to('/groups') }}">User Groups</a></li>
                            @endif
                            
                            @if(\Helper::checkRules('list-variables'))
                                <li class="{{ Active(URL::to('/variables*')) }}"><a href="{{ URL::to('/variables') }}">Variables</a></li>
                            @endif

                            @if(\Helper::checkRules('list-pages'))
                                <li class="{{ Active(URL::to('/pages*')) }}"><a href="{{ URL::to('/pages') }}">Pages</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(\Helper::checkRules('list-course-students') && !IS_ADMIN)
                    <li class="{{ Active(URL::to('/courseStudents')) }} {{ Active(URL::to('/users/view*')) }} {{ Active(URL::to('/requests*')) }} {{ Active(URL::to('/coures/view*')) }}"><a><i class="fa fa-info-circle"></i>Students<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(\Helper::checkRules('list-course-students'))
                                <li class="{{ Active(URL::to('/courseStudents')) }} {{ Active(URL::to('/users/view*')) }}"><a href="{{ URL::to('/courseStudents') }}">Students Courses</a></li>
                            @endif
                            @if(\Helper::checkRules('list-student-requests'))
                                <li class="{{ Active(URL::to('/requests*')) }}"><a href="{{ URL::to('/requests') }}">Student Requests {!! App\Models\StudentRequest::getCount() ? "<span class='badge bg-green'>".App\Models\StudentRequest::getCount()."</span>" :'' !!}</a></li>
                            @endif
                            @if(\Helper::checkRules('list-quiz-scores'))
                                <li class="{{ Active(URL::to('/scores*')) }}"><a href="{{ URL::to('/scores') }}">Quizzes Scores</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

       {{--  <!-- /menu footer buttons -->
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
        <!-- /menu footer buttons --> --}}
    </div>
</div>
