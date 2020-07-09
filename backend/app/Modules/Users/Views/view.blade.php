@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->name)
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/view-user.css') }}">
@endsection
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data->data->group }}: <small>{{ $data->data->name }}</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 profile_left">
                        <div class="profile_img">
                            <div id="crop-avatar">
                                <!-- Current avatar -->
                                <img class="img-responsive avatar-view" src="{{ $data->data->image }}" alt="Avatar" title="Change the avatar">
                            </div>
                        </div>
                        <h3>{{ $data->data->name }}</h3>
                        <ul class="list-unstyled user_data">
                            @if($data->data->address != '')
                            <li><i class="fa fa-map-marker user-profile-icon"></i> {{$data->data->address}}</li>
                            @endif
                            @if($data->data->gender != '')
                            <li><i class="fa fa-user"></i> {{ $data->data->gender == 1 ? 'Male' : 'Female' }}</li>
                            @endif
                            <li class="m-top-xs"><i class="fa fa-phone"></i> {{ $data->data->phone }}</li>
                            <li class="m-top-xs"><i class="fa fa-envelope"></i> {{ $data->data->email }}</li>
                            @if($data->data->group_id == 2)
                            <li class="m-top-xs"><i class="fa fa-star"></i> ({{ $data->data->rateCount }} Review) {{ $data->data->totalRate }} of 5</li>
                            @endif
                        </ul>
                        <hr>
                        @if($data->data->group_id == 3)
                        <h4>Last Login Sessions</h4>
                        <ul class="list-unstyled user_data">                            
                            @foreach($data->sessions as $session)
                            <li><i class="fa fa-clock-o user-profile-icon"></i> {{ $session->created_at }}</li>
                            @endforeach
                        </ul>
                        @elseif($data->data->group_id == 2)
                        <div class="row last">
                            <h4>Certificate Logo:</h4> <img src="{{ $data->data->logo }}">
                        </div>
                        @endif
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                @if($data->data->group_id == 3)
                                <li role="presentation" class="active"><a href="#tab_content" role="tab" id="requests-tab" data-toggle="tab" aria-expanded="false">Requests ({{ count((array) $data->requests) }})</a></li>
                                @endif
                                <li role="presentation" class="{{ $data->data->group_id == 3 ? '' : 'active' }}"><a href="#tab_content1" role="tab" id="home-tab" data-toggle="tab" aria-expanded="false">Courses ({{ count((array) $data->courses) }})</a></li>
                                <li role="presentation" class=""><a href="#tab_content2" id="comment-tab" role="tab" data-toggle="tab" aria-expanded="true">Comments ({{ count((array) $data->comments) }})</a></li>
                                @if($data->data->group_id == 3)
                                <li role="presentation" class=""><a href="#tab_content3" role="tab" id="review-tab" data-toggle="tab" aria-expanded="false">Reviews ({{ count((array) $data->reviews) }})</a></li>
                                @endif
                                <li role="presentation" class=""><a href="#tab_content4" role="tab" id="rate-tab" data-toggle="tab" aria-expanded="false">Instructor Rates ({{ count((array) $data->rates) }})</a></li>
                                @if($data->data->group_id == 3)
                                <li role="presentation" class=""><a href="#tab_content5" role="tab" id="score-tab" data-toggle="tab" aria-expanded="false">Scores ({{ $data->scores->total->score }})</a></li>
                                @endif
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                @if($data->data->group_id == 3)
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content" aria-labelledby="requests-tab">
                                    @if(!empty((array) $data->requests))
                                    <table class="data table table-striped no-margin">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Course</th>
                                                @if(IS_ADMIN)
                                                <th>Instructor</th>
                                                @endif
                                                <th>Status</th>
                                                @if(IS_ADMIN || \Helper::checkRules('edit-student-request'))
                                                <th style="padding-left: 50px">Actions</th>                            
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data->requests as $value)
                                            <tr id="tableRaw{{ $value->id }}">
                                                <td width="3%">{{ $value->id }}</td>
                                                <td width="20%">
                                                    <div class="course-data">
                                                        <a href="{{ URL::to('/courses/view/'.$value->course_id) }}" target="_blank">{{ $value->course->title }}</a>
                                                    </div>
                                                </td>
                                                @if(IS_ADMIN)
                                                <td>
                                                    <div class="course-data">
                                                        <a href="{{ URL::to('/users/view/'.$value->instructor_id) }}" target="_blank">{{ $value->instructor->name }}</a>
                                                    </div>
                                                </td>
                                                @endif
                                                <td>{{ $value->status == 1 ? 'Active' : ($value->status == 0 ? 'In Active' : 'Student Sent Request') }}</td>
                                                @if(IS_ADMIN || \Helper::checkRules('edit-student-request'))
                                                <td width="20%" align="center">
                                                    @if(\Helper::checkRules('edit-student-request'))
                                                        @if($value->status != 1)
                                                        <a href="{{ URL::to('/requests/update/' . $value->id . '/1') }}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i> Accept </a>
                                                        @endif
                                                        @if($value->status != 0)
                                                        <a href="{{ URL::to('/requests/update/' . $value->id . '/0') }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Refuse </a>
                                                        @endif
                                                    @endif
                                                    @if(\Helper::checkRules('delete-student-request'))
                                                        <a onclick="deleteRequest('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                                    @endif
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <div class="empty">No Requests Available</div>
                                    @endif
                                </div>
                                @endif
                                <div role="tabpanel" class="tab-pane fade {{ $data->data->group_id == 3 ? '' : 'active in' }}" id="tab_content1" aria-labelledby="home-tab">
                                    @if(!empty((array) $data->courses))
                                    <!-- start user projects -->
                                    <table class="data table table-striped no-margin">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Course Type</th>
                                                <th width="10%">University</th>
                                                <th width="10%">Faculty</th>
                                                <th>Field</th>
                                                <th>Price</th>
                                                <th>Valid Until</th>
                                                <th>Status</th>
                                                @if($data->data->group_id == 3)
                                                <th>View Duration</th>
                                                @endif
                                                <th style="padding-left: 50px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data->courses as $key => $value)
                                            <tr>
                                                <td width="3%">{{ $value->id }}</td>
                                                <td>{{ $value->title }}</td>
                                                <td>{{ $value->course_type == 1 ? 'General' : 'University & Faculty' }}</td>
                                                <td>{{ $value->university }}</td>
                                                <td>{{ $value->faculty }}</td>
                                                <td>{{ $value->field }}</td>
                                                <td>{{ $value->price }} $</td>
                                                <td>{{ $value->valid_until }}</td>
                                                <td>{!! $value->statusLabel !!}</td>
                                                @if($data->data->group_id == 3)
                                                <td>
                                                    @if($value->seeDuration)
                                                    {{ $value->seeDuration }} <br> of {{ $value->allTime }}
                                                    @endif
                                                </td>
                                                @endif
                                                <td width="150px" align="center">
                                                    @if(\Helper::checkRules('edit-course'))
                                                        <a href="{{ URL::to('/courses/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                                    @endif
                                                    @if(\Helper::checkRules('view-course'))
                                                        <a href="{{ URL::to('/courses/view/' . $value->id) }}" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> View </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- end user projects -->
                                    @else
                                    <div class="empty">No Courses Available</div>
                                    @endif
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="comment-tab">
                                    <!-- start recent activity -->
                                    <ul class="messages messages1">
                                        @if(!empty((array)$data->comments))
                                        @foreach($data->comments as $comment)
                                        <li id="tableRaw{{ $comment->id }}">
                                            <img src="{{ $comment->image }}" class="avatar" alt="Avatar">
                                            <div class="message_wrapper">
                                                <h4 class="heading">{!! $comment->creator !!} On <a href="{{ URL::to('/videos/'.$comment->video_id.'/comments') }}">{{ $comment->video_title }}</a></h4>
                                                <p class="url">
                                                    @if(\Helper::checkRules('delete-video-comment'))
                                                    <a href="#" class="btn btn-xs btn-danger" onclick="deleteComment({{ $comment->id }})"><i class="fa fa-trash"></i> </a>
                                                    @endif
                                                </p>
                                                <div class="clearfix"></div>
                                                <span class="time">{{ $comment->created_at }}</span>
                                                <p class="message">{{ $comment->comment }}</p>
                                            </div>
                                            @if($comment->reply_on == 0 && !empty((array)$comment->replies))
                                            <ul class="messages messages2">
                                            @endif   
                                            @foreach($comment->replies as $reply)
                                            <li id="tableRaw{{ $reply->id }}">
                                                <img src="{{ $reply->image }}" class="avatar" alt="Avatar">
                                                <div class="message_wrapper">
                                                    <h4 class="heading">{!! $reply->creator !!}</h4>
                                                    <p class="url">
                                                        @if(\Helper::checkRules('delete-video-comment'))
                                                        <a href="#" class="btn btn-xs btn-danger" onclick="deleteComment({{ $reply->id }})"><i class="fa fa-trash"></i> </a>
                                                        @endif
                                                    </p>
                                                    <div class="clearfix"></div>
                                                    <span class="time">{{ $reply->created_at }}</span>
                                                    <p class="message">{{ $reply->comment }}</p>
                                                </div>
                                            </li>
                                            @endforeach
                                            @if($comment->reply_on == 0 && !empty((array)$comment->replies))
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                        @else
                                        <li>No Comments</li>
                                        @endif
                                    </ul>
                                    <div class="row comment">
                                        <div class="col-xs-11">
                                            <textarea class="form-control comment" placeholder="Write a comment"></textarea>
                                        </div>
                                        <div class="col-xs-1">
                                            <button class="btn btn-sm btn-success"><i class="fa fa-send"></i> Send</button>
                                        </div>
                                    </div>
                                </div>
                                @if($data->data->group_id == 3)
                                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="review-tab">
                                    <div class="col-xs-12 feedback">
                                        <div class="row">
                                            <ul class="messages">
                                                @if(!empty((array) $data->reviews))
                                                @foreach($data->reviews as $reply)
                                                <li id="tableRaw{{ $reply->id }}">
                                                    <img src="{{ $reply->image }}" class="avatar" alt="Avatar">
                                                    <div class="message_wrapper">
                                                        <div>
                                                            <h4 class="heading">{{ $reply->creator }} On <a href="{{ URL::to('/courses/view/'.$reply->course_id) }}">{{ $reply->course_title }}</a></h4>
                                                            <p class="url">
                                                                @if(\Helper::checkRules('delete-course-review'))
                                                                <a href="#" class="btn btn-xs btn-danger" onclick="deleteReview({{ $reply->id }})"><i class="fa fa-trash"></i> </a>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <span class="time">{{ $reply->created_at }}</span>
                                                        <p class="message">{{ $reply->content }}</p>
                                                        <div class="rate">
                                                            @if($reply->rate != 0 )
                                                            @for($i=0;$i<$reply->rate;$i++)
                                                            <i class="fa fa-star"></i>
                                                            @endfor
                                                            @else
                                                            No Rate
                                                            @endif
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </li>
                                                @endforeach
                                                @else
                                                <li>No Reviews Available</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="rate-tab">
                                    <div class="col-xs-12 feedback">
                                        <div class="row">
                                            <ul class="messages">
                                                @if(!empty((array) $data->rates))
                                                @foreach($data->rates as $reply)
                                                <li id="tableRaw{{ $reply->id }}">
                                                    <img src="{{ $reply->image }}" class="avatar" alt="Avatar">
                                                    <div class="message_wrapper">
                                                        <div>
                                                            @if($data->data->group_id == 3)
                                                            <h4 class="heading">
                                                                {{ $reply->creator }}
                                                                On <a href="{{ URL::to('/users/view/'.$reply->instructor_id) }}" target="_blank">{{ $reply->instructor }}</a>
                                                            </h4>
                                                            @else
                                                            <h4 class="heading">
                                                                <a href="{{ URL::to('/users/view/'.$reply->student_id) }}" target="_blank">{{ $reply->creator }}</a>  </h4>
                                                            @endif
                                                            <p class="url">
                                                                @if(\Helper::checkRules('delete-course-review'))
                                                                <a href="#" class="btn btn-xs btn-danger" onclick="deleteRate({{ $reply->id }})"><i class="fa fa-trash"></i> </a>
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <span class="time">{{ $reply->created_at }}</span>
                                                        <div class="rate">
                                                            @if($reply->rate != 0 )
                                                            @for($i=0;$i<$reply->rate;$i++)
                                                            <i class="fa fa-star"></i>
                                                            @endfor
                                                            @else
                                                            No Rate
                                                            @endif
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </li>
                                                @endforeach
                                                @else
                                                <li>No Rates Available</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @if($data->data->group_id == 3)
                                <div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="score-tab">
                                    <div class="row">
                                        <div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                            <div class="tile-stats">
                                                <div class="icon"><i class="fa fa-question-circle"></i></div>
                                                <div class="count">{{ $data->scores->total->allQuestion }}</div>
                                                <h3>Answers</h3>
                                            </div>
                                        </div>
                                        <div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                            <div class="tile-stats">
                                                <div class="icon"><i class="fa fa-check-circle"></i></div>
                                                <div class="count">{{ $data->scores->total->studentRightAnswers }}</div>
                                                <h3>Right Answers</h3>
                                            </div>
                                        </div>
                                        <div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                            <div class="tile-stats">
                                                <div class="icon"><i class="fa fa-times-circle"></i></div>
                                                <div class="count">{{ $data->scores->total->studentWrongAnswers }}</div>
                                                <h3>Wrong Answers</h3>
                                            </div>
                                        </div>
                                        <div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                            <div class="tile-stats">
                                                <div class="icon"><i class="fa fa-spinner"></i></div>
                                                <div class="count">{{ $data->scores->total->score }}</div>
                                                <h3>Total Score</h3>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!empty((array) $data->scores))
                                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                    @foreach($data->scores->scores as $key => $value)
                                    <div class="panel">
                                        <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{ $value->id }}" aria-expanded="false" aria-controls="collapseOne">
                                            <h4 class="panel-title col-xs-12 col-sm-12">{{ $key+1 }}- {{ $value->course }}</h4>
                                            <h4 class="panel-title text-right col-xs-12 col-sm-12">
                                                <span><i class="fa fa-question-circle"></i> {{ $value->all }}</span>
                                                <span><i class="fa fa-check-circle"></i> {{ $value->right }}</span>
                                                <span><i class="fa fa-times-circle"></i> {{ $value->wrong }}</span>
                                                <span><i class="fa fa-spinner"></i> {{ $value->score }}</span>
                                                <span><i class="fa fa-star"></i> Rank: {{ $value->rank }}</span>
                                                <div class="clearfix"></div>
                                            </h4>
                                            <div class="clearfix"></div>
                                        </a>
                                        <div id="collapseOne{{ $value->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                            <div class="panel-body">
                                                <table class="data table table-striped no-margin">
                                                    <thead>
                                                        <tr>
                                                            <th>Course</th>
                                                            <th>Lesson</th>
                                                            <th>Question</th>
                                                            <th>Answer</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($value->lessons as $lessonKey => $lessonValue)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ URL::to('/courses/view/'.$value->course_id) }}" target="_blank">{{ $value->course }}</a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ URL::to('/lessons/edit/'.$lessonValue->lesson_id) }}" target="_blank">{{ $lessonValue->lesson }}</a>
                                                            </td>
                                                            <td>{{ $lessonValue->question }}</td>
                                                            <td>{{ $lessonValue->answer }}</td>
                                                            <td>
                                                                @if($lessonValue->correct == 0)
                                                                <span class="label label-danger">Wrong</span>
                                                                @else
                                                                <span class="label label-success">Correct</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>        
                                    @endforeach
                                    </div>
                                    @else
                                    <div class="empty">No Questions Answered</div>
                                    @endif 
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop()

@section('script')
    <script src="{{asset('assets/components/users.js')}}"></script>
    <script src="{{asset('assets/components/courses.js')}}"></script>
    <script src="{{asset('assets/components/comments.js')}}"></script>
@stop
