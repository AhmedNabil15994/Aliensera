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
            <h2>Active Students <small>Weekly progress</small></h2>
            <div class="filter">
              <div id="reportrange20" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="demo-container" style="height:280px">
                <div id="chart_plot_20" class="demo-placeholder"></div>
              </div>
              <div class="tiles">
                <div class="col-md-4 tile">
                  <span>Total Students Sessions</span>
                  <h3>{{ $data->allStudentSessions }}</h3>
                  <span class="sparkline11 graph" style="height: 160px;">
                       <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                  </span>
                </div>
                <div class="col-md-4 tile">
                  <span>Total Revenue</span>
                  <h3>$ {{ $data->allRevenue }}</h3>
                  <span class="sparkline22 graph" style="height: 160px;">
                        <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                  </span>
                </div>
                <div class="col-md-4 tile">
                  <span>Total Views Duration</span>
                  <h3>{{ $data->allDuration }}</h3>
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
      @if(IS_ADMIN== false)
      <div class="col-md-4">
        <div class="x_panel">
          <div class="x_title">
            <h2>Top Seen Courses</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li  class="pull-right"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            @foreach($data->topSeenCourses as $course)
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
      @endif
    </div>

  </div>
@stop()

@section('script')
    <script type="text/javascript">
 
      $(function(){
          
          var chart_plot_20_data = [];
          @foreach($data->chartData as $chartKey => $one)
          chart_plot_20_data[{{ $chartKey }}] = ["{{ $one[0] }}",{{ $one[1] }}];
          @endforeach

          function init_chart_plot(chart_plot_20_data){            
            var chart_plot_20_settings = {
              grid: {
                show: true,
                aboveData: true,
                color: "#3f3f3f",
                labelMargin: 10,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: true,
                hoverable: true,
                autoHighlight: true,
                mouseActiveRadius: 100
              },
              series: {
                lines: {
                  show: true,
                  fill: true,
                  lineWidth: 2,
                  steps: false
                },
                points: {
                  show: true,
                  radius: 4.5,
                  symbol: "circle",
                  lineWidth: 3.0
                },
              },
              legend: {
                position: "ne",
                margin: [0, -25],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function(label, series) {
                  return label + '&nbsp;&nbsp;';
                },
                width: 40,
                height: 1
              },
              colors: ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'],
              shadowSize: 0,
              tooltip: true,
              
              yaxis: {
                min: 0
              },
              xaxis: {
                mode: "categories",
                
              }

            };  
            
            if ($("#chart_plot_20").length){                
                $.plot( $("#chart_plot_20"), 
                [{ 
                  label: "Active Students", 
                  data: chart_plot_20_data, 
                  lines: { 
                    fillColor: "rgba(150, 202, 89, 0.12)" 
                  }, 
                  points: { 
                    fillColor: "#fff" } 
                }], chart_plot_20_settings);
                
            }
          }

          function init_date_rangepicker() {

            if( typeof ($.fn.daterangepicker) === 'undefined'){ return; }
            // console.log('init_daterangepicker');
          
            var cb = function(start, end, label) {
              // console.log(start.toISOString(), end.toISOString(), label);
              $('#reportrange20 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            };

            var optionSet1 = {
              startDate: moment().subtract(29, 'days'),
              endDate: moment(),
              minDate: '01/01/2019',
              maxDate: '12/31/2030',
              dateLimit: {
              days: 60
              },
              showDropdowns: true,
              showWeekNumbers: true,
              timePicker: false,
              timePickerIncrement: 1,
              timePicker12Hour: true,
              ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              opens: 'left',
              buttonClasses: ['btn btn-default'],
              applyClass: 'btn-small btn-primary',
              cancelClass: 'btn-small',
              format: 'MM/DD/YYYY',
              separator: ' to ',
              locale: {
              applyLabel: 'Submit',
              cancelLabel: 'Clear',
              fromLabel: 'From',
              toLabel: 'To',
              customRangeLabel: 'Custom',
              daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
              monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
              firstDay: 1
              }
            };
            
            $('#reportrange20 span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
            $('#reportrange20').daterangepicker(optionSet1, cb);
            $('#options1').click(function() {
              $('#reportrange20').data('daterangepicker').setOptions(optionSet1, cb);
            });
            $('#options2').click(function() {
              $('#reportrange20').data('daterangepicker').setOptions(optionSet2, cb);
            });
            $('#destroy').click(function() {
              $('#reportrange20').data('daterangepicker').remove();
            });

            $('#reportrange20').on('apply.daterangepicker', function(ev, picker) {
              var from = picker.startDate.format('YYYY-MM-DD');
              var to = picker.endDate.format('YYYY-MM-DD');
              $formData = new FormData();
              $formData.append('from', from);
              $formData.append('to', to);
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '/getChartData',
                  type: 'POST',
                  data: $formData ,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function (data) {
                    init_chart_plot(data);
                  },        
              });



            });
         
          }

          init_chart_plot(chart_plot_20_data);
          init_date_rangepicker();


      });

    </script>
@stop()

