@extends('Layouts.master')
@section('title', 'Users')

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
                                @if(Input::has('id') || Input::has('name') || Input::has('email') || Input::has('phone') || Input::has('group_id') || Input::has('course_id'))
                                    <a href="{{ URL::to('/users') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>ID</label>
                                    <input type="number" class="form-control" name="id" placeholder="ID" value="{{ Input::get('id') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ Input::get('name') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ Input::get('email') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="phone" placeholder="Phone" value="{{ Input::get('phone') }}">
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>Group</label>
                                    <select class="form-control select2" name="group_id">
                                        <option value="0">Select Group</option>
                                        @foreach($data->groups as $group)
                                            <option value="{{ $group->id }}" {{ Input::get('group_id') == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-4">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="form-control select2" name="course_id">
                                        <option value="0">Select Course</option>
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
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>Users<small> Total : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-user'))
                                    <a href="{{URL::to('/users/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> Add New</a>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Active</th>
                            @if(Input::get('course_id') != 0)
                            <th>View Duration</th>
                            @endif
                            <th>Certificate Logo (Instructors Only)</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->name }}</td>
                                <td width="3%" align="left"><span class="btn {{ $value->is_active == 1 ? "btn-success" : "btn-danger" }} btn-xs"> {{ $value->active }}</span></td>
                                @if(Input::get('course_id') != 0)
                                <td align="left">
                                    {{ $value->viewDuration }} of {{ \App\Models\Course::getData(\App\Models\Course::getOne(Input::get('course_id')),'course')->allTime }}
                                </td>
                                @endif
                                <td>
                                    @if($value->logo != '')
                                    <img src="{{ $value->logo }}" alt="">
                                    @else
                                    -----
                                    @endif
                                </td>
                                <td class="actions" width="15%" align="left">
                                    @if(\Helper::checkRules('edit-user'))
                                        <a href="{{ URL::to('/users/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                    @endif

                                    @if($value->group_id != 1)
                                        {{-- <a href="{{ URL::to('/messages/with/' . $value->id) }}" class="btn btn-dark btn-xs"><i class="fa fa-comments"></i> Say Hello </a> --}}

                                        @if(\Helper::checkRules('view-user'))
                                            <a href="{{ URL::to('/users/view/' . $value->id) }}" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> View </a>
                                        @endif
                                    @endif

                                    @if($value->group_id == 3)
                                        <a href="{{ URL::to('/users/unsetDevices/' . $value->id) }}" class="btn btn-dark btn-xs"><i class="fa fa-times"></i> Unset Devices </a>
                                    @endif

                                    @if(\Helper::checkRules('delete-user') && $value->deleted_by == null && $value->group_id != 1)
                                        <a onclick="deleteUser('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                    @endif
                                    @if(\Helper::checkRules('restore-user') && $value->deleted_by != null)
                                        <a onclick="restoreUser('{{ $value->id }}')" class="btn btn-primary btn-xs"><i class="fa fa-refresh"></i> Restore </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="6">No Data Found</td>
                                <td style="display: none;"></td>
                                @if(Input::get('course_id') != 0)
                                <td style="display: none;"></td>
                                @endif
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

@stop

@section('script')
    <script src="{{ asset('assets/components/users.js')}}"></script>
@stop()
