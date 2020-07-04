@extends('Layouts.master')
@section('title', 'Students Courses')
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
                                @if(Input::has('student_id') || Input::has('course_id') )
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
                                <div class="col-xs-12 col-md-6">
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
                                <div class="col-xs-12 col-md-6">
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
                            <h3>Students Courses<small> Total : {{ $data->data->pagination->total_count }}</small></h3>
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
                            <th>Student</th>
                            <th>Course</th>
                            <th>Details</th>
                            <th>Valid Until</th>
                            <th>Price</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->myCourse->id }}</td>
                                <td>
                                    {{-- <img src="{{ $value->student_image }}" alt=""> --}}
                                    <div class="course-data">
                                        <a href="{{ URL::to('/users/view/'.$value->student_id) }}" target="_blank">{{ $value->student }}</a><br>
                                    </div>
                                </td>
                                <td>
                                    {{-- <img src="{{ $value->myCourse->image }}">  --}}
                                    <div class="course-data">
                                        <a href="{{ URL::to('/courses/view/'.$value->course_id) }}" target="_blank">{{ $value->myCourse->title }}</a><br>
                                        {{-- <p>{{ $value->myCourse->description }}</p> --}}
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                                <td>
                                    Type: {{ $value->myCourse->courseType }} <br>
                                    @if($value->myCourse->course_type == 2)
                                    University: {{ $value->myCourse->university }} <br>
                                    Faculty: {{ $value->myCourse->faculty }} <br>
                                    @endif
                                    Field: {{ $value->myCourse->field }}
                                </td>
                                <td>{{ $value->myCourse->valid_until }}</td>
                                <td>{{ $value->myCourse->price }} $</td>
                                <td width="150px" align="center">
                                    <a href="{{ URL::to('/users/view/' . $value->student_id) }}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Student Profile</a>
                                </td>
                            </tr>
                        @endforeach
                        @if($data->data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="6">No Data Found</td>
                                <td style="display: none;"></td>
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
