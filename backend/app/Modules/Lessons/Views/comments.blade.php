@extends('Layouts.master')
@section('title', 'Comments')
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/comments.css') }}">
@endsection
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><small>{{ $data->data->title }}</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-xs-12">
                        <div class="profile_title">
                            <div class="embed-responsive embed-responsive-16by9 col-xs-12">
                                <div class="embed-container">
                                    <iframe src="{{ $data->data->link }}" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="row results">
                                    <h3>Video Details</h3>
                                    <p class="col-md-2 col-xs-6"> <span class="glyphicon glyphicon-book"></span> Course: {{ $data->data->course }}</p>
                                    <p class="col-md-2 col-xs-6"><i class="fa fa-book"></i> Lecture: {{ $data->data->lesson }}</p>
                                    <p class="col-md-2 col-xs-6"><i class="fa fa-file-movie-o"></i> Size: {{ $data->data->size }}</p>
                                    <p class="col-md-2 col-xs-6"><i class="fa fa-comments"></i> <span class="total_comments">{{ $data->count }}</span> Comments</p>
                                    <p class="col-md-2 col-xs-6"><i class="fa fa-clock-o"></i> Duration: {{ $data->data->duration }}</p>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Recent Comments</a></li>
                                @if(\Helper::checkRules('add-video-comment'))
                                <li class="pull-right btn btn-md btn-default"><i class="fa fa-plus"></i> Add Comment</li>
                                @endif
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <!-- start recent activity -->
                                    <ul class="messages messages1">
                                        @if(!empty((array)$data->data->comments))
                                        @foreach($data->data->comments as $comment)
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
                                        <div class="col-md-11 col-xs-10">
                                            <textarea class="form-control comment" placeholder="Write a comment"></textarea>
                                        </div>
                                        <div class="col-md-1 col-xs-2">
                                            <button class="btn btn-sm btn-success"><i class="fa fa-send"></i> Send</button>
                                        </div>
                                    </div>
                                    <!-- end recent activity -->
                                </div>
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
<script src="{{ asset('assets/components/comments.js') }}" type="text/javascript"></script>
@endsection