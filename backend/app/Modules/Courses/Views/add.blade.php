@extends('Layouts.master')
@section('title', 'Add Course')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/courses/create/') }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>Add Course information</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/courses') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                    @if(\Helper::checkRules('add-course'))
                                    <button type="submit" class="btn btn-round btn-success">Create <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-md-4">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(IS_ADMIN == true)
                                <div class="col-md-4">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Instructor</label>
                                                <select name="instructor_id" class="form-control">
                                                    <option value="">Select An Instructor...</option>
                                                    @foreach($data->instructors as $instructor)
                                                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="">Select A Status...</option>
                                                    <option value="1">Instructor Sent Request</option>
                                                    <option value="2">Request Refused</option>
                                                    <option value="3">Active</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-4">
                                    <div class="row" >
                                        <div class="col-md-12">
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
                                <div class="col-md-4">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>University</label>
                                                <select name="university_id" class="form-control">
                                                    <option value="">Select An University...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Faculty</label>
                                                <select name="faculty_id" class="form-control">
                                                    <option value="">Select A Faculty...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row" >
                                        <div class="col-md-12">
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
                                <div class="col-md-3">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Year</label>
                                                <input type="text" class="form-control" placeholder="Enter Year" name="year" value="{{ old('year') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Price</label>
                                                <input type="text" class="form-control" placeholder="Enter Price" name="price" value="{{ old('price') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Valid Until</label>
                                                <input type="text" class="form-control datepicker" placeholder="Enter Date" name="valid_until" value="{{ old('valid_until') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>What Student Learn</label>
                                                <textarea class="form-control" placeholder="What Student Learn" name="what_learn">{{ old('what_learn') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Requirements</label>
                                                <textarea class="form-control" placeholder="Enter Requirements" name="requirements">{{ old('requirements') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" placeholder="Enter Description" name="description">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                @if(\Helper::checkRules('add-course-image'))
                                <div class="col-md-12">
                                    <div class="row" >
                                        <h3><b>Course Image</b></h3> <br>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h3 class="">Upload new Course image</h3>
                                                <h6>Upload a different photo...</h6>
                                                <input id="fileUpload" name="image" type="file">
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop()
@section('script')
    <script src="{{ asset('assets/components/courses.js')}}"></script>
@stop()
