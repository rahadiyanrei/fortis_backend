<!DOCTYPE html>
<html>
  @include('component.heading')
  @include('component.js')
  @extends('component.body')
  @section('pages',ucfirst($wheel_brand).' Wheels')
  @section('content')
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}"> 
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Details</h3>
          </div>
          <div class="card-body" style="display: block;">
            <div class="row">
              <div class="col-12 col-sm-5">
                <div class="col-12">
                  <img src="{{ $data_detail->image }}" class="product-image" alt="Image Thumbnail">
                </div>
                <div class="col-12 product-image-thumbs">
                  @foreach ($data_detail->colors as $color)
                    @foreach ($color->image as $img)
                    <div class="product-image-thumb"><img src="{{ $img->image }}" alt="Product Image"></div>
                    @endforeach
                  @endforeach
                </div>
              </div>
              <div class="col-12 col-sm-7">
                <h3 class="my-3">{{ $data_detail->name }}</h3>
                <hr>
                <p>
                  {{ $data_detail->about }}
                </p>
                <hr>
                <p>PCD: {{ $data_detail->PCD }}</p>
                <p>ET: {{ $data_detail->ET }}</p>
                <p>HUB: {{ $data_detail->hub }}</p>
                <hr>
                <h4>Finishes</h4>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  @foreach ($data_detail->colors as $item)
                  <label class="btn btn-default text-center">
                    <input type="radio" name="color_option" autocomplete="off" checked="">
                    {{ ucfirst(trans($item->color_name)) }}
                    <br>
                    @if ($item->color_hex !== null)
                    <i class="fas fa-circle fa-2x" style="color: {{ $item->color_hex }}"></i>
                    @endif
                  </label>
                  @endforeach
                </div>
                <hr>
                <h4>Sizes</h4>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  @foreach ($data_detail->sizes as $item)
                  <label class="btn btn-default text-center">
                    <input type="radio" name="color_option" id="color_option_b1" autocomplete="off">
                    <span class="text-l">{{ $item->diameter }}"</span> x {{ $item->option_width }}
                  </label>
                  @endforeach
                </div>
                <hr>
                <h4>Dealer</h4>
                <ul>
                  @foreach ($data_detail->dealer as $item)
                  <li>{{ $item->dealers->name }}</li>
                  @endforeach
                </ul>
                <div class="mt-4">
                  <a href="{{ url($update_action) }}">
                    <button type="button" class="btn btn-block btn-outline-secondary btn-xl"><i class="fas fa-edit"></i>Edit</button>
                  </a>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  @endsection
  <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
  <script>
      $.widget.bridge('uibutton', $.ui.button)
  </script>
  <script type="text/javascript">
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    $(document).ready(function () {
      bsCustomFileInput.init();
      $('.product-image-thumb').on('click', function () {
        var $image_element = $(this).find('img')
        $('.product-image').prop('src', $image_element.attr('src'))
        $('.product-image-thumb.active').removeClass('active')
        $(this).addClass('active')
      })
    });
  </script>

</html>