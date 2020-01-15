@extends('Layouts.master')
@section('title', 'Variables')
@section('content')
    <div class="row">
        <form method="get" action="{{ URL::current() }}">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <strong>Filter By</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <button type="submit" class="btn btn-primary" style="width:110px;"><i class="fa fa fa-search"></i> Search ..</button>
                                 @if(Input::has('key') || Input::has('value'))
                                    <a href="{{ URL::to('/variables') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Key</label>
                                        <input type="text" class="form-control" name="key" placeholder="Key" value="{{ Input::get('key') }}">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Value</label>
                                        <input type="text" class="form-control" name="value" placeholder="Value" value="{{ Input::get('value') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if(\Helper::checkRules('add-variable'))
        <div class="row" >
        <form method="post" action="{{ URL::to('/variables/create/') }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Add Variable information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                @if(\Helper::checkRules('add-variable'))
                                    <button type="submit" class="btn btn-round btn-success">Create <i class="fa fa-plus"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Key</label>
                                        <input type="text" class="form-control" name="key" placeholder="Key" value="{{ Input::get('key') }}">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label>Value</label>
                                        <input type="text" class="form-control" name="value" placeholder="Value" value="{{ Input::get('value') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Variables<small> Total : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <table id="tableList" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Key</th>
                            <th>value</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->key }}</td>
                                <td>{{ $value->value }}</td>
                                <td width="150px" align="center">
                                    @if(\Helper::checkRules('edit-variable'))
                                        <a href="{{ URL::to('/variables/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                    @endif
                                    @if(\Helper::checkRules('delete-variable'))
                                        <a onclick="deleteVariable('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="3">No Data Found</td>
                                <td style="display: none;"></td>
                                <td style="display: none;"></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()

@section('script')
    <script src="{{ URL::asset('assets/components/variable.js')}}"></script>
@stop()
