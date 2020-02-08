@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('otherhead')
<style type="text/css" media="screen">
    .file-row{
        min-height: 80px;
        clear: both;
    }
    .file-row div{
        width: 50%;
        float: left;
        display: inline-block;
    }
    .file-row div p{
        display: block;
        width: 100%;
        margin: auto;
    }
    .file-row div p.name{
        padding-top: 25px;
    }
    .file-row div p.size{
        padding-top: 17px;
    }
    .file-row div:nth-of-type(4){
        padding-top: 20px;
    }
    .panel_toolbox>li{
        float: right;
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
    h2 span{
        font-size: 16px;
    }
    button.add-question,
    button.clear-question{
        margin-top: 25px;
    }
    button i.fa-play{
        margin-left: 3px;
    }
    .embed-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; } 
    .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
</style>
@endsection
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/lessons/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Edit Lesson information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/lessons') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(\Helper::checkRules('edit-lesson'))
                                <button type="submit" class="btn btn-round btn-success">Save <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            @if(IS_ADMIN == true)
                            <div class="col-md-4">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ $data->data->title }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Active</label>
                                    <div class="checkbox">
                                        <input type="checkbox" class="flat" name="status" {{ $data->data->status == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-md-6">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ $data->data->title }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Valid Until</label>
                                            <input type="text" class="form-control datepicker" placeholder="Enter Date" name="valid_until" value="{{ $data->data->valid_until }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Course</label>
                                            <select class="form-control" name="course_id">
                                                <option value="">Select Course...</option>
                                                @foreach($data->courses as $course)
                                                <option value="{{ $course->id }}" {{ $data->data->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" placeholder="Enter Description" name="description">{{ $data->data->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        @if( $data->data->course_status == 3 && ($data->data->status == 1 || IS_ADMIN == true))
        <div class="col-xs-12">
            <div class="row" >
                <div class="col-xs-12">
                    <div class="row">
                        <div class="row">
                            @php $count = 12; @endphp
                            @if(\Helper::checkRules('add-lesson-video'))
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Lesson Lectures</h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <p>Drag multiple files to the box below for multi upload or click to select files. This is for demonstration purposes only, the files are not uploaded to any server.</p>
                                        <div id="actions" class="row">
                                            <div class="col-lg-7">
                                                <span class="btn btn-success fileinput-button dz-clickable">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>Add Videos...</span>
                                                </span>
                                                <button type="submit" class="btn btn-primary start">
                                                    <i class="glyphicon glyphicon-upload"></i>
                                                    <span>Start Upload</span>
                                                </button>
                                                <button type="reset" class="btn btn-warning cancel">
                                                    <i class="glyphicon glyphicon-ban-circle"></i>
                                                    <span>Cancel Upload</span>
                                                </button>
                                            </div>

                                            <div class="col-lg-5">
                                                <span class="fileupload-process">
                                                    <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress=""></div>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- HTML heavily inspired by http://blueimp.github.io/jQuery-File-Upload/ -->
                                        <div class="table table-striped" class="files" id="previews">
                                            <div id="template" class="file-row">
                                                <div>
                                                    <span class="preview"><img data-dz-thumbnail /></span>
                                                </div>
                                                <div>
                                                    <p class="name" data-dz-name></p>
                                                    <strong class="error text-danger" data-dz-errormessage></strong>
                                                </div>
                                                <div>
                                                    <p class="size" data-dz-size></p>
                                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                                      <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button class="btn btn-primary start">
                                                        <i class="glyphicon glyphicon-upload"></i>
                                                        <span>Start</span>
                                                    </button>
                                                    <button data-dz-remove class="btn btn-warning cancel">
                                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                                        <span>Cancel</span>
                                                    </button>
                                                    <button data-dz-remove class="btn btn-danger delete">
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php $count = 6; @endphp
                            @endif
                            <div class="col-md-{{ $count }} col-sm-{{ $count }} col-xs-12" style="padding-left: 0;">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>List Lectures <span>Total:</span><span class="total_videos"> {{ count((array)$data->data->videos) }}</span></h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content x_content_playlist">
                                        @if(!empty((array)$data->data->videos))
                                        <div class="playlist">
                                            <div class="row">
                                                <div class="col-xs-1">No#</div>
                                                <div class="col-xs-3">Lecture</div>
                                                <div class="col-xs-2 text-center">Duration</div>
                                                <div class="col-xs-2 text-center">Size</div>
                                                <div class="col-xs-2 text-center">Free</div>
                                                <div class="col-xs-2 text-center">Action</div>
                                            </div>
                                            @foreach($data->data->videos as $key => $video)
                                            <div class="row results" id="results{{ $video->id }}">
                                                <div class="col-xs-1">{{ $key+1 }}</div>
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
                                    @include('Partials.videoPlayer')
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if(\Helper::checkRules('add-lesson-question'))
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Lesson Quiz Questions</h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="row" >
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Question</label>
                                                    <input type="text" class="form-control" placeholder="Enter Question" name="question">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Answer A</label>
                                                    <input type="text" class="form-control" placeholder="Enter Answer A" name="answer_a">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Answer B</label>
                                                    <input type="text" class="form-control" placeholder="Enter Answer B" name="answer_b">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Answer C</label>
                                                    <input type="text" class="form-control" placeholder="Enter Answer C" name="answer_c">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Answer D</label>
                                                    <input type="text" class="form-control" placeholder="Enter Answer D" name="answer_d">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Correct Answer</label>
                                                    <select class="form-control" name="correct_answer">
                                                        <option value="">Select Correct Answer</option>
                                                        <option value="a">Answer A</option>
                                                        <option value="b">Answer B</option>
                                                        <option value="c">Answer C</option>
                                                        <option value="d">Answer D</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group text-right">
                                                    <button class="btn btn-sm btn-warning clear-question"><i class="fa fa-refresh"></i></button>
                                                    <button class="btn btn-sm btn-success add-question"><i class="fa fa-plus"></i> Add</button>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0;">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>List Questions <span>Total:</span><span class="total_questions"> {{ count((array)$data->data->questions) }}</span></h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content x_content_questions">
                                        @if(!empty((array)$data->data->questions))
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
                                            @foreach($data->data->questions as $key => $question)
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
                                    <div class="modal fade" id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>        
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item" src="" id="video"  allowscriptaccess="always" allow="autoplay"></iframe>
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
        </div>
        <hr>
        @endif
    </div>
</div>
@stop()
@section('script')
<script src="{{ asset('assets/components/lessons.js') }}" type="text/javascript"></script>
<script src="https://player.vimeo.com/api/player.js"></script>
@endsection