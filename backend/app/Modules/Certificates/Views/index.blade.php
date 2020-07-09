@extends('Layouts.master')
@section('title', 'Certificates')
@section('otherhead')
<style type="text/css" media="screen">
    td p{
        color: #777;
    }
    td img{
        display: inline-block;
        float: left;
    }
    div.course-data{
        float: left;
        display: inline;
        width: calc(100% - 60px);
        margin-left: 5px;
        margin-top: 5px;
    }
</style>
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
                                @if(Input::has('student_id') || Input::has('course_id') || Input::has('instructor_id') || Input::has('code') || Input::has('rate') )
                                    <a href="{{ URL::to('/courseStudents') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Code</label>
                                        <input type="text" class="form-control" name="code" placeholder="Code" value="{{ Input::get('code') }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Course</label>
                                        <select name="course_id" class="form-control">
                                            <option value="">Select A Course...</option>
                                            @foreach($data->courses as $course)
                                            <option value="{{ $course->id }}" {{ Input::get('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(IS_ADMIN)
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Instructor</label>
                                        <select name="instructor_id" class="form-control">
                                            <option value="">Select An Instructor...</option>
                                            @foreach($data->instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ Input::get('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <select name="student_id" class="form-control">
                                            <option value="">Select A Student...</option>
                                            @foreach($data->students as $student)
                                            <option value="{{ $student->id }}" {{ Input::get('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <label>Rate (%)</label>
                                        <input type="text" class="form-control" name="rate" placeholder="Rate" value="{{ Input::get('rate') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-8">
                            <h3>Certificates<small> Total : {{ $data->data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-4 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <table id="tableList" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th width="30%">Course</th>
                            <th>Student</th>
                            @if(IS_ADMIN)
                            <th>Instructor</th>
                            @endif
                            <th>Rate</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->code }}</td>
                                <td>
                                    <div class="course-data">
                                        <a href="{{ URL::to('/courses/view/'.$value->course_id) }}" target="_blank">{{ $value->course }}</a><br>
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                                <td>
                                    <div class="course-data">
                                        <a href="{{ URL::to('/users/view/'.$value->student_id) }}" target="_blank">{{ $value->student }}</a><br>
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                                @if(IS_ADMIN)
                                <td>
                                    <div class="course-data">
                                        <a href="{{ URL::to('/users/view/'.$value->instructor_id) }}" target="_blank">{{ $value->instructor }}</a><br>
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                                @endif
                                <td>{{ $value->rate }}</td>
                                <td width="150px" align="center">
                                    <a href="{{ URL::to('/certificates/' . $value->id.'/download') }}" class="btn btn-success btn-xs"><i class="fa fa-download"></i> Download Certificate </a>
                                </td>
                            </tr>
                        @endforeach
                        @if($data->data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                @if(!IS_ADMIN)
                                <td colspan="5">No Data Found</td>
                                @else
                                <td colspan="6">No Data Found</td>
                                <td style="display: none;"></td>
                                @endif
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>    
                        @endif
                        </tbody>
                    </table>
                </div>
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()

@section('script')
    <script src="{{ asset('assets/components/courses.js')}}"></script>
@stop()
