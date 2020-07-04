@extends('Layouts.master')
@section('title', 'Quiz - ' . $data->data->id)
@section('otherhead')
<link rel="stylesheet" type="text/css" href="{{ URL::to('/assets/css/edit-quiz.css') }}">
@endsection
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/quizes/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>Edit Lesson information</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/lessons') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                                @if(\Helper::checkRules('edit-lesson'))
                                <button type="submit" class="btn btn-round btn-success">Save <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" style="min-height: 80px">
                            <div class="col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="form-control" name="course_id">
                                        <option value="">Select Course...</option>
                                        @foreach($data->courses as $course)
                                        <option value="{{ $course->id }}" {{ $data->data->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Lessons</label>
                                    <select class="form-control" name="lesson_id[]" multiple>
                                        <option value="" disabled>Select Lesson...</option>
                                        @foreach($data->lessons as $lesson)
                                        <option value="{{ $lesson->id }}" {{ in_array($lesson->id, $data->data->lessons_id) ? 'selected' : '' }}>{{ $lesson->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-9 col-sm-8">
                                <div class="form-group">
                                    <label class="control-label">Quiz Type</label>
                                    <div>
                                        <p class="gender">
                                            Inside Lessons <input type="radio" class="flat" name="quiz_type" {{ $data->data->quiz_type == 0 ? 'checked' : ''  }} value="0"/>
                                            Between Lessons <input type="radio" class="flat" name="quiz_type" {{ $data->data->quiz_type == 1 ? 'checked' : ''  }} value="1"/>
                                        </p>
                                    </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            @if(\Helper::checkRules('add-lesson-question'))
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Quiz Questions</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Question</label>
                                    <input type="text" class="form-control" placeholder="Enter Question" name="question">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Number OF Answers</label>
                                    <select class="form-control" name="number_of_answers">
                                        <option value="2">2 Answers</option>
                                        <option value="3">3 Answers</option>
                                        <option value="4">4 Answers</option>
                                        <option value="5">5 Answers</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Answer A</label>
                                    <input type="text" class="form-control" placeholder="Enter Answer A" name="answer_a">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Answer B</label>
                                    <input type="text" class="form-control" placeholder="Enter Answer B" name="answer_b">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-xs-12 answer_c col-sm-6">
                                <div class="form-group">
                                    <label>Answer C</label>
                                    <input type="text" class="form-control" placeholder="Enter Answer C" name="answer_c">
                                </div>
                            </div>
                            <div class="col-xs-12 answer_d col-sm-6">
                                <div class="form-group">
                                    <label>Answer D</label>
                                    <input type="text" class="form-control" placeholder="Enter Answer D" name="answer_d">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 answer_e">
                                <div class="form-group">
                                    <label>Answer E</label>
                                    <input type="text" class="form-control" placeholder="Enter Answer E" name="answer_e">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Correct Answer</label>
                                    <select class="form-control" name="correct_answer">
                                        <option value="">Select Correct Answer</option>
                                        <option value="a">Answer A</option>
                                        <option value="b">Answer B</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group text-right">
                                    <button class="btn btn-sm btn-warning clear-question"><i class="fa fa-refresh"></i></button>
                                    <button class="btn btn-sm btn-success add-question2"><i class="fa fa-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0;">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>List Questions <span>Total:</span><span class="total_questions"> {{ $data->data->number_of_questions }}</span></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content x_content_questions">
                        <div class="quiz">
                            <div class="row">
                                <div class="col-xs-1">No#</div>
                                <div class="col-xs-4">Question</div>
                                <div class="col-xs-1">A</div>
                                <div class="col-xs-1">B</div>
                                <div class="col-xs-1">C</div>
                                <div class="col-xs-1">D</div>
                                <div class="col-xs-1">E</div>
                                <div class="col-xs-2 text-center">Action</div>
                            </div>
                            @foreach($data->data->questions as $key => $question)
                            <div class="row results" id="questions{{ $question->id }}">
                                <div class="col-xs-1">{{ $key+1 }}</div>
                                <div class="col-xs-4">{{ $question->question }}</div>
                                <div class="col-xs-1">{!! $question->answer_a !!}</div>
                                <div class="col-xs-1">{!! $question->answer_b !!}</div>
                                <div class="col-xs-1">{!! $question->answer_c !!}</div>
                                <div class="col-xs-1">{!! $question->answer_d !!}</div>
                                <div class="col-xs-1">{!! $question->answer_e !!}</div>
                                <div class="col-xs-2 text-center">
                                    @if(\Helper::checkRules('delete-lesson-question'))
                                    <button class="btn btn-danger btn-xs" onclick="deleteEditedQuestion({{ $question->id }})"><i class="fa fa-trash"></i></button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop()
@section('script')
<script src="{{ asset('assets/components/quizes.js') }}" type="text/javascript"></script>
@endsection