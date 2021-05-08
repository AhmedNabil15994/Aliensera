<div class="modal fade" id="move_to_another_course" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-xs-10">
          <h5 class="modal-title">Move Video: <span class="my-title"></span></h5>
        </div>
        <div class="col-xs-2">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="modal-body">
        <div class="col-xs-12">
          <div class="row" style="margin-bottom: 15px;">
            <div class="form-group">
              <div class="col-xs-4">
                <label>Courses</label>
              </div>
              <div class="col-xs-8">
                <select name="course_id" class="form-control select2" style="width: 100%;">
                  <option value="">Select A Course...</option>
                  @foreach($data->courses as $course)
                  <option value="{{ $course->id }}">{{ $course->title }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group">
              <div class="col-xs-4">
                <label>Lessons</label>
              </div>
              <div class="col-xs-8">
                <select name="lesson_id" class="form-control select2" style="width: 100%;">
                  <option value="">Select A Lesson...</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer" style="margin-top: 15px;">
        <button type="button" class="btn btn-success"><i class="fa fa-save"></i> Save changes</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </div>
  </div>
</div>