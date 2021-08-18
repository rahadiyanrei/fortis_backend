<style>
  .card-footer{
    background-color: transparent;
  }
</style>
<form method="POST" action="{{ url($post_action) }}" enctype="multipart/form-data">
  @csrf
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name">Wheel Name</label>
          <input name="name" type="text" class="form-control" id="name" placeholder="Enter wheel name" value="{{ old('name', $data_detail->name) }}" required>
        </div>
        <div class="form-group">
          <input name="brand" type="text" id="brand" class="form-control" value="{{ $wheel_brand }}" style="display: none;">
          @if (isset($uuid))
            <input name="uuid" type="text" id="uuid" class="form-control" value="{{ $uuid }}" style="display: none;"> 
          @endif
        </div>
        <div class="form-group">
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input custom-cb" type="checkbox" id="is_new_release" name="is_new_release">
            <label for="is_new_release" class="custom-control-label">New Release</label>
          </div>
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input custom-cb" type="checkbox" id="is_discontinued" name="is_discontinued">
            <label for="is_discontinued" class="custom-control-label">Discontinued</label>
          </div>
        </div>
        <div class="form-group">
          <label>Diameter</label>
          <div class="row">
            <div class="col-md-12">
              <div class="form-check">
                <div class="row">
                  @foreach ($wheel_diameter as $item)
                    <div class="col-md-2">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" id="diameter-{{$item}}" name="diameter[]" type="checkbox" value="{{ $item }}" @if(old('diameter', $data_detail->sizes->pluck('diameter')) !== null) @foreach(old('diameter', $data_detail->sizes->pluck('diameter')) as $key => $value)  @if($value == $item) checked @endif @endforeach @endif>
                        <label for="diameter-{{$item}}" class="custom-control-label">{{ $item }}"</label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label>About</label>
          <textarea class="form-control" name="about" rows="3" required>{{ old('about', $data_detail->about) }}</textarea>
        </div>
        <div class="form-group">
          <label>Colors</label>
          <div class="row" id="form-wheel-color">
            <div class="col-md-5">
              <input name="wheel_color[color_name][]" id="color_name_index_0" type="text" class="form-control" placeholder="Enter color name" required>
            </div>
            <div class="col-md-5">
              <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                <input type="text" name="wheel_color[color_hex][]" id="color_hex_index_0" class="form-control" data-original-title="" title="">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fas fa-square"></i></span>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <button type="button" id="btn-add-color" onclick="addColor()" class="btn btn-block btn-outline-secondary">
                <i class="nav-icon fas ion-plus-round"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="form-group">
          <input type="checkbox" name="status" id="status" checked data-bootstrap-switch data-on-color="success">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="image_thumbnail">Image Thumbnail</label>
          <div class="col-md-12">
            <img id="preview-image-before-upload" src="@if($data_detail->image) {{$data_detail->image}} @else {{ asset('img/product_image_not_found.gif') }} @endif" alt="preview image" style="max-height: 250px;">
          </div>
          <div class="input-group">
            <div class="custom-file">
              <input type="file" name="image" id="image_thumb" accept="image/png">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
<script type="text/javascript">
  var colorIndex = 0;
  $(document).ready(function(){
    const dataColor = {!! $data_detail->colors !!}
    dataColor.map(function(value, index){
      if(index !== 0) addColor();
      $(`#color_name_index_${index}`).val(value.color_name)
      $(`#color_hex_index_${index}`).val(value.color_hex)
    })

    const isNewRelease = parseInt("{!! $data_detail->is_new_release !!}") || 0;
    const isDiscontinued = parseInt("{!! $data_detail->is_discontinued !!}") || 0;

    if (isNewRelease && !isDiscontinued) {
      $('input[name=is_discontinued]').prop('checked', false)
      $('input[name=is_discontinued]').prop('disabled', true)

      $('input[name=is_new_release]').prop('checked', true)
    } else if (!isNewRelease && isDiscontinued) {
      $('input[name=is_new_release]').prop('checked', false)
      $('input[name=is_new_release]').prop('disabled', true)

      $('input[name=is_discontinued]').prop('checked', true)
    } else {
      $('input[name=is_discontinued]').prop('checked', false)
      $('input[name=is_discontinued]').prop('disabled', false)

      $('input[name=is_new_release]').prop('checked', false)
      $('input[name=is_new_release]').prop('disabled', false)
    }
    
  });

  $("input[data-bootstrap-switch]").each(function(){
    const status = "{!! $data_detail->status !!}" !== '' ? parseInt("{!! $data_detail->status !!}") : 1;
    if (status === 0){
      $("input[data-bootstrap-switch]").bootstrapSwitch('state',false);
      return
    }
    $(this).bootstrapSwitch('state',true);
  });
  var _URL = window.URL || window.webkitURL;

  $("#image_thumb").change(function(e) {
      var file, img;
      var dimensionWidth = parseInt("{{$image_thumbnail_dimension['width']}}");
      var dimensionHeight = parseInt("{{$image_thumbnail_dimension['height']}}");
      if ((file = this.files[0])) {
          img = new Image();
          img.onload = function() {
            if (this.width !== dimensionWidth && this.height !== dimensionHeight)
            {
              alert(`Image Thumbnail dimension is not meet the requirement ( ${dimensionWidth} x ${dimensionHeight} px)`);
              const imageFromDB = "{!! $data_detail->image !!}";
              if (imageFromDB) clearImageThumbnail(imageFromDB)
              else defaultImageThumbnail()
              return;
            }
            let reader = new FileReader();
            reader.onload = (e) => { 
              $('#preview-image-before-upload').attr('src', e.target.result); 
            }
            reader.readAsDataURL(file); 
          };
          img.src = _URL.createObjectURL(file);
      }
  });
  
  function clearImageThumbnail(src){
    $("#image_thumb").val(""); 
    $('#preview-image-before-upload').attr('src', src); 
  }

  function defaultImageThumbnail(){
    $("#image_thumb").val(""); 
    $('#preview-image-before-upload').attr('src', "{{ asset('img/product_image_not_found.gif') }}"); 
  }

  $('.my-colorpicker2').colorpicker()

  $('.my-colorpicker2').on('colorpickerChange', function(event) {
    $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
  });

  async function addColor() {
    colorIndex++;
    $("#form-wheel-color").append(`
    <div class="col-md-5" id="idx-color-name-${colorIndex}">
      <input name="wheel_color[color_name][]" type="text" id="color_name_index_${colorIndex}" class="form-control" placeholder="Enter color name" required>
    </div>
    <div class="col-md-5" id="idx-color-hex-${colorIndex}">
      <div class="input-group my-colorpicker-${colorIndex} colorpicker-element" data-colorpicker-id="2">
        <input type="text" name="wheel_color[color_hex][]" id="color_hex_index_${colorIndex}" class="form-control" data-original-title="" title="">
        <div class="input-group-append">
          <span class="input-group-text"><i class="fas fa-square"></i></span>
        </div>
      </div>
    </div>
    <div class="col-md-2" id="idx-btn-${colorIndex}">
      <button type="button" id="btn-add-color" onclick="deleteColor(${colorIndex})" class="btn btn-block btn-outline-danger">
        <i class="nav-icon fas ion-minus-round"></i>
      </button>
    </div>
    `)
    $('.my-colorpicker-'+colorIndex).colorpicker()
    $('.my-colorpicker-'+colorIndex).on('colorpickerChange', function(event) {
      $(`.my-colorpicker-${colorIndex} .fa-square`).css('color', event.color.toString());
    });
  }

  function deleteColor(index){
    $('#idx-color-name-'+index).remove()
    $('#idx-color-hex-'+index).remove()
    $('#idx-btn-'+index).remove()
  }

  $('.custom-cb').change(function(){
    const nameValue = $(this).attr('name');
    const nameProp = $(this).prop("checked");
    if (nameValue === 'is_new_release' && nameProp === true){
      $('input[name=is_discontinued]').prop('checked', false)
      $('input[name=is_discontinued]').prop('disabled', true)
    } else if (nameValue === 'is_discontinued' && nameProp === true){
      $('input[name=is_new_release]').prop('checked', false)
      $('input[name=is_new_release]').prop('disabled', true)
    } else if (nameProp === false) {
      $('input[name=is_discontinued]').prop('checked', false)
      $('input[name=is_discontinued]').prop('disabled', false)

      $('input[name=is_new_release]').prop('checked', false)
      $('input[name=is_new_release]').prop('disabled', false)
    }
  })
</script>