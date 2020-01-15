<div class="row">
    <div class="col-md-12">
        <div class="box box-body">
            <div >
                <span id="closeReply" class="btn btn-white pull-right"> X </span>
            </div>
            <div style="padding-right: 5%">
                <a class="btn btn-white pull-right"><i class="fa fa-mail-reply "></i> Send </a>
            </div>

            <div class="box-body">

                <div class="form-group">
                    <label>To : </label>
                    <input type="text" class="form-control" placeholder="Email To" name="lat" id="replyTo">
                </div>

                <div class="form-group">
                    <label>CC : </label>
                    <input type="text" class="form-control" placeholder="Email CC" name="lat">
                </div>

                <div class="form-group">
                    <label>Subject : </label>
                    <input type="text" class="form-control" placeholder="Email Subject" name="lat">
                </div>

                <div class="form-group">
                    <label>Body : </label>
                    {!! Form::textarea('desc', '' , ['class' => 'textarea form-control', 'placeholder' => 'Enter Description']) !!}
                </div>

            </div>
        </div>
    </div>
</div>



