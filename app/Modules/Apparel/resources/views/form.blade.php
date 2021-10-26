<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Apparel')
    @section('content')
    <style>
      .card-footer{
        background-color: transparent;
      }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/ekko-lightbox/ekko-lightbox.css') }}">
    <form method="POST" action="{{ url('apparel/post') }}" enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                @if (isset($uuid))
                  <input type="text" name="uuid" style="display: none" value="{{ $uuid }}">
                @endif
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Apparel Category</label>
                    <select name="apparel_category_id" id="apparel_category" class="form-control select2bs4" style="width: 100%;">
                      @foreach ($categories as $item)
                        <option value="{{ $item->id }}" @if($data_detail->apparel_category_id === $item->id) selected @endif>{{ $item->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input autocomplete="off" name="name" type="text" class="form-control" id="name" placeholder="Enter apparel name" value="{{ old('name', $data_detail->name) }}" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $data_detail->description) }}</textarea>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="name">Tokopedia</label>
                    <input autocomplete="off" name="tokopedia_url" type="text" class="form-control" id="tokopedia_url" placeholder="Enter tokopedia link" value="{{ old('tokopedia_url', $data_detail->tokopedia_url) }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="name">Shopee</label>
                    <input autocomplete="off" name="shopee_url" type="text" class="form-control" id="shopee_url" placeholder="Enter shopee link" value="{{ old('shopee_url', $data_detail->shopee_url) }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="name">Lazada</label>
                    <input autocomplete="off" name="lazada_url" type="text" class="form-control" id="lazada_url" placeholder="Enter lazada link" value="{{ old('lazada_url', $data_detail->lazada_url) }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="name">Bukalapak</label>
                    <input autocomplete="off" name="bukalapak_url" type="text" class="form-control" id="bukalapak_url" placeholder="Enter bukalapak link" value="{{ old('bukalapak_url', $data_detail->bukalapak_url) }}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="name">Blibli</label>
                    <input autocomplete="off" name="blibli_url" type="text" class="form-control" id="blibli_url" placeholder="Enter blibli link" value="{{ old('blibli_url', $data_detail->blibli_url) }}">
                  </div>
                </div>
                <div class="col-md-4" style="margin-top: 2rem">
                  <button type="button" class="btn btn-default" data-toggle="modal" onclick="openModal()" data-target="#modal-xl" style="width: 100%">
                    Upload Apparel Galleries
                  </button>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Sizes</label>
                    <div class="row">
                      <div class="col-md-2">
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" id="allSizeCheckbox" name="is_all_sizes" onchange="allSizeFunc()" @if($data_detail->is_all_sizes === 1) checked @endif>
                          <label for="allSizeCheckbox" class="custom-control-label">All Sizes</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <select name='sizes[]' id='sizes-option' class="select2bs4" multiple="multiple" data-placeholder="Select sizes" style="width: 100%;">
                          @foreach ($sizes as $size)
                            <option value="{{ $size }}" @if($data_detail->sizes) @foreach(json_decode($data_detail->sizes) as $value) @if($value === $size) selected @endif @endforeach @endif>{{ $size }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4" style="margin-top: 2rem">
                  <div class="form-group">
                    <input type="checkbox" name="status" id="status" checked data-bootstrap-switch data-on-color="success">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="image_thumbnail">Image Thumbnail ({{$image_thumbnail_dimension['width']}} x {{$image_thumbnail_dimension['height']}}) 2 MB</label>
                <div class="col-md-12">
                  <img id="preview-image-before-upload" src="@if($data_detail->image_thumbnail) {{ $data_detail->image_thumbnail }} @else {{ asset('img/product_image_not_found.gif') }} @endif" alt="preview image" style="max-width: 100%;height: auto;">
                </div>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" name="image_thumbnail" id="image_thumb" accept="image/png">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card card-gray-dark">
                <div class="card-header">
                  <h4 class="card-title">Apparel Galleries</h4>
                </div>
                <div class="card-body">
                  <div class="row" id="image-gallery">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
      <div class="card-footer">
      </div>
    </form>
    <div class="modal fade" id="modal-xl">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Upload Galleries</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @include('component.dropzone')
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" onclick="saveImage()" id="save-img-button" class="btn btn-primary">Save Images</button>
          </div>
        </div>
      </div>
    </div>
    @endsection
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
    <script type="text/javascript">
      $(function () {
        allSizeFunc();
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
          event.preventDefault();
          $(this).ekkoLightbox({
            alwaysShowClose: true
          });
        });

        $('.select2bs4').select2({
          theme: 'bootstrap4'
        })

        var img_gallery = {!! $data_detail->images !!}
        img_gallery.map(function(item){
          putImg(item.image, imgInit)
        })
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
                  const imageFromDB = "{!! $data_detail->image_thumbnail !!}";
                  if (imageFromDB) clearImageThumbnail(imageFromDB)
                  else defaultImageThumbnail()
                  return;
                }

                if (file.size > 2097152) {
                  alert(`Image Thumbnail size is not meet the requirement (2 MB)`);
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
        
        $("input[data-bootstrap-switch]").each(function(){
          const status = "{!! $data_detail->status !!}" !== '' ? parseInt("{!! $data_detail->status !!}") : 1;
          if (status === 0){
            $("input[data-bootstrap-switch]").bootstrapSwitch('state',false);
            return
          }
          $(this).bootstrapSwitch('state',true);
        });
      })
      var imgInit = 0;
      function saveImage(){
        urlImage.map(function(value, index) {
          putImg(value, imgInit)
        })
        urlImage = [];
        $('#modal-xl').modal('toggle');
      }

      function putImg(src, init) {
        $('#image-gallery').append(`
          <div class="col-sm-2 img-init-${init}">
            <button type="button" class="close" onclick="delImg(${init})" style="right:0px;position: absolute;">
              <span>&times;</span>
            </button>
            <a href="${src}" data-toggle="lightbox" data-gallery="gallery">
              <img src="${src}" class="img-fluid mb-2"/>
            </a>
            <input type="text" style="display:none;" name="image_galleries[]" value="${src}">
          </div>
          `);
        imgInit++;
      }

      function delImg(init) {
        $('.img-init-'+init).remove()
      }

      function openModal() {
        totalImage = 0;
        myDropzone.removeAllFiles(true)
      }

      function allSizeFunc() {
        if ($('#allSizeCheckbox').is(':checked')){
          $('#sizes-option').prop('disabled', true)
        } else {
          $('#sizes-option').prop('disabled', false)
        }
      }
      $.widget.bridge('uibutton', $.ui.button)
    </script>

</html>