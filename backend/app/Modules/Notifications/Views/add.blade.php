@extends('Layouts.master')
@section('title', 'Send Notification')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/notifications/create/') }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
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
                            @if(IS_ADMIN)
                            <div class="row" >
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Course Type</label>
                                                <select name="course_type" class="form-control">
                                                    <option value="">Select A Course Type...</option>
                                                    <option value="1">General</option>
                                                    <option value="2">University & Faculty</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>University</label>
                                                <select name="university_id" class="form-control">
                                                    <option value="">Select An University...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Faculty</label>
                                                <select name="faculty_id" class="form-control">
                                                    <option value="">Select A Faculty...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Year</label>
                                                <select name="year" class="form-control">
                                                    <option value="">Select Year...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Field</label>
                                                <select name="field_id" class="form-control">
                                                    <option value="">Select A Field...</option>
                                                    @foreach($data->fields as $field)
                                                    <option value="{{ $field->id }}">{{ $field->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Course</label>
                                                <select name="course_id" class="form-control">
                                                    <option value="">Select A Course...</option>
                                                    @foreach($data->courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Instructor</label>
                                                <select name="course_id" class="form-control">
                                                    <option value="">Select An Instructor...</option>
                                                    @foreach($data->instructors as $instructor)
                                                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                                    @endforeach
                                                </select>
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
                            @else
                            <div class="row" >
                                <div class="col-md-6 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12 col-sm-6">
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label>Course</label>
                                                <select name="course_id" class="form-control">
                                                    <option value="">Select A Course...</option>
                                                    @foreach($data->courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                                    @endforeach
                                                </select>
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
                            @endif
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
