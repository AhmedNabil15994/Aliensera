@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/view-course.css') }}">
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
                            <h3 class="prod_title">Description</h3>
                            <p class="desc">{{ $data->data->description }}</p>
                            <br />
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
                        <h3 class="prod_title">Requirements</h3>
                        <p class="desc">{!! $data->data->requirements !!}</p>
                        <br>
                        <h3 class="prod_title">What Student Learn !?</h3>
                        <p class="desc">{{ $data->data->what_learn }}</p>
                        <br>
                        <div class="course_details">
                            <h3 class="prod_title">Course Details</h3>
                            <div class="col-lg-6 col-xs-12">
                                <p>Course Type : <span>{{ $data->data->courseType }}</span></p>
                                <p>Field : <span>{{ $data->data->field }}</span></p>
                                <p>Instructor : <span>{{ $data->data->instructor }}</span></p>
                                @if($data->data->course_type == 2)
                                <p>University : <span>{{ $data->data->university }}</span></p>
                                @endif
                                <p>Used Quota : <span>{{ $data->data->quota }} GB</span></p>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <p>Price : <span class="price">{{ $data->data->price }} $</span></p>
                                <p>Valid Until : <span>{{ $data->data->valid_until }}</span></p>
                                <p>Status : <span>{!! $data->data->statusLabel !!}</span></p>
                                @if($data->data->course_type == 2)
                                <p>Faculty : <span>{{ $data->data->faculty }}</span></p>
                                @endif
                                <p>Accepted Request : <span>{{ $data->data->requests }} Requests</span></p>
                            </div>
                        </div>
                        <br />
                    </div>

                    <div class="col-xs-12">
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Lectures</a></li>
                                <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Quizes</a></li>
                                <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Reviews</a></li>
                                <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">Views Average</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in example" id="tab_content1" aria-labelledby="home-tab">
                                    <ol class="accordion example" id="accordion" role="tablist" aria-multiselectable="true">
                                        @if(!empty((array) $data->data->lessons))
                                        @foreach($data->data->lessons as $key => $lesson)
                                        <li class="panel"  data-tab="{{ $lesson->id }}">
                                            <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{ $lesson->id }}" aria-expanded="false" aria-controls="collapseOne">
                                                <h4 class="panel-title"><i class="fa fa-arrows-alt first"></i> {{ $key+1 }}- {{ $lesson->title }}</h4>
                                            </a>
                                            <div id="collapseOne{{ $lesson->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    @if(!empty((array)$lesson->videos))
                                                    <div class="playlist">
                                                        <div class="row">
                                                            <div class="col-xs-1">No#</div>
                                                            <div class="col-xs-2">Lecture</div>
                                                            <div class="col-xs-2 text-center">Duration</div>
                                                            <div class="col-xs-2 text-center">Size</div>
                                                            <div class="col-xs-2 text-center">Free</div>
                                                            <div class="col-xs-3 text-center">Action</div>
                                                        </div>
                                                        @foreach($lesson->videos as $keyr => $video)
                                                        <div class="row results" data-tab="{{ $video->id }}" id="results{{ $video->id }}">
                                                            <div class="col-xs-1"><i class="fa fa-arrows-alt second"></i>  {{ $keyr+1 }}</div>
                                                            <div class="col-xs-2 title">{{ $video->title }}</div>
                                                            <div class="col-xs-2 text-center">{{ $video->duration }}</div>
                                                            <div class="col-xs-2 text-center">{{ $video->size }}</div>
                                                            <div class="col-xs-2 text-center">{{ $video->free }}</div>
                                                            <div class="col-xs-3 text-center">
                                                                <button class="btn btn-default btn-xs" data-link="{{ $video->link }}"><i class="fa fa-play"></i> Play Video</button>
                                                                @if(\Helper::checkRules('delete-lesson-video'))
                                                                <button class="btn btn-danger btn-xs" onclick="deleteLecture({{ $video->id }})"><i class="fa fa-trash"></i> Delete</button>
                                                                @endif
                                                                <a href="{{ URL::to('/videos/'.$video->id.'/comments') }}" class="btn btn-primary btn-xs"><i class="fa fa-comments"></i> Comments</a>
                                                                @if(\Helper::checkRules('change-video-status'))
                                                                <a href="{{ URL::to('/videos/'.$video->id.'/changeStatus') }}" class="btn btn-success btn-xs"><i class="fa fa-video-camera"></i> Toggle Free</a>
                                                                @endif
                                                                <a class="btn btn-warning btn-xs" target="_blank" data-tab="{{ $video->id }}" data-area="{{ $lesson->id }}"><i class="fa fa-share"></i> Move To Another Lesson</a>
                                                                @if(\Helper::checkRules('add-video-attachment'))
                                                                @if($video->attachment == '')
                                                                <input id="fileUpload" class="hidden" name="attachment" type="file">
                                                                <button class="btn btn-info btn-xs" onclick="uploadAttachment({{ $video->id }})"><i class="fa fa-file"></i> Upload PDF</button>
                                                                @else
                                                                <a class="btn btn-info btn-xs" href="{{ $video->attachment }}" target="_blank"><i class="fa fa-file"></i> Download PDF</a>
                                                                <a class="btn btn-dark btn-xs" href="{{ URL::to('/videos/'.$video->id.'/removeAttachment') }}"><i class="fa fa-trash"></i> Remove PDF</a>
                                                                @endif
                                                                @endif
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
                                        </li>
                                        @endforeach
                                        @else
                                        No Lectures Available
                                        @endif
                                    </ol>
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

                                                    @foreach($lesson->studentScores as $score)
                                                    <div class="row rowitem">
                                                        <div class="col-xs-4"><i class="fa fa-user"></i><a href="{{ URL::to('/users/view/'.$score->student_id) }}" target="_blank"> {{ $score->student }}</a> </div>
                                                        <div class="col-xs-2"><i class="fa fa-question-circle"></i> {{ $score->all }}</div>
                                                        <div class="col-xs-2"><i class="fa fa-check-circle"></i> {{ $score->right }}</div>
                                                        <div class="col-xs-2"><i class="fa fa-times-circle"></i> {{ $score->wrong }}</div>
                                                        <div class="col-xs-2"><i class="fa fa-spinner"></i> {{ $score->score }}</div>
                                                    </div>
                                                    @endforeach
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
                                <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                                    <div class="col-xs-12 feedback">
                                        @foreach($data->data->rates as $rateKey => $rateValue)
                                        <div class="row">
                                            <div class="col-xs-2">
                                                {{ ($rateKey+1) * 10 }}%
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" data-transitiongoal="{{ ($rateKey+1) * 10 }}" aria-valuenow="{{ ($rateKey+1) * 10 }}" style="width: {{ ($rateKey+1) * 10 }}%;"></div> 
                                                </div>
                                            </div>
                                            <div class="col-xs-4">
                                                {{ $rateValue }} Students
                                            </div>
                                        </div>
                                        @endforeach
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
@include('Partials.move_to_another_lesson')
@stop()
@section('script')
<script src="{{ asset('assets/components/courses.js')}}"></script>
<script src="{{ asset('assets/components/lessons.js')}}"></script>
<script src="{{ asset('assets/js/sortable.js')}}"></script>
<script src="https://player.vimeo.com/api/player.js"></script>
<script src="{{ asset('assets/components/sorting.js') }}"></script>
<script src="{{ asset('assets/components/view-course.js') }}"></script>
@stop()
