@extends('Layouts.master')
@section('title', 'Add User')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/users/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>Add Users information</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/users') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                    @if(\Helper::checkRules('add-user'))
                                    <button type="submit" class="btn btn-round btn-success">Create <i class="fa fa-plus"></i></button>
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
                                                <input type="text" class="form-control" placeholder="Enter First name" name="first_name" value="{{ old('first_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" placeholder="Enter Last Name" name="last_name" value="{{ old('last_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" placeholder="Enter Email" name="email" value="{{ old('email') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" placeholder="Enter New Password" name="password" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Enter Address" name="address" value="{{ old('address') }}" required>
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
                                                        <option value="{{ $groupValue->id }}" {{ $groupValue->id == old('group_id') ? "selected=selected" : ''  }}>{{ $groupValue->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Active</label>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="flat" name="active" {{ old('active') ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" class="form-control" placeholder="Enter Phone" name="phone" value="{{ old('phone') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select name="gender" class="form-control" required>
                                                    <option value="">Choose..</option>
                                                    <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>Male</option>
                                                    <option value="2" {{ old('gender') == 2 ? 'selected' : '' }}>Female</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <h3><b>Extra Permissions</b></h3> <br>
                                            <select id='custom-headers' class="searchable" name="permissions[]" multiple='multiple'>
                                                @forelse($data->permissions as $permissionKey => $permissionValue)
                                                    <option value="{{$permissionValue}}">{{$permissionValue}}</option>
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
