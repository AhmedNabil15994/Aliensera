@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/faculties/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Edit Faculty information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/faculties') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(\Helper::checkRules('edit-faculty'))
                                <button type="submit" class="btn btn-round btn-success">Save <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ $data->data->title }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Number Of Years</label>
                                    <input type="number" min="1" class="form-control" placeholder="Enter # Years" name="number_of_years" value="{{ $data->data->years }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>University</label>
                                    <select class="form-control" name="university_id">
                                        <option value="">Select An University..</option>
                                        @foreach($data->universities as $university)
                                        <option value="{{ $university->id }}" {{ $data->data->university_id == $university->id ? 'selected': '' }}>{{ $university->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>Active</label>
                                    <div class="checkbox">
                                        <input type="checkbox" class="flat" name="status" {{ $data->data->status == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" placeholder="Enter Description" name="description">{{ $data->data->description }}</textarea>
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
