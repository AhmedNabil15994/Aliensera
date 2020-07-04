@extends('Layouts.master')
@section('title', 'Requests')
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
                                <div class="col-xs-12 col-sm-6 col-md-6">
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
                                <div class="col-xs-12 col-sm-6 col-md-6">
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
                                <div class="col-xs-12 col-sm-6 col-md-6">
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
                                <div class="col-xs-12 col-sm-6 col-md-6">
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
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-8">
                            <h3>Student Requests<small> Total : {{ $data->pagination->total_count }}</small></h3>
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
                            <th width="25%">Student</th>
                            <th width="25%">Course</th>
                            @if(IS_ADMIN)
                            <th width="25%">Instructor</th>
                            @endif
                            <th>Status</th>
                            @if(IS_ADMIN || \Helper::checkRules('edit-student-request'))
                            <th style="padding-left: 50px">Actions</th>                            
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>
                                    {{-- <img src="{{ $value->student->image }}" alt=""> --}}
                                    <div class="course-data">
                                        <a href="{{ URL::to('/users/view/'.$value->student_id) }}" target="_blank">{{ $value->student->name }}</a>
                                    </div>
                                </td>

                                <td>
                                    {{-- <img src="{{ $value->course->image }}" alt=""> --}}
                                    <div class="course-data">
                                        <a href="{{ URL::to('/courses/view/'.$value->course_id) }}" target="_blank">{{ $value->course->title }}</a>
                                        {{-- <p>{{ $value->course->description }}</p> --}}
                                    </div>
                                </td>
                                @if(IS_ADMIN)
                                <td>
                                    {{-- <img src="{{ $value->instructor->image }}" alt=""> --}}
                                    <div class="course-data">
                                        <a href="{{ URL::to('/users/view/'.$value->instructor_id) }}" target="_blank">{{ $value->instructor->name }}</a>
                                    </div>
                                </td>
                                @endif
                                <td>{{ $value->status == 1 ? 'Active' : ($value->status == 0 ? 'In Active' : 'Student Sent Request') }}</td>
                                @if(IS_ADMIN || \Helper::checkRules('edit-student-request'))
                                <td align="center">
                                    @if(\Helper::checkRules('edit-student-request'))
                                        @if($value->status != 1)
                                        <a href="{{ URL::to('/requests/update/' . $value->id . '/1') }}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i> Accept </a>
                                        @endif
                                        @if($value->status != 0)
                                        <a href="{{ URL::to('/requests/update/' . $value->id . '/0') }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Refuse </a>
                                        @endif
                                    @endif
                                    @if(\Helper::checkRules('delete-student-request'))
                                        <a onclick="deleteRequest('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                @if(IS_ADMIN)
                                <td colspan="5">No Data Found</td>
                                <td style="display: none;"></td>
                                @else
                                <td colspan="4">No Data Found</td>
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
    <script src="{{ asset('assets/components/studentRequests.js')}}"></script>
@stop()
