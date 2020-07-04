@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/universities/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Edit University information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/universities') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(\Helper::checkRules('edit-university'))
                                <button type="submit" class="btn btn-round btn-success">Save <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-md-4 col-xs-9 col-sm-8">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ $data->data->title }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-3 col-sm-4">
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
