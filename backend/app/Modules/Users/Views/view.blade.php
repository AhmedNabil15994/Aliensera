@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->display_name)
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
</style>
@endsection
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data->data->group }}: <small>{{ $data->data->display_name }}</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
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
                        <h3>{{ $data->data->display_name }}</h3>
                        <ul class="list-unstyled user_data">
                            <li><i class="fa fa-map-marker user-profile-icon"></i> {{$data->data->address}}</li>
                            <li><i class="fa fa-briefcase user-profile-icon"></i> {{ $data->data->group }}</li>
                            <li><i class="fa fa-user"></i> {{ $data->data->gender }}</li>
                            <li class="m-top-xs"><i class="fa fa-phone"></i> {{ $data->data->phone }}</li>
                            <li class="m-top-xs"><i class="fa fa-envelope"></i> {{ $data->data->email }}</li>
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
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
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
    <script src="{{URL::asset('assets/components/users.js')}}"></script>
    <script src="{{URL::asset('assets/components/courses.js')}}"></script>
    <script src="{{URL::asset('assets/components/comments.js')}}"></script>
@stop
