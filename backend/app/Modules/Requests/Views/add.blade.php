@extends('Layouts.master')
@section('title', 'Assign Student To Course')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/requests/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>Assign Student To Course</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/requests') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                    @if(\Helper::checkRules('add-request'))
                                    <button type="submit" class="btn btn-round btn-success">Assign <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-xs-12 col-sm-6 col-md-4">
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
                                <div class="col-xs-12 col-sm-6 col-md-4">
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
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="">Select Status...</option>
                                            <option value="0">In Active</option>
                                            <option value="1">Active</option>
                                            <option value="2">Student Sent Request</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop()

@section('script')
    <script src="{{ asset('assets/components/lessons.js')}}"></script>
@stop()
