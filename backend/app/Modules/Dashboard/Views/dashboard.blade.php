@extends('Layouts.master')
@section('title', 'Dashboard')
@section('otherhead')
<style type="text/css" media="screen">
  .media .date{
    width: 60px;
  }
  .media .date img{
    width: 50px;
    height: 50px;
  }
</style>
@endsection
@section('content')
<div class="">
    <div class="row top_tiles">
      @if(IS_ADMIN == false)
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-book"></i></div>
          <div class="count">{{ $data->allCourses2 }}</div>
          <h3>All Courses</h3>
          <p>All Courses</p>
        </div>
      </div>
      @endif
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-book"></i></div>
          <div class="count">{{ $data->allCourses }}</div>
          <h3>Active Courses</h3>
          <p>Active Courses Only.</p>
        </div>
      </div>
      @if(IS_ADMIN == true)
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-users"></i></div>
          <div class="count">{{ $data->allInstructors }}</div>
          <h3>Instructors</h3>
          <p>Active Instructors Only.</p>
        </div>
      </div>
      @endif
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-users"></i></div>
          <div class="count">{{ $data->allStudents }}</div>
          <h3>Students</h3>
          <p>Active Students Only.</p>
        </div>
      </div>
      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          {{-- <div class="icon"><i class="fa fa-check-square-o"></i></div> --}}
          <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
          <div class="count">{{ $data->allVideos }}</div>
          <h3>Videos</h3>
          <p>Lectures Videos.</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Transaction Summary <small>Weekly progress</small></h2>
            <div class="filter">
              <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="demo-container" style="height:280px">
                <div id="chart_plot_02" class="demo-placeholder"></div>
              </div>
              <div class="tiles">
                <div class="col-md-4 tile">
                  <span>Total Sessions</span>
                  <h2>231,809</h2>
                  <span class="sparkline11 graph" style="height: 160px;">
                       <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                  </span>
                </div>
                <div class="col-md-4 tile">
                  <span>Total Revenue</span>
                  <h2>$231,809</h2>
                  <span class="sparkline22 graph" style="height: 160px;">
                        <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                  </span>
                </div>
                <div class="col-md-4 tile">
                  <span>Total Sessions</span>
                  <h2>231,809</h2>
                  <span class="sparkline11 graph" style="height: 160px;">
                         <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>Top Courses</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @foreach($data->topCourses as $course)
            <article class="media event">
              <a class="pull-left date" href="{{ URL::to('/courses/view/'.$course->course->id) }}">
                <img src="{{ $course->course->image }}" alt="">
              </a>
              <div class="media-body">
                <a class="title" href="{{ URL::to('/courses/view/'.$course->course->id) }}">{{ $course->course->title }}</a>
                <p>{{ substr($course->course->description, 0, 60) }}...</p>
                <p>Instructor: {{ $course->course->instructor }}</p>
                <p>{{ $course->count }} Students</p>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>
      @if(IS_ADMIN == true)
      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>Top Instructors</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li  class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @foreach($data->topInstructors as $instructor)
            <article class="media event">
              <a class="pull-left date" href="{{ URL::to('/users/view/'.$instructor->instructor->id) }}">
                <img src="{{ $instructor->instructor->image }}" alt="">
              </a>
              <div class="media-body">
                <a class="title" href="{{ URL::to('/users/view/'.$instructor->instructor->id) }}">{{ $instructor->instructor->name }}</a>
                <p>{{ $instructor->count }} Courses</p>
                <p>{{ $instructor->count2 }} Students</p>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>
      @endif
      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>Top Students</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li  class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @foreach($data->topStudents as $student)
            <article class="media event">
              <a class="pull-left date" href="{{ URL::to('/users/view/'.$student->student->id) }}">
                <img src="{{ $student->student->image }}" alt="">
              </a>
              <div class="media-body">
                <a class="title" href="{{ URL::to('/users/view/'.$student->student->id) }}">{{ $student->student->name }}</a>
                <p>{{ $student->count }} Courses</p>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>
    </div>

  </div>
@stop()

@section('script')
    <script src="{{ URL::asset('assets/components/dashboard.js')}}"></script>
@stop()

