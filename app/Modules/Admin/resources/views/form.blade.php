<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Create Admin')
    @section('content')
      <style>
        .card-footer{
          background-color: transparent;
        }
      </style>
      <form method="POST" action="{{ url('create/post') }}">
        <div class="card card-info">
          @csrf
          @if (isset($uuid))
            <input name="uuid" type="text" id="uuid" class="form-control" value="{{ $uuid }}" style="display: none;"> 
          @endif
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="fullname">Fullname</label>
                  <input name="fullname" type="text" class="form-control" id="fullname" placeholder="Enter fullname" autocomplete="off" value="{{ old('fullname', $data_detail->fullname) }}" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" autocomplete="off" value="{{ old('email', $data_detail->email) }}" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="password">Password</label>
                  <input name="password" type="password" class="form-control" id="password" placeholder="Enter password min 8 character" autocomplete="off" onkeyup='check();' value="" minlength="8" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="password">Confirmation Password</label>
                  <input type="password" class="form-control" id="confirm_password" placeholder="Enter confirmation password min 8 character" autocomplete="off" onkeyup='check();' value="" minlength="8" required>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" id="btn-submit" class="btn btn-primary" disabled>Submit</button>
          </div>
          <div class="overlay" style="display: none">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
          </div>
        </div>
        <div class="card-footer">
        </div>
      </form>
      <script type="text/javascript">
        function check(){
          if ($('#password').val() === $('#confirm_password').val()) {
            $('#password').removeClass('is-invalid')
            $('#confirm_password').removeClass('is-invalid')
            $('#btn-submit').prop('disabled', false)
          } else {
            $('#password').addClass('is-invalid')
            $('#confirm_password').addClass('is-invalid')
            $('#btn-submit').prop('disabled', true)
          }
        }
      </script>
    @endsection
</html>