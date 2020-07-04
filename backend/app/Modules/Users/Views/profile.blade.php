@extends('Layouts.master')
@section('title', $data->id . ' - ' . $data->name)
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/vendors/switchery/dist/switchery.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/profile.css') }}">
@endsection
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Profile</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                        <div class="profile_img">
                            <div id="crop-avatar">
                                <!-- Current avatar -->
                                <img class="img-responsive avatar-view" src="{{ $data->image }}" alt="Avatar" title="Change the avatar">
                            </div>
                        </div>
                        <h3>{{ $data->name }}</h3>
                        <ul class="list-unstyled user_data">
                            <li><i class="fa fa-map-marker user-profile-icon"></i> {{$data->address}}</li>
                            <li><i class="fa fa-user"></i> {{ $data->gender == 1 ? 'Male' : 'Female' }}</li>
                            <li class="m-top-xs"><i class="fa fa-phone"></i> {{ $data->phone }}</li>
                            <li class="m-top-xs"><i class="fa fa-envelope"></i> {{ $data->email }}</li>
                        </ul>
                        <a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Change Image</a>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><small>User Information</small></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br>
                                <form class="form-horizontal form-label-left" method="post" action="{{ URL::to('/updateProfile') }}" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <input type="text" class="form-control has-feedback-left" value="{{ $data->first_name }}" name="first_name" placeholder="First Name">
                                            <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <input type="text" class="form-control has-feedback-left" value="{{ $data->last_name }}" name="last_name" placeholder="Last Name">
                                            <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input type="text" class="form-control has-feedback-left" value="{{ $data->email }}" readonly="readonly" placeholder="Email">
                                            <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input type="password" class="form-control has-feedback-left" name="password" placeholder="Password">
                                            <span class="fa fa-shield form-control-feedback left" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Re-type Password</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input type="password" class="form-control has-feedback-left" name="password_confirmation" placeholder="Re-type Password">
                                            <span class="fa fa-shield form-control-feedback left" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Phone</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input type="text" class="form-control has-feedback-left" value="{{ $data->phone }}" name="phone" placeholder="Phone">
                                            <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input type="text" class="form-control has-feedback-left" value="{{ $data->address }}" name="address" placeholder="Address">
                                            <span class="fa fa-map-marker form-control-feedback left" aria-hidden="true"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <p class="gender">
                                                Male <input type="radio" class="flat" name="gender" id="genderM" value="1" {{ $data->gender == 1 ? 'checked' : '' }}/>
                                                Female <input type="radio" class="flat" name="gender" id="genderF" value="2" {{ $data->gender == 2 ? 'checked' : '' }}/>
                                            </p>
                                        </div>
                                    </div>
                                    <input type="file" name="image" class="hidden">
                                    @if(!IS_ADMIN)
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Show Students IDs</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <label>
                                              <input type="checkbox" name="show" class="js-switch" {{ $data->show_student_id == 1 ? 'checked' : '' }} /> On
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <button type="button" class="btn btn-primary">Cancel</button>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop()

@section('script')
<script src="{{ asset('assets/components/profile.js') }}" type="text/javascript"></script>
<script src="{{ asset('/assets/vendors/switchery/dist/switchery.min.js') }}"></script>

@stop
