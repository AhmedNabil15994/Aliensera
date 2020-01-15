@extends('Layouts.master')
@section('title', 'Users')
@section('otherhead')
<style type="text/css">
    .x_content.search .row{
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="">
    <div class="page-title">
        <div class="title_left">
        <h3>@yield('heading')</h3>
        </div>

        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search text-right">
                <!--<div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Go!</button>
                </span>
                </div>-->
                <button class="btn btn-success" type="button"><i class="fa fa-plus"></i> Add</button>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <strong>Search</strong>
                    <ul class="nav navbar-right panel_toolbox">
                        <div align="right">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </div>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content search">
                	@yield('x_content_search')
                	<div class="row">
                        <div class="col-xs-12 text-right">
                            <div class="col-xs-6 col-xs-offset-6">
                                <div class="form-group">
                                    <button class="btn btn-sm btn-default" id="search"><i class="fa fa-search"></i>Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <strong>@yield('heading') List</strong>
                    <ul class="nav navbar-right panel_toolbox">
                        <div align="right">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </div>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content x_content_table">
                    @yield('x_content_table')
                </div>
            </div>
        </div>

    </div>
</div>
@stop()
@section('script')

@stop()
