<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Social Media')
    @section('content')
      <style>
        .card-footer{
          background-color: transparent;
        }
      </style>
      <div class="card">
        <form method="POST" action="{{ url('/contact/social_media') }}">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="facebook">Facebook</label>
                  <input name="facebook" type="text" class="form-control" placeholder="Enter facebook link" value="{{ old('facebook', $data->facebook) }}">
  
                  <input name="id" type="text" class="form-control" value="{{ old('id', $data->id) }}" style="display: none;">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="linkedin">LinkedIn</label>
                  <input name="linkedin" type="text" class="form-control" placeholder="Enter linkedin link" value="{{ old('linkedin', $data->linkedin) }}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="youtube">Youtube</label>
                  <input name="youtube" type="text" class="form-control" placeholder="Enter youtube link" value="{{ old('youtube', $data->youtube) }}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="instagram">Instagram</label>
                  <input name="instagram" type="text" class="form-control" placeholder="Enter instagram link" value="{{ old('instagram', $data->instagram) }}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="twitter">Twitter</label>
                  <input name="twitter" type="text" class="form-control" placeholder="Enter twitter link" value="{{ old('twitter', $data->twitter) }}">
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    @endsection
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

</html>