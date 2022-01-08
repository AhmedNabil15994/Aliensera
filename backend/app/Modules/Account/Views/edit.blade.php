@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->name)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/accounts/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Edit Account information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/accounts') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(\Helper::checkRules('edit-account'))
                                <button type="submit" class="btn btn-round btn-success">Save <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-md-4 col-xs-12 col-sm-9">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Name" name="name" value="{{ $data->data->name }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-9">
                                <div class="form-group">
                                    <label>App ID</label>
                                    <input type="text" class="form-control" placeholder="Enter App ID" name="app_id" value="{{ $data->data->app_id }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-9">
                                <div class="form-group">
                                    <label>Client ID</label>
                                    <input type="text" class="form-control" placeholder="Enter Client ID" name="client_id" value="{{ $data->data->client_id }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-9">
                                <div class="form-group">
                                    <label>Client Secret</label>
                                    <input type="text" class="form-control" placeholder="Enter Client Secret" name="client_secret" value="{{ $data->data->client_secret }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-9">
                                <div class="form-group">
                                    <label>Access Token</label>
                                    <input type="text" class="form-control" placeholder="Enter Access Token" name="access_token" value="{{ $data->data->access_token }}">
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
