@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('otherhead')
<style type="text/css" media="screen">
    .feedback{
        padding-left: 25px;
    }
    div.images{
        border-right: 1px solid #DDD;
    }
    h3 span{
        font-size: 14px;
        color: #777;
    }
    .messages li{
        border: 1px solid #DDD;
        border-radius: 5px;
        padding-left: 10px !important;
        margin-bottom: 5px;
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
    span.time{
        display: block;
        margin-bottom: 10px;
        color: #777;
        margin-top: -10px;
    }
    i.fa-star{
        color: #FFC400;
        font-size: 15px;
    }
    .course_details p{
        font-size: 15px;
        color: #55;
    }
    .course_details p span{
        color: #777;
    }
    .course_details p span.price{
        font-size: 16px;
        color: #26B99A;
    }
    .course_details p span span.btn{
        color: #FFF;
    }
    ul.list-inline.prod_size li{
        padding: 10px;
        font-size: 15px;
        background: transparent;
        color: #666;
    }
    li span i.fa{
        color: #26B99A;
        font-size: 20px;
    }
    .product-image img{
        height: 400px;
        max-height: 500px;
    }
    .prod_title{
        margin-top: 0;
        margin-bottom: 15px;
    }
    div.playlist,
    div.quiz{
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        padding-top: 0;
        padding-bottom: 0;
    }
    div.playlist div.row,
    div.quiz div.row{
        padding-bottom: 10px;
        padding-top: 10px;
        border-bottom: 1px solid #DDD;
    }
    div.playlist div.row:first-of-type,
    div.quiz div.row:first-of-type{
        padding-top: 10px;
        background: #eee;
    }
    div.playlist div.row:last-child,
    div.quiz div.row:last-child{
        border-bottom: 0 ;
    }
    div.playlist div.row div,
    div.quiz div.row div{
        font-weight: bold;
        font-size: 13px;
    }
    .btn-xs i.fa{
        font-size: 14px;
    }
    .playlist .row.results,
    .quiz .row.results{
        font-weight: 400;
        color: #555;
    }
    .playlist .row.results:hover,
    .quiz .row.results:hover{
        background: #f9f9f9;
        cursor: pointer;
    }
    .collapse.in:hover{
        background: #FFF;
    }
    p.desc{
        color: #777;
    }
    .embed-container {
        position: relative;
        padding-bottom: 56.25%;
        overflow: hidden;
    }    
    .embed-container iframe,
    .embed-container object,
    .embed-container embed {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endsection
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $data->data->title }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="product-image">
                            <img src="{{ $data->data->image }}" alt="..." />
                        </div>
                        <div class="product_gallery">
                            <h2></h2>
                            <ul class="list-inline prod_size">
                                <li class="label label-default label-xs">
                                    <span>
                                        <i class="fa fa-book"></i>
                                        {{ $data->data->lessonsCount }} Lectures
                                    </span>
                                </li>
                                <li class="label label-default label-xs">
                                    <span>
                                        <i class="fa fa-caret-square-o-right"></i>
                                        <span class="total_videos">{{ $data->data->videosCount }}</span> Videos
                                    </span>
                                </li>
                                <li class="label label-default label-xs">
                                    <span>
                                        <i class="fa fa-clock-o"></i>
                                        <span>{{ $data->data->allTime }}</span>
                                    </span>
                                </li>
                                <li class="label label-default label-xs">
                                    <span>
                                        <i class="fa fa-comments-o"></i>
                                        {{ $data->data->commentsCount }} Comments
                                    </span>
                                </li>
                                <li class="label label-default label-xs">
                                    <span>
                                        <i class="fa fa-star"></i>
                                        ({{ $data->data->rateCount }} Reviews) {{ $data->data->totalRate }} of 5
                                    </span>
                                </li>
                            </ul> 
                        </div>
                    </div>

                    <div class="col-md-5 col-sm-5 col-xs-12" style="border:0px solid #e5e5e5;">
                        <h3 class="prod_title">{{ $data->data->title }}</h3>
                        <p class="desc">{{ $data->data->description }}</p>
                        <br />

                        <div class="course_details">
                            <h2>Course Details</h2>
                            <p>Course Type : <span>{{ $data->data->courseType }}</span></p>
                            @if($data->data->course_type == 2)
                            <p>University : <span>{{ $data->data->university }}</span></p>
                            <p>Faculty : <span>{{ $data->data->faculty }}</span></p>
                            @endif
                            <p>Field : <span>{{ $data->data->field }}</span></p>
                            <p>Instructor : <span>{{ $data->data->instructor }}</span></p>
                            <p>Price : <span class="price">{{ $data->data->price }} $</span></p>
                            <p>Valid Until : <span>{{ $data->data->valid_until }}</span></p>
                            <p>Status : <span>{!! $data->data->statusLabel !!}</span></p>
                        </div>
                        <br />
                    </div>

                    <div class="col-md-12">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Lectures</a></li>
                                <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Quizes</a></li>
                                <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Reviews</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                        @if(!empty((array) $data->data->lessons))
                                        @foreach($data->data->lessons as $key => $lesson)
                                        <div class="panel">
                                            <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{ $lesson->id }}" aria-expanded="false" aria-controls="collapseOne">
                                                <h4 class="panel-title">{{ $key+1 }}- {{ $lesson->title }}</h4>
                                            </a>
                                            <div id="collapseOne{{ $lesson->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    @if(!empty((array)$lesson->videos))
                                                    <div class="playlist">
                                                        <div class="row">
                                                            <div class="col-xs-1">No#</div>
                                                            <div class="col-xs-3">Lecture</div>
                                                            <div class="col-xs-2 text-center">Duration</div>
                                                            <div class="col-xs-2 text-center">Size</div>
                                                            <div class="col-xs-2 text-center">Free</div>
                                                            <div class="col-xs-2 text-center">Action</div>
                                                        </div>
                                                        @foreach($lesson->videos as $keyr => $video)
                                                        <div class="row results" id="results{{ $video->id }}">
                                                            <div class="col-xs-1">{{ $keyr+1 }}</div>
                                                            <div class="col-xs-3">{{ $video->title }}</div>
                                                            <div class="col-xs-2 text-center">{{ $video->duration }}</div>
                                                            <div class="col-xs-2 text-center">{{ $video->size }}</div>
                                                            <div class="col-xs-2 text-center">{{ $video->free }}</div>
                                                            <div class="col-xs-2 text-center">
                                                                <button class="btn btn-default btn-xs" data-link="{{ $video->link }}"><i class="fa fa-play"></i></button>
                                                                <a href="{{ URL::to('/videos/'.$video->id.'/comments') }}" class="btn btn-primary btn-xs"><i class="fa fa-comments"></i></a>
                                                                @if(\Helper::checkRules('delete-lesson-video'))
                                                                <button class="btn btn-danger btn-xs" onclick="deleteLecture({{ $video->id }})"><i class="fa fa-trash"></i></button>
                                                                @endif
                                                                <a href="{{ URL::to('/videos/'.$video->id.'/changeStatus') }}" class="btn btn-success btn-xs"><i class="fa fa-video-camera"></i> Toggle Free</a>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @else
                                                    <div class="empty">
                                                        No Videos Available
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        No Lectures Available
                                        @endif
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                    <div class="accordion" id="accordion2" role="tablist" aria-multiselectable="true">
                                    @if(!empty((array) $data->data->lessons))
                                    @foreach($data->data->lessons as $key => $lesson)
                                        <div class="panel">
                                            <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo{{ $lesson->id }}" aria-expanded="false" aria-controls="collapseTwo">
                                                <h4 class="panel-title">{{ $key+1 }}- {{ $lesson->title }}</h4>
                                            </a>
                                            <div id="collapseTwo{{ $lesson->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseTwo" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    @if(!empty((array)$lesson->questions))
                                                    <div class="quiz">
                                                        <div class="row">
                                                            <div class="col-xs-1">No#</div>
                                                            <div class="col-xs-2">Question</div>
                                                            <div class="col-xs-2">Answer A</div>
                                                            <div class="col-xs-2">Answer B</div>
                                                            <div class="col-xs-2">Answer C</div>
                                                            <div class="col-xs-2">Answer D</div>
                                                            <div class="col-xs-1 text-center">Action</div>
                                                        </div>
                                                        @foreach($lesson->questions as $key => $question)
                                                        <div class="row results" id="questions{{ $question->id }}">
                                                            <div class="col-xs-1">{{ $key+1 }}</div>
                                                            <div class="col-xs-2">{{ $question->question }}</div>
                                                            <div class="col-xs-2">{!! $question->answer_a !!}</div>
                                                            <div class="col-xs-2">{!! $question->answer_b !!}</div>
                                                            <div class="col-xs-2">{!! $question->answer_c !!}</div>
                                                            <div class="col-xs-2">{!! $question->answer_d !!}</div>
                                                            <div class="col-xs-1 text-center">
                                                                @if(\Helper::checkRules('delete-lesson-question'))
                                                                <button class="btn btn-danger btn-xs" onclick="deleteQuestion({{ $question->id }})"><i class="fa fa-trash"></i></button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @else
                                                    <div class="empty">
                                                        No Quizes Available
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @else
                                    No Lectures Available
                                    @endif
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                                    <div class="col-xs-12 feedback">
                                        <div class="row">
                                            <ul class="messages">
                                                @if(!empty((array) $data->data->feedback))
                                                @foreach($data->data->feedback as $reply)
                                                <li id="tableRaw{{ $reply->id }}">
                                                    <img src="{{ $reply->image }}" class="avatar" alt="Avatar">
                                                    <div class="message_wrapper">
                                                        <div>
                                                            <h4 class="heading">{{ $reply->creator }}</h4>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Partials.videoPlayer')
@stop()
@section('script')
<script src="{{ asset('assets/components/courses.js')}}"></script>
@stop()
