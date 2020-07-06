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
                                        <label>Instructor</label>
                                        <select name="instructor_id" class="form-control">
                                            <option value="">Select An Instructor...</option>
                                            @foreach($data->instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ Input::get('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
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
                            <h3>Upgrade Requests<small> Total : {{ $data->data->pagination->total_count }}</small></h3>
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
                            <th width="30%">Course</th>
                            <th>Instructor</th>
                            <th>Estimated Cost</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>
                                    <div class="course-data">
                                        <a href="{{ URL::to('/courses/view/'.$value->id) }}" target="_blank">{{ $value->title }}</a><br>
                                        <p>{{ $value->description }}</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                                <td>
                                    <div class="course-data">
                                        <a href="{{ URL::to('/users/view/'.$value->instructor_id) }}" target="_blank">{{ $value->instructor }}</a><br>
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                                <td>
                                    @if($value->instructor_price != null)
                                    Start Date: <span class="cost">{{ @$value->instructor_price->updated_start_date }}</span> <br>
                                    End Date: <span class="cost">{{ @$value->instructor_price->updated_end_date }}</span> <br>
                                    Upload Space: <span class="cost">{{ @$value->instructor_price->updated_upload_space }} GB</span><br>
                                    Upload Cost: <span class="cost">{{ @$value->instructor_price->updated_upload_cost }} LE</span><br>
                                    Students Approvals: <span class="cost">{{ @$value->instructor_price->updated_approval_number }}</span><br>
                                    Approvals Cost: <span class="cost">{{ @$value->instructor_price->updated_approval_cost }} LE</span><br>
                                    @endif
                                </td>
                                <td width="150px" align="center">
                                    <a href="{{ URL::to('/courses/upgrade/' . $value->id.'/1') }}" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Accept Upgrade </a>
                                    <a href="{{ URL::to('/courses/upgrade/' . $value->id.'/2') }}" class="btn btn-dark btn-xs"><i class="fa fa-times"></i> Refuse Upgrade </a>
                                </td>
                            </tr>
                        @endforeach
                        @if($data->data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="4">No Data Found</td>
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
