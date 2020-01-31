@extends('Layouts.master')
@section('title', 'Lessons')
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
                                    <a href="{{ URL::to('/requests') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
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
                                <div class="col-xs-6">
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
                                <div class="col-xs-6">
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
                                <div class="col-xs-6">
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
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Student Requests<small> Total : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-md-6">
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
                            <th>Student</th>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Status</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->student->name }}</td>
                                <td>{{ $value->course->title }}</td>
                                <td>{{ $value->instructor->name }}</td>
                                <td>{{ $value->status == 1 ? 'Active' : ($value->status == 0 ? 'In Active' : 'Student Sent Request') }}</td>
                                <td width="150px" align="center">
                                    @if(\Helper::checkRules('edit-student-request'))
                                        <a href="{{ URL::to('/requests/update/' . $value->id . '/1') }}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i> Accept </a>
                                        <a href="{{ URL::to('/requests/update/' . $value->id . '/0') }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Refuse </a>
                                    @endif
                                    @if(\Helper::checkRules('delete-student-request'))
                                        <a onclick="deleteRequest('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="5">No Data Found</td>
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
    <script src="{{ asset('assets/components/studentRequests.js')}}"></script>
@stop()
