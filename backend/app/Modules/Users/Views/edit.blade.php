@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->display_name)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/users/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Edit Users information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/users') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(\Helper::checkRules('edit-user'))
                                <button type="submit" class="btn btn-round btn-success">Save <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-md-6">
                                <div class="row" >
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" placeholder="Enter First name" name="first_name" value="{{ $data->data->first_name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Last Name" name="last_name" value="{{ $data->data->last_name }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" placeholder="Enter Email" name="email" value="{{ $data->data->email }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" placeholder="Enter New Password" name="password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row" >
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Group Name</label>
                                            <select name="group_id" class="form-control" required>
                                                <option value="">Choose..</option>
                                                @foreach($data->groups as $groupKey => $groupValue)
                                                    <option value="{{ $groupValue->id }}" {{ $groupValue->id == $data->data->group_id ? "selected=selected" : ''  }}>{{ $groupValue->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Active</label>
                                            <div class="checkbox">
                                                <input type="checkbox" class="flat" {{ $data->data->active == 1 ? "checked" : ""  }} name="active">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" placeholder="Enter Phone" name="phone" value="{{ $data->data->phone}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h3><b>Extra Permissions</b></h3> <br>
                                        <select id='custom-headers' class="searchable" name="permissions[]" multiple='multiple'>
                                            @forelse($data->permissions as $permissionKey => $permissionValue)
                                                @if(!empty($data->data->extra_rules))
                                                <option value="{{$permissionValue}}" {{ in_array($permissionValue, $data->data->extra_rules) ? 'selected' : '' }} >{{$permissionValue}}</option>
                                                @else
                                                <option value="{{$permissionValue}}">{{$permissionValue}}</option>
                                                @endif
                                            @empty
                                                <option value="0" disabled selected>-- No Data -- </option>
                                            @endforelse
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
</div>
@stop()

@section('script')
    <script src="{{URL::asset('assets/components/users.js')}}"></script>
@stop
