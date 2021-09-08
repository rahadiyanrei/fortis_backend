<style>
  .card-footer{
    background-color: transparent;
  }
</style>
<link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<form method="POST" action="{{ url($post_action) }}" id="form-wheel" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-md-12">
      <div class="card card-default">
        <div class="card-body p-0">
          <div class="bs-stepper">
            <div class="bs-stepper-header" role="tablist">
              <!-- your steps here -->
              <div class="step" data-target="#general-part">
                <button type="button" class="step-trigger" role="tab" aria-controls="general-part" id="general-part-trigger">
                  <span class="bs-stepper-circle">1</span>
                  <span class="bs-stepper-label">General Information</span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#size-part">
                <button type="button" class="step-trigger" role="tab" aria-controls="size-part" id="size-part-trigger">
                  <span class="bs-stepper-circle">2</span>
                  <span class="bs-stepper-label">Wheel Size (Inch)</span>
                </button>
              </div>
              <div class="line"></div>
              <div class="step" data-target="#color-part">
                <button type="button" class="step-trigger" role="tab" aria-controls="color-part" id="color-part-trigger">
                  <span class="bs-stepper-circle">3</span>
                  <span class="bs-stepper-label">Finishes & Images ({{$image_thumbnail_dimension['width']}} x {{$image_thumbnail_dimension['height']}})</span>
                </button>
              </div>
            </div>
            <div class="bs-stepper-content">
              <!-- your steps content here -->
              <!-- GENERAL -->
              <div id="general-part" class="content fade" role="tabpanel" aria-labelledby="general-part-trigger">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">Wheel Name</label>
                      <input autocomplete="off" name="name" type="text" class="form-control" id="name" placeholder="Enter wheel name" value="{{ old('name', $data_detail->name) }}">
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
                        <label for="is_new_release" class="custom-control-label">New Arrival</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input custom-cb" type="checkbox" id="is_discontinued" name="is_discontinued">
                        <label for="is_discontinued" class="custom-control-label">Discontinued</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="pcd">PCD</label>
                          <input autocomplete="off" name="PCD" type="text" class="form-control" id="pcd" placeholder="Enter PCD" value="{{ old('PCD', $data_detail->PCD) }}">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="et">ET</label>
                          <input autocomplete="off" name="ET" type="text" class="form-control" id="et" placeholder="Enter ET" value="{{ old('ET', $data_detail->ET) }}">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="hub">Hub</label>
                          <input autocomplete="off" name="hub" type="text" class="form-control" id="hub" placeholder="Enter Hub" value="{{ old('hub', $data_detail->hub) }}">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>About</label>
                      <textarea class="form-control" name="about" rows="3">{{ old('about', $data_detail->about) }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Dealer</label>
                      <select name='dealer[]' class="select2bs4" multiple="multiple" data-placeholder="Select a Dealer" style="width: 100%;">
                        @foreach ($dealer as $item)
                          <option value="{{ $item->id }}" @foreach($data_detail->dealer as $value) @if($value->dealer_id === $item->id) selected @endif @endforeach>{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="checkbox" name="status" id="status" checked data-bootstrap-switch data-on-color="success">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="image_thumbnail">Image Thumbnail ({{$image_thumbnail_dimension['width']}} x {{$image_thumbnail_dimension['height']}})</label>
                      <div class="col-md-12">
                        <img id="preview-image-before-upload" src="@if($data_detail->image) {{$data_detail->image}} @else {{ asset('img/product_image_not_found.gif') }} @endif" alt="preview image" style="max-width: 100%;height: auto;">
                      </div>
                      <div class="input-group">
                        <div class="custom-file">
                          <input type="file" name="image" id="image_thumb" accept="image/png">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="stepper.next()">Next</button>
              </div>
              <!-- SIZE -->
              <div id="size-part" class="content fade" role="tabpanel" aria-labelledby="size-part-trigger">
                <div class="row" style="margin-bottom: 0.5rem">
                  @foreach ($wheel_diameter as $index => $item)
                  <div class="col-md-2">
                    <div class="row" style="margin-bottom: 0.5rem;">
                      <div class="col-md-4" style="margin-top: 0.5rem">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" onclick="sizeClick({{$item}})" id="diameter-{{$item}}" name="diameter[diameter][]" type="checkbox" value="{{ $item }}" @if(old('diameter', $data_detail->sizes->pluck('diameter')) !== null) @foreach(old('diameter', $data_detail->sizes->pluck('diameter')) as $key => $value)  @if($value == $item) checked @endif @endforeach @endif>
                          <label for="diameter-{{$item}}" class="custom-control-label">{{ $item }}"</label>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="custom-control custom-checkbox">
                          <input name="diameter[option_width][]" id="option_width_{{$item}}" autocomplete="off" type="text" class="form-control" disabled> 
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="stepper.previous()">Previous</button>
                <button type="button" class="btn btn-outline-secondary" onclick="stepper.next()">Next</button>
              </div>
              <!-- FINISHES -->
              <div id="color-part" class="content" role="tabpanel" aria-labelledby="color-part-trigger">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group" id="form-wheel-color">
                          <div class="row" id="form-wheel-color-row_0" style="margin-bottom: 0.5rem">
                            <div class="col-md-3">
                              <input name="wheel_color[0][color_name]" autocomplete="off" id="color_name_index_0" type="text" class="form-control" placeholder="Enter color name" required>
                            </div>
                            <div class="col-md-3">
                              <div class="input-group my-colorpicker2 my-colorpicker-0 colorpicker-element" data-colorpicker-id="2">
                                <input type="text" name="wheel_color[0][color_hex]" autocomplete="off" id="color_hex_index_0" class="form-control" data-original-title="" title="">
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="input-group">
                                <div class="custom-file">
                                  <input type="file" name="wheel_color[0][image][]" id="color_image_upload_0" accept="image/png" onchange="colorImageUpload(0)" multiple>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <button type="button" onclick="addColor()" class="btn btn-block btn-outline-secondary">
                                <i class="nav-icon fas ion-plus-round"></i>
                              </button>
                            </div>
                            <div class="col-md-12">
                              <div id="image_preview_0">
                                
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="stepper.previous()">Previous</button>
                <button type="submit" id="submit-btn" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript">
  $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'),{
      animation:true
    })
  })
  var colorIndex = 0;
  var totalColorIndex = [];
  totalColorIndex.push(colorIndex)

  $(document).ready(function(){
    $('.my-colorpicker2').colorpicker()

    const dataColor = {!! $data_detail->colors !!}
    dataColor.map(function(value, index){
      if(index !== 0) addColor();
      $(`#color_name_index_${index}`).val(value.color_name)
      $(`#color_hex_index_${index}`).val(value.color_hex)
      value.image.map(function(item){
        $(`#image_preview_${index}`).append(`
        <div class="row" id="image_preview_row_${index}" style="margin-top: 0.5rem;">
        </div>
        `)
        imageColorPreview(index,item.image)
      })
    })

    const dataSize = {!! $data_detail->sizes !!}
    dataSize.map(function(value, index){
      sizeWidthVal(value.diameter, value.option_width)
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

  async function addColor() {
    colorIndex++;
    $("#form-wheel-color").append(`
    <div class="row" id="form-wheel-color-row_${colorIndex}" style="margin-bottom: 0.5rem">
    <div class="col-md-3" id="idx-color-name-${colorIndex}">
      <input name="wheel_color[${colorIndex}][color_name]" autocomplete="off" type="text" id="color_name_index_${colorIndex}" class="form-control" placeholder="Enter color name" required>
    </div>
    <div class="col-md-3" id="idx-color-hex-${colorIndex}">
      <div class="input-group my-colorpicker-${colorIndex} colorpicker-element" data-colorpicker-id="2">
        <input type="text" name="wheel_color[${colorIndex}][color_hex]" autocomplete="off" id="color_hex_index_${colorIndex}" class="form-control" data-original-title="" title="">
      </div>
    </div>
    <div class="col-md-4">
      <div class="input-group">
        <div class="custom-file">
          <input type="file" name="wheel_color[${colorIndex}][image][]" id="color_image_upload_${colorIndex}" accept="image/png" onchange="colorImageUpload(${colorIndex})" multiple>
        </div>
      </div>
    </div>
    <div class="col-md-2" id="idx-btn-${colorIndex}">
      <button type="button" onclick="deleteColor(${colorIndex})" class="btn btn-block btn-outline-danger">
        <i class="nav-icon fas ion-minus-round"></i>
      </button>
    </div>
  <div class="col-md-12">
    <div id="image_preview_${colorIndex}" >
      
    </div>
  </div>
    </div>
    `)
    $('.my-colorpicker-'+colorIndex).colorpicker()
    totalColorIndex.push(colorIndex)
  }

  function deleteColor(index){
    const arrs = totalColorIndex.indexOf(index);
    if (arrs > -1) {
      totalColorIndex.splice(arrs, 1);
    }
    $('#form-wheel-color-row_'+index).remove()
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
  });

  function sizeClick(index) {
    const nameProp = $(`#diameter-${index}`).prop("checked");
    $(`#option_width_${index}`).prop('disabled', !nameProp);
  }

  function sizeWidthVal(index,val) {
    sizeClick(index)
    $(`#option_width_${index}`).val(val);
  }

  function removeImgeColor(index) {
    $(`#color_image_upload_${index}`).val(""); 
  }
  
  function colorImageUpload(index) {
    $(`#image_preview_row_${index}`).remove()
    const totalImage = document.getElementById(`color_image_upload_${index}`).files.length;
    if (totalImage > 4) {
      alert(`Max limit 3 images!`);
      removeImgeColor(index)
      return;
    }
    var dimensionWidth = parseInt("{{$image_thumbnail_dimension['width']}}");
    var dimensionHeight = parseInt("{{$image_thumbnail_dimension['height']}}");
    var files = document.getElementById(`color_image_upload_${index}`);
    $(`#image_preview_${index}`).append(`
    <div class="row" id="image_preview_row_${index}" style="margin-top: 0.5rem;">
    </div>
    `)
    for(var i=0;i<totalImage;i++)
    {
      let img = new Image();
      img.onload = function() {
        if (this.width !== dimensionWidth && this.height !== dimensionHeight)
          {
            alert(`Image dimension is not meet the requirement ( ${dimensionWidth} x ${dimensionHeight} px)`);
            removeImgeColor(index)
            $(`#image_preview_row_${index}`).remove()
            return;
          }
      }
      img.src = _URL.createObjectURL(files.files[i]);
      imageColorPreview(index,URL.createObjectURL(event.target.files[i]))
    }
  }

  function imageColorPreview(index,src) {
    $(`#image_preview_row_${index}`).append(`
        <div class="col-md-3">
          <div class="card">
            <div class="card-body" style="display: block;">
              <img src="${src}" alt="preview image" style="max-width: 100%;height: auto;">
            </div>
          </div>
        </div>  
    `);
  }
</script>