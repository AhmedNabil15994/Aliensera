@extends('Layouts.master')
@section('title', 'Universities')
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
                                @if(Input::has('title'))
                                    <a href="{{ URL::to('/universities') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
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
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="title" placeholder="Title" value="{{ Input::get('title') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Universities<small> Total : {{ $data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-md-6">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                @if(\Helper::checkRules('add-university'))
                                    <a href="{{URL::to('/universities/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> Add New</a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="x_content x_content_table">
                    <table id="tableList" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th style="padding-left: 50px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->data as $value)
                            <tr id="tableRaw{{ $value->id }}">
                                <td width="3%">{{ $value->id }}</td>
                                <td>{{ $value->title }}</td>
                                <td>{{ $value->description }}</td>
                                <td>{{ $value->status == 1 ? 'Active' : 'In Active' }}</td>
                                <td width="150px" align="center">
                                    @if(\Helper::checkRules('edit-university'))
                                        <a href="{{ URL::to('/universities/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                    @endif
                                    @if(\Helper::checkRules('delete-university'))
                                        <a onclick="deleteUniversity('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if($data->pagination->total_count == 0)
                            <tr>
                                <td></td>
                                <td colspan="4">No Data Found</td>
                                <td style="display: none;"></td>
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
    <script src="{{ asset('assets/components/universities.js')}}"></script>
@stop()
