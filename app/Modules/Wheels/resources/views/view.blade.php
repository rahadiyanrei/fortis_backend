<!DOCTYPE html>
<html>
  @include('component.heading')
  @include('component.js')
  @extends('component.body')
  @section('pages',ucfirst($wheel_brand).' Wheels')
  @section('content')
    @include('Wheels::form-wheel')
  @endsection
  <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
  <script>
      $.widget.bridge('uibutton', $.ui.button)
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      bsCustomFileInput.init();
    });
  </script>

</html>