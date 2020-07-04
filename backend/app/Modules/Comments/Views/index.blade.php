@extends('Layouts.master')
@section('title', 'Comments')
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/comments-index.css') }}">
@endsection
@section('content')
    <div class="row">
        <form method="get" action="{{ URL::current() }}">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <strong>Filter By</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" style="width:110px;"><i class="fa fa fa-search"></i> Search ..</button>
                                @if(Input::has('student_id') || Input::has('course_id') || Input::has('instructor_id') || Input::has('status'))
                                    <a href="{{ URL::to('/comments') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-sm-6 col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <select class="form-control" name="student_id">
                                            <option value="">Select Student...</option>
                                            @foreach($data->students as $student)
                                            <option value="{{ $student->id }}" {{ $student->id == Input::get('student_id') ? 'selected' : '' }}>{{ $student->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Course</label>
                                        <select class="form-control" name="course_id">
                                            <option value="">Select Course...</option>
                                            @foreach($data->courses as $course)
                                            <option value="{{ $course->id }}" {{ $course->id == Input::get('course_id') ? 'selected' : '' }}>{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(IS_ADMIN)
                                <div class="col-sm-6 col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Instructor</label>
                                        <select class="form-control" name="instructor_id">
                                            <option value="">Select Instructor...</option>
                                            @foreach($data->instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ $instructor->id == Input::get('instructor_id') ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>Comments<small> Total : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content">
                  <ul class="messages messages2">
                    @foreach($data->data as $comment)
                    <li id="tableRaw{{ $comment->id }}">
                        <a href="{{ URL::to('/videos/'.$comment->video_id.'/comments') }}">
                            <img src="{{ $comment->image }}" class="avatar" alt="Avatar">
                            <div class="message_wrapper">
                                <h4 class="heading">{!! $comment->creator !!} <span class="my-title">On: {{ $comment->video_title }} ({{ $comment->course }})</span> <span class="time pull-right">{{ $comment->created_at }}</span></h4>
                                <p class="message">{{ $comment->comment }}</p>
                                <p class="url"></p>
                            </div>
                        </a>
                    </li>
                    <div class="clearfix"></div>
                    @endforeach
                </ul>
                </div>
                
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()

@section('script')
    <script src="{{ asset('assets/components/studentRequests.js')}}"></script>
@stop()
