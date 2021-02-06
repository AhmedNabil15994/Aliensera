@extends('Layouts.master')
@section('title', 'Statistics')
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
                                @if(Input::has('university_id') || Input::has('course_id') || Input::has('faculty_id') || Input::has('year'))
                                    <a href="{{ URL::current() }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
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
                                        <label>University</label>
                                        <select class="form-control" name="university_id">
                                            <option value="">Select University...</option>
                                            @foreach($data->universities as $university)
                                            <option value="{{ $university->id }}" {{ $university->id == Input::get('university_id') ? 'selected' : '' }}>{{ $university->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Faculty</label>
                                        <select class="form-control" name="faculty_id">
                                            <option value="">Select Faculty...</option>
                                            @foreach($data->faculties as $faculty)
                                            <option value="{{ $faculty->id }}" {{ $faculty->id == Input::get('faculty_id') ? 'selected' : '' }}>{{ $faculty->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <input class="form-control" type="number" placeholder="Year" value="{{ Input::get('year') }}">
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
                            <h3>Statistics</h3>
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
                            <th>University</th>
                            <th>Faculty</th>
                            <th>Year</th>
                            <th>Course</th>
                            <th>Student Count</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $key => $value)
                            <tr id="tableRaw{{ $key }}">
                                {{-- {{ dd($value) }} --}}
                                <td>{{ $value['university'] }}</td>
                                <td>{{ $value['faculty'] }}</td>
                                <td>{{ $value['year'] }}</td>
                                <td>{{ $value['course'] }}</td>
                                <td>{{ $value['studentCount'] }} Student</td>
                                <td width="150px" align="center">
                                    <a href="{{ URL::to('/downloadStats/'.$value['course_id']) }}" class="btn btn-success btn-xs"><i class="fa fa-print"></i> Print Users Data </a>
                                    <a href="{{ URL::to('/stats/'.$value['course_id'].'/sendNotification') }}" class="btn btn-primary btn-xs"><i class="fa fa-send"></i> Send Notification </a>
                                </td>
                            </tr>
                        @endforeach
                        @if(count($data->data) == 0)
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
                {{-- @include('Partials.pagination') --}}
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()

@section('script')
    <script src="{{ asset('assets/components/courses.js')}}"></script>
@stop()
