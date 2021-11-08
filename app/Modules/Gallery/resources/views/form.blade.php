<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Gallery')
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
    <form method="POST" action="{{ url('gallery/post') }}" enctype="multipart/form-data">
      @csrf
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    @if (isset($uuid))
                        <input type="text" name="uuid" style="display: none" value="{{ $uuid }}">
                    @endif
                    <label>Gallery Type</label>
                    <select name="type" id="type" class="form-control">
                      <option value="car" @if($data_detail->type === 'car') selected @endif>Vehicle</option>
                      <option value="wheel" @if($data_detail->type === 'wheel') selected @endif>Wheel</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Vehicle Brand</label>
                    <select name="vehicle_brand_id" id="vehicle_brand" class="form-control select2bs4" style="width: 100%;">
                      @foreach ($vehicle_brand as $item)
                        <option value="{{ $item->id }}" @if($data_detail->vehicle_brand_id === $item->id) selected @endif>{{ $item->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="title">Gallery Title</label>
                    <input autocomplete="off" name="title" type="text" class="form-control" id="title" placeholder="Enter gallery title" value="{{ old('title', $data_detail->title) }}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Wheel</label>
                    <select name="wheel_id" id="wheel_id" class="form-control select2bs4" style="width: 100%;" required>
                      @foreach ($wheel as $item)
                        <option value="{{ $item->id }}" @if($item->status === 0) disabled="disabled" @endif @if($item->id === $data_detail->wheel_id) selected @endif>{{ $item->name }} @if($item->status === 0) (Inactive) @endif</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-3" style="margin-top: 1rem">
                  <button type="button" class="btn btn-default" data-toggle="modal" onclick="openModal()" data-target="#modal-xl">
                    Upload Galleries
                  </button>
                </div>
                <div class="col-md-3" style="margin-top: 1.5rem">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" name="dashboard_flag" type="checkbox" id="customCheckbox1" value="option1" @if($data_detail->dashboard_flag === 1) checked @endif>
                    <label for="customCheckbox1" class="custom-control-label">Dashboard Flag</label>
                  </div>
                </div>
                <div class="col-md-6" style="margin-top: 1rem">
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
                  <h4 class="card-title">Galleries</h4>
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

        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
          event.preventDefault();
          $(this).ekkoLightbox({
            alwaysShowClose: true
          });
        });

        $('.select2bs4').select2({
          theme: 'bootstrap4'
        })

        var img_gallery = {!! $data_detail->img_gallery !!}
        img_gallery.map(function(item){
          putImg(item.image, imgInit)
        })
        var type = "{!! $data_detail->type !!}"
        if (type === 'wheel') {
          $('#vehicle_brand').prop('disabled', true);
        }
        $('#type').change(function(){
          console.log($(this).val() === 'wheel')
          if ($(this).val() === 'wheel') {
            $('#vehicle_brand').prop('disabled', true);
          } else {
            $('#vehicle_brand').prop('disabled', false);
          }
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
      $.widget.bridge('uibutton', $.ui.button)
    </script>

</html>