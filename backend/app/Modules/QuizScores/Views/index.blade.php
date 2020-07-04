@extends('Layouts.master')
@section('title', 'Quizzes Scores')
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/quizscores-index.css') }}">
@endsection
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
                                @if(Input::has('student_id') || Input::has('course_id') )
                                    <a href="{{ URL::to('/scores') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                                @endif
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <select name="student_id" class="form-control">
                                            <option value="">Select A Student...</option>
                                            @foreach($data->students as $student)
                                            <option value="{{ $student->id }}" {{ Input::get('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label>Course</label>
                                        <select name="course_id" class="form-control">
                                            <option value="">Select A Course...</option>
                                            @foreach($data->courses as $course)
                                            <option value="{{ $course->id }}" {{ Input::get('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                            @endforeach
                                        </select>
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
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="row">
                        <div class="col-xs-6">
                            <h3>Quizzes <small> Total : {{ $data->data->pagination->total_count }}</small></h3>
                        </div>
                        <div class="col-xs-6 text-right">
                            <ul class="nav navbar-right " style="padding-top: 1%">
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="panel">
                    @foreach($data->data->data as $key => $value)
                    <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{ $value->id }}" aria-expanded="false" aria-controls="collapseOne">
                        <h4 class="panel-title">{{ $key+1 }}- {{ $value->lesson }}</h4>
                        <h4 class="panel-title text-right">
                            <span><i class="fa fa-question-circle"></i> {{ $value->all }}</span>
                            <span><i class="fa fa-check-circle"></i> {{ $value->right }}</span>
                            <span><i class="fa fa-times-circle"></i> {{ $value->wrong }}</span>
                            <span><i class="fa fa-spinner"></i> {{ $value->score }}</span>
                            <div class="clearfix"></div>
                        </h4>
                        <div class="clearfix"></div>
                    </a>
                    <div id="collapseOne{{ $value->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body">
                            <table class="data table table-striped no-margin">
                                <thead>
                                    <tr>
                                        <th>Lesson</th>
                                        <th>Student</th>
                                        <th>Answers</th>
                                        <th>Right</th>
                                        <th>Wrong</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($value->students as $studentKey => $studentValue)
                                    <tr>
                                        <td>
                                            <a href="{{ URL::to('/lessons/view/'.$value->lesson_id) }}" target="_blank">{{ $value->lesson }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ URL::to('/users/view/'.$studentValue->student_id) }}" target="_blank">{{ $studentValue->student }}</a>
                                        </td>
                                        <td><i class="fa fa-question-circle"></i> {{ $studentValue->all }}</td>
                                        <td><i class="fa fa-check-circle"></i> {{ $studentValue->right }}</td>
                                        <td><i class="fa fa-times-circle"></i> {{ $studentValue->wrong }}</td>
                                        <td><i class="fa fa-spinner"></i> {{ $studentValue->score }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>       
                @include('Partials.pagination')
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
@stop()

@section('script')
    <script src="{{ asset('assets/components/courses.js')}}"></script>
@stop()
