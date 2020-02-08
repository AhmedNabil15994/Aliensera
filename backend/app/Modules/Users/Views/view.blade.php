@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->name)
@section('otherhead')
<style type="text/css" media="screen">
    .user_data li{
        color: #777;
        font-size: 14px;
    }
    .user_data li i{
        color: #1ABB9C;
        margin-right: 10px;
    }
    ul.messages li .message_wrapper h4.heading{
        margin-bottom: 0;
        float: left;
        padding-top: 3px;
        width: 75%;
    }
    p.url{
        float: left;
        width: 25%;
        text-align: right;
        margin-bottom: 0;
    }
    p.url a{
        margin-bottom: 0;
    }
    ul.messages li .message_wrapper h4.heading{
        margin-bottom: 5px;
    }
    span.time{
        display: block;
        margin-bottom: 10px;
        color: #777;
        margin-top: -8px;
    }
    div.row.comment{
        margin-top: 15px;
        display: none;
    }
    ul.bar_tabs{
        background: unset;
        margin-top: 40px;
    }
    li.pull-right.btn-default{
        margin-top: -10px;
    }
    ul.messages2{
        border: 1px solid #DDD;
        margin-left: 45px;
        border-radius: 5px;
        padding: 10px;
        margin-right: 45px;
    }
    i.fa-star{
        color: #FFC400;
        font-size: 15px;
    }
    .profile_left{
        border-right: 1px solid #DDD;
    }
    div.empty{
        border-bottom: 1px dotted #e6e6e6;
        padding: 8px 0;
    }
    div.collapse.in:hover{
        background: #FFF;
        background-color: #FFF;
    }
    .panel-heading h4{
        width: 50%;
        display: inline-block;
        float: left;
    }
    .panel-heading h4 span{
        display: inline-block;
        width: 25%;
        float: left;
        font-size: 18px;
    }
    i.fa-question-circle{
        color: #337ab7;
    }
    i.fa-check-circle{
        color: #26B99A;
    }
    i.fa-times-circle{
        color: #d9534f;
    }
    i.fa-spinner{
        color: #5bc0de;
    }
</style>
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
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
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
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" role="tab" id="home-tab" data-toggle="tab" aria-expanded="false">Courses ({{ count((array) $data->courses) }})</a></li>
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
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
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
                                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <div class="tile-stats">
                                                <div class="icon"><i class="fa fa-question-circle"></i></div>
                                                <div class="count">{{ $data->scores->total->allQuestion }}</div>
                                                <h3>Answers</h3>
                                            </div>
                                        </div>
                                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <div class="tile-stats">
                                                <div class="icon"><i class="fa fa-check-circle"></i></div>
                                                <div class="count">{{ $data->scores->total->studentRightAnswers }}</div>
                                                <h3>Right Answers</h3>
                                            </div>
                                        </div>
                                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <div class="tile-stats">
                                                <div class="icon"><i class="fa fa-times-circle"></i></div>
                                                <div class="count">{{ $data->scores->total->studentWrongAnswers }}</div>
                                                <h3>Wrong Answers</h3>
                                            </div>
                                        </div>
                                        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
                                            <h4 class="panel-title">{{ $key+1 }}- {{ $value->course }}</h4>
                                            <h4 class="panel-title text-right">
                                                <span><i class="fa fa-question-circle"></i> {{ $value->all }}</span>
                                                <span><i class="fa fa-check-circle"></i> {{ $value->right }}</span>
                                                <span><i class="fa fa-times-circle"></i> {{ $value->wrong }}</span>
                                                <span><i class="fa fa-spinner"></i> {{ $value->score }}</span>
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
                                                                <a href="{{ URL::to('/coures/view/'.$value->course_id) }}" target="_blank">{{ $value->course }}</a>
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
