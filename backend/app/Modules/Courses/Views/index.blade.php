@extends('Layouts.master')
@section('title', 'Courses')
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
                                @if(Input::has('title') || Input::has('instructor_id') || Input::has('status') || Input::has('course_type') || Input::has('university_id') || Input::has('faculty') || Input::has('status') )
                                    <a href="{{ URL::to('/courses') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{ Input::get('title') }}">
                                    </div>
                                </div>
                                @if(IS_ADMIN == true)
                                <div class="col-xs-6 col-md-4">
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
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">Select A Status...</option>
                                            <option value="0" {{ Input::has('status') && Input::get('status') == 0 ? 'selected' : '' }}>New</option>
                                            <option value="1" {{ Input::get('status') == 1 ? 'selected' : '' }}>Instructor Sent Request</option>
                                            <option value="2" {{ Input::get('status') == 2 ? 'selected' : '' }}>Request Refused</option>
                                            <option value="3" {{ Input::get('status') == 3 ? 'selected' : '' }}>Active</option>
                                            <option value="4" {{ Input::get('status') == 4 ? 'selected' : '' }}>Expired</option>
                                            <option value="5" {{ Input::get('status') == 5 ? 'selected' : '' }}>Instructor Sent Upgrade Request</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group">
                                        <label>Course Type</label>
                                        <select name="course_type" class="form-control">
                                            <option value="">Select A Course Type...</option>
                                            <option value="1" {{ Input::get('course_type') == 1 ? 'selected' : '' }}>General</option>
                                            <option value="2" {{ Input::get('course_type') == 2 ? 'selected' : '' }}>University & Faculty</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group">
                                        <label>University</label>
                                        <select name="university_id" class="form-control">
                                            <option value="">Select An University...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group">
                                        <label>Faculty</label>
                                        <select name="faculty_id" class="form-control">
                                            <option value="">Select A Faculty...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4">
                                    <div class="form-group">
                                        <label>Field</label>
                                        <select name="field_id" class="form-control">
                                            <option value="">Select A Field...</option>
                                            @foreach($data->fields as $field)
                                            <option value="{{ $field->id }}" {{ Input::get('field_id') == $field->id ? 'selected' : '' }}>{{ $field->title }}</option>
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
                        <div class="col-xs-6">
                            <h3>Courses<small> Total : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-course'))
                                    <a href="{{URL::to('/courses/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> Add New</a>
                                @endif
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
                            <th width="3%">ID</th>
                            <th>Title</th>
                            <th>Course Type</th>
                            <th width="10%">University</th>
                            <th width="10%">Faculty</th>
                            <th>Field</th>
                            <th width="8%">Valid Until</th>
                            <th width="5%">Price</th>
                            @if(!IS_ADMIN)
                            <th>Used Quota</th>
                            @endif
                            <th>Active Students</th>
                            <th>Status</th>
                            <th align="center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td>{{ $value->id }}</td>
                                <td>{{-- <img src="{{ $value->image }}"> --}}{{ $value->title }}</td>
                                <td>{{ $value->course_type == 1 ? 'General' : 'University & Faculty' }}</td>
                                <td>{{ $value->university }}</td>
                                <td>{{ $value->faculty }}</td>
                                <td>{{ $value->field }}</td>
                                <td>{{ $value->valid_until }}</td>
                                <td>{{ $value->price }} LE</td>
                                @if(!IS_ADMIN)
                                <td>{{ $value->quota }} GB</td>
                                @endif
                                <td>{{ $value->studentCount }}</td>
                                <td>{!! $value->statusLabel !!}</td>
                                <td>
                                    @if(\Helper::checkRules('edit-course'))
                                        <a href="{{ URL::to('/courses/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                    @endif
                                    @if(\Helper::checkRules('view-course'))
                                        <a href="{{ URL::to('/courses/view/' . $value->id) }}" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> View </a>
                                        <a href="{{ URL::to('/courses/discussion/' . $value->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-comments"></i> Discussion </a>
                                    @endif
                                    @if(\Helper::checkRules('delete-course') && $value->deleted_by == null)
                                        <a onclick="deleteCourse('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                    @endif
                                    @if(\Helper::checkRules('restore-course') && $value->deleted_by != null)
                                        <a onclick="restoreCourse('{{ $value->id }}')" class="btn btn-primary btn-xs"><i class="fa fa-refresh"></i> Restore </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                @if(!IS_ADMIN)
                                <td colspan="11">No Data Found</td>
                                @else
                                <td colspan="12">No Data Found</td>
                                <td style="display: none;"></td>
                                @endif
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
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
