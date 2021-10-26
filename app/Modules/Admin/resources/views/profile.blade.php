<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','My Profile')
    @section('content')
      <style>
        .card-footer{
          background-color: transparent;
        }
      </style>
      <div class="card">
        <div class="card-body row">
          <div class="col-5 text-center d-flex align-items-center justify-content-center">
            <div class="">
              <h2>{{$user->fullname}}</h2>
              <p class="lead mb-5">
                {{ $user->email }} <br>
                Created at : {{ $user->created_at->toFormattedDateString() }} <br>
                Last updated at : {{ $user->updated_at->toFormattedDateString() }}
              </p>
            </div>
          </div>
          <div class="col-7">
            <form action="{{ url('admin/profile/update') }}" method="post">
              @csrf
              <div class="form-group">
                <label for="old_password">Old Password</label>
                <input type="password" id="old_password" placeholder="Enter your old password" name="old_password" class="form-control">
              </div>
              <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" onkeyup="check()" placeholder="Enter password min 8 character" minlength="8" name="password" class="form-control">
              </div>
              <div class="form-group">
                <label for="confirm_password">Confirmation New Password</label>
                <input type="password" id="confirm_password" onkeyup="check()" placeholder="Enter confirmation password min 8 character" minlength="8" class="form-control">
              </div>
              <div class="form-group">
                <input type="submit" id="btn-submit" class="btn btn-primary" value="Submit" disabled>
              </div>
            </form>
          </div>
        </div>
      </div>
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