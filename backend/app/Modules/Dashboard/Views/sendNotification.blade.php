@extends('Layouts.master')
@section('title', 'Send Notification')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::current() }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>Add Notification information</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <button type="submit" class="btn btn-round btn-success">Send <i class="fa fa-paper-plane"></i></button>
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Body</label>
                                                <textarea class="form-control" placeholder="Enter Body" name="description">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-xs-12">
                                    <div class="row" >
                                        <h3><b>Notification Image</b></h3> <br>
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <h3 class="">Upload new Notification image</h3>
                                                <h6>Upload a different photo...</h6>
                                                <input id="fileUpload" name="image" type="file">
                                            </div>
                                            <hr>
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
    <script src="{{asset('assets/components/send-notifications.js')}}"></script>
@stop
