@extends('Layouts.master')
@section('title', 'Quizes')
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
                            @if(Input::has('lesson_id') || Input::has('course_id') || Input::has('status') || Input::has('type'))
                                <a href="{{ URL::to('/quizes') }}" type="submit" class="btn btn-danger" style="color: black;"><i class="fa fa fa-refresh"></i></a>
                            @endif
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </div>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content search">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="form-control" name="course_id">
                                        <option value="">Select Course...</option>
                                        @foreach($data->courses as $course)
                                        <option value="{{ $course->id }}" {{ $course->id == Input::get('course_id') ? 'selected' : '' }}>{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Lesson</label>
                                    <select class="form-control" name="lesson_id">
                                        <option value="">Select Lesson...</option>
                                        @foreach($data->lessons as $lesson)
                                        <option value="{{ $lesson->id }}" {{ $lesson->id == Input::get('lesson_id') ? 'selected' : '' }}>{{ $lesson->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Quiz Type</label>
                                    <select class="form-control" name="quiz_type">
                                        <option value="">Select Quiz Type...</option>
                                        <option value="0" {{ Input::get('quiz_type') != null && Input::get('quiz_type') == 0 ? 'selected' : '' }}>Inside Lessons</option>
                                        <option value="1" {{ Input::get('quiz_type') == 1 ? 'selected' : '' }}>Between Lessons</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">Select Status...</option>
                                        <option value="0" {{ Input::get('status') != null && Input::get('status') == 0 ? 'selected' : '' }}>In-Active</option>
                                        <option value="1" {{ Input::get('status') == 1 ? 'selected' : '' }}>Active</option>
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
                        <h3>Quizs<small> Total : {{ $data->pagination->total_count }}</small></h3>
                    </div>
                    <div class="col-xs-6 text-right">
                        <ul class="nav navbar-right " style="padding-top: 1%">
                            @if(\Helper::checkRules('add-lesson'))
                                <a href="{{URL::to('/quizes/add')}}" class="btn btn-default" style="color: black;"><i class="fa fa fa-plus"></i> Add New</a>
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
                        <th># Questions</th>
                        <th>Course</th>
                        <th>Lesson</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th style="padding-left: 50px">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data->data as $value)
                        <tr id="tableRaw{{ $value->id }}">
                            <td width="3%">{{ $value->id }}</td>
                            <td width="10%">{{ $value->number_of_questions }}</td>
                            <td><a href="{{ URL::to('/courses/view/'.$value->course_id) }}" target="_blank">{{ $value->course_title }}</a></td>
                            <td>
                                @foreach($value->lessons as $lesson)
                                <a href="{{ URL::to('/lessons/edit/'.$lesson->id) }}" target="_blank">{{ $lesson->id }}- {{ $lesson->title }}</a><br>
                                @endforeach
                            </td>
                            <td>{{ $value->type }}</td>
                            <td>{{ $value->status == 1 ? 'Active' : ($value->status == 0 ? 'In Active' : 'Instructor Sent Request') }}</td>
                            <td width="150px" align="center">
                                @if(\Helper::checkRules('edit-quiz'))
                                    <a href="{{ URL::to('/quizes/edit/' . $value->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                @endif
                                @if(\Helper::checkRules('delete-quiz'))
                                    <a onclick="deleteQuiz('{{ $value->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if($data->pagination->total_count == 0)
                        <tr>
                            <td></td>
                            <td colspan="6">No Data Found</td>
                            <td style="display: none;"></td>
                            <td style="display: none;"></td>
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
    <script src="{{ asset('assets/components/quizes.js')}}"></script>
@stop()
