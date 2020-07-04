@extends('Layouts.master')
@section('title', $data->course->id . ' - ' . $data->course->title)
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/discussion.css') }}">
@endsection
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data->course->title }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="product-image">
                            <img src="{{ $data->course->image }}" alt="..." />
                        </div>
                        <div class="product_gallery">
                            <h2></h2>
                            <h3 class="prod_title">Description</h3>
                            <p class="desc">{{ $data->course->description }}</p>
                            <br />
                        </div>
                    </div>

                    <div class="col-md-5 col-sm-5 col-xs-12" style="border:0px solid #e5e5e5;">
                        <h3 class="prod_title">Requirements</h3>
                        <p class="desc">{!! $data->course->requirements !!}</p>
                        <br>
                        <h3 class="prod_title">What Student Learn !?</h3>
                        <p class="desc">{{ $data->course->what_learn }}</p>
                        <br>
                        <div class="course_details">
                            <h3 class="prod_title">Course Details</h3>
                            <div class="col-lg-6 col-xs-12">
                                <p>Course Type : <span>{{ $data->course->courseType }}</span></p>
                                <p>Field : <span>{{ $data->course->field }}</span></p>
                                <p>Instructor : <span>{{ $data->course->instructor }}</span></p>
                                @if($data->course->course_type == 2)
                                <p>University : <span>{{ $data->course->university }}</span></p>
                                @endif
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <p>Price : <span class="price">{{ $data->course->price }} $</span></p>
                                <p>Valid Until : <span>{{ $data->course->valid_until }}</span></p>
                                <p>Status : <span>{!! $data->course->statusLabel !!}</span></p>
                                @if($data->course->course_type == 2)
                                <p>Faculty : <span>{{ $data->course->faculty }}</span></p>
                                @endif
                            </div>
                        </div>
                        <br />
                    </div>

                    <div class="col-xs-12">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Recent Discussion</a></li>
                                @if(\Helper::checkRules('add-video-comment'))
                                <li class="pull-right btn btn-md btn-default"><i class="fa fa-plus"></i> Add Comment</li>
                                @endif
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <!-- start recent activity -->
                                    <ul class="messages messages1">
                                        @if(!empty($data->data))
                                        @foreach($data->data as $comment)
                                        <li id="tableRaw{{ $comment->id }}">
                                            <img src="{{ $comment->image }}" class="avatar" alt="Avatar">
                                            <div class="message_wrapper">
                                                <h4 class="heading">{!! $comment->creator !!}</h4>
                                                <span class="time">{{ $comment->created_at }}</span>
                                                <p class="message">{{ $comment->comment }}</p>
                                                <p class="url">
                                                    @if(\Helper::checkRules('add-video-comment'))
                                                    <a href="#" class="reply" data-area="{{ $comment->id }}"><i class="fa fa-reply"></i> Reply </a> &nbsp;
                                                    @endif
                                                    @if(\Helper::checkRules('delete-video-comment'))
                                                    <a href="#" onclick="deleteComment({{ $comment->id }})"><i class="fa fa-trash"></i> Delete </a>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="clearfix"></div>
                                            @if($comment->reply_on == 0 && !empty((array)$comment->replies))
                                            <ul class="messages messages2">
                                            @endif   
                                            @foreach($comment->replies as $reply)
                                            <li id="tableRaw{{ $reply->id }}">
                                                <img src="{{ $reply->image }}" class="avatar" alt="Avatar">
                                                <div class="message_wrapper">
                                                    <h4 class="heading">{!! $reply->creator !!}</h4>
                                                    <span class="time">{{ $reply->created_at }}</span>
                                                    <p class="message">{{ $reply->comment }}</p>
                                                    <p class="url">
                                                        @if(\Helper::checkRules('add-video-comment'))
                                                        <a href="#" class="reply" data-area="{{ $reply->id }}"><i class="fa fa-reply"></i> Reply </a> &nbsp;
                                                        @endif
                                                        @if(\Helper::checkRules('delete-video-comment'))
                                                        <a href="#" onclick="deleteComment({{ $reply->id }})"><i class="fa fa-trash"></i> Delete </a>
                                                        @endif
                                                    </p>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </li>
                                            @endforeach
                                            @if($comment->reply_on == 0 && !empty((array)$comment->replies))
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                        @else
                                        <li>No Discussion</li>
                                        @endif
                                    </ul>
                                    <div class="row comment">
                                        <div class="col-md-11 col-xs-10">
                                            <textarea class="form-control comment" placeholder="Write a comment"></textarea>
                                        </div>
                                        <div class="col-md-1 col-xs-2">
                                            <button class="btn btn-sm btn-success"><i class="fa fa-send"></i> Send</button>
                                        </div>
                                    </div>
                                    <!-- end recent activity -->
                                </div>
                                @include('Partials.pagination')
                                <div class="clearfix"></div>
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
<script src="{{ asset('assets/components/discussion.js')}}"></script>
@stop()
