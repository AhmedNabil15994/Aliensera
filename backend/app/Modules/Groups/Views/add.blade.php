@extends('Layouts.master')
@section('title', 'Add Group')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/groups/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>Add Group information</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/groups') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                    @if(\Helper::checkRules('add-group'))
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
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder="Enter Title" name="title" value="{{ old('title') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Permissions</label>
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
                </div>
            </form>
        </div>
    </div>
@stop()

@section('script')
    <script src="{{asset('assets/components/groups.js')}}"></script>
@stop
