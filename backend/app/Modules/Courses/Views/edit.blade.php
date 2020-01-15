@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('otherhead')
<style type="text/css" media="screen">
    .feedback{
        padding-left: 25px;
    }
    div.images{
        border-right: 1px solid #DDD;
    }
    h3 span{
        font-size: 14px;
        color: #777;
    }
</style>
@endsection
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/courses/update/' . $data->data->id) }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Edit Course information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/courses') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(\Helper::checkRules('edit-course'))
                                <button type="submit" class="btn btn-round btn-success">Save <i class="fa fa-check"></i></button>
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
                                            <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ $data->data->title }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Instructor</label>
                                            <select name="instructor_id" class="form-control">
                                                <option value="">Select An Instructor...</option>
                                                @foreach($data->instructors as $instructor)
                                                <option value="{{ $instructor->id }}" {{ $data->data->instructor_id == $instructor->id ? 'selected' : '' }}>{{ $instructor->name }}</option>
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
                                                <option value="0" {{ $data->data->status == 0 ? 'selected' : '' }}>New</option>
                                                <option value="1" {{ $data->data->status == 1 ? 'selected' : '' }}>Instructor Sent Request</option>
                                                <option value="2" {{ $data->data->status == 2 ? 'selected' : '' }}>Request Refused</option>
                                                <option value="3" {{ $data->data->status == 3 ? 'selected' : '' }}>Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Course Type</label>
                                            <select name="course_type" class="form-control">
                                                <option value="">Select A Course Type...</option>
                                                <option value="1" {{ $data->data->course_type == 1 ? 'selected' : '' }}>General</option>
                                                <option value="2" {{ $data->data->course_type == 2 ? 'selected' : '' }}>University & Faculty</option>
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
                                            <select name="university_id" class="form-control" {{ $data->data->course_type == 1 ? 'disabled':'' }}>
                                                <option value="">Select An University...</option>
                                                @foreach($data->universities as $university)
                                                <option value="{{ $university->id }}" {{ $data->data->university_id == $university->id ? 'selected' : '' }}>{{ $university->title }}</option>
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
                                            <label>Faculty</label>
                                            <select name="faculty_id" class="form-control" {{ $data->data->course_type == 1 ? 'disabled':'' }}>
                                                <option value="">Select A Faculty...</option>
                                                @foreach($data->faculties as $faculty)
                                                <option value="{{ $faculty->id }}" {{ $data->data->faculty_id == $faculty->id ? 'selected' : '' }}>{{ $faculty->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Field</label>
                                            <select name="field_id" class="form-control">
                                                <option value="">Select A Field...</option>
                                                @foreach($data->fields as $field)
                                                <option value="{{ $field->id }}" {{ $data->data->field_id == $field->id ? 'selected' : '' }}>{{ $field->title }}</option>
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
                                            <label>Price</label>
                                            <input type="text" class="form-control" placeholder="Enter Price" name="price" value="{{ $data->data->price }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Valid Until</label>
                                            <input type="text" class="form-control datepicker" placeholder="Enter Date" name="valid_until" value="{{ $data->data->valid_until }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" placeholder="Enter Description" name="description">{{ $data->data->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-xs-12 col-md-12 images">
                                <div class="row" >
                                    <h3><b>Course Image</b></h3> <br>
                                    @if(\Helper::checkRules('add-course-image'))
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h3 class="">Upload new course image</h3>
                                                <h6>Upload a different photo...</h6>
                                                <input id="fileUpload" name="image" type="file">
                                            </div>
                                            <hr>
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        <div class="imagesHolder">
                                            <h3 class="">List images</h3>
                                            @if($data->data->image != '')
                                                <figure id="imgRaw{{$data->data->id}}">
                                                    @if(\Helper::checkRules('delete-course-image'))
                                                        <a onclick="deleteImage('{{$data->data->id}}')" class="remove fa fa-remove btn btn-xs"></a>
                                                    @endif
                                                    <a href="{{ $data->data->image }}" class="fancybox" rel="gallery" title="">
                                                        <img src="{{ $data->data->image }}" class="avatar" alt="avatar" style="width: 200px;height: 200px;">
                                                    </a>
                                                </figure>
                                            @else
                                                <div class="empty">
                                                    <img src="{{ URL::to('/assets/images/not-available.jpg') }}" class="avatar img-circle" alt="avatar" style="width: 250px;height: 250px;">
                                                </div>
                                            @endif
                                        </div>
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
    <script src="{{ URL::asset('assets/components/courses.js')}}"></script>
@stop()
