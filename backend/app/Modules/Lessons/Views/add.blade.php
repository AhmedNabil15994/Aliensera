@extends('Layouts.master')
@section('title', 'Add Lesson')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/lessons/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>Add Lesson information</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/lessons') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                    @if(\Helper::checkRules('add-lesson'))
                                    <button type="submit" class="btn btn-round btn-success">Create <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                               <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Valid Until</label>
                                            <input type="text" class="form-control datepicker" placeholder="Enter Date" name="valid_until" value="{{ old('valid_until') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Course</label>
                                        <select class="form-control" name="course_id">
                                            <option value="">Select Course...</option>
                                            @foreach($data->courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(!IS_ADMIN)
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Questions Sort</label>
                                        <div>
                                            <p class="gender">
                                                In Order <input type="radio" class="flat" name="questions_sort" {{ old('questions_sort') == 0 ? 'checked' : ''  }} value="0"/>
                                                Random <input type="radio" class="flat" name="questions_sort" {{ old('questions_sort') == 1 ? 'checked' : ''  }} value="1"/>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Quiz Duration (In Minutes)</label>
                                        <input type="text" class="form-control" placeholder="Enter Quiz Duration (In Minutes)" name="quiz_duration" value="{{ old('quiz_duration') }}">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label>Active At</label>
                                        <input type="text" class="form-control" placeholder="Active At" name="active_at" value="{{ old('active_at') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Pass Quiz To View Next Lessons</label>
                                        <div class="checkbox">
                                            <input type="checkbox" class="flat" name="pass_quiz" {{ old('pass_quiz') == 1 ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" placeholder="Enter Description" name="description">{{ old('description') }}</textarea>
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
