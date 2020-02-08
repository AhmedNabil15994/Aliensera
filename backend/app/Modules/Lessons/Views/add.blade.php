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
                                @if(IS_ADMIN == true)
                                <div class="col-md-4">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Active</label>
                                        <div class="checkbox">
                                            <input type="checkbox" class="flat" name="status" {{ old('status') == 1 ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
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
                                                <input type="text" class="form-control datepicker" placeholder="Enter Date" name="valid_until" value="{{ old('valid_until') }}">
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
                                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
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
                                                <textarea class="form-control" placeholder="Enter Description" name="description">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
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
