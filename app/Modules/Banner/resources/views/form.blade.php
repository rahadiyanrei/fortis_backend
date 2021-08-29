<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Banner')
    @section('content')
      <style>
        .card-footer{
          background-color: transparent;
        }
      </style>
      <form method="POST" action="{{ url('banner/post') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="image_thumbnail">Image Banner ({{$image_thumbnail_dimension['width']}} x {{$image_thumbnail_dimension['height']}})</label>
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
            <div class="col-md-6">
              <div class="form-group">
                <label for="title">Title</label>
                <input name="title" type="text" class="form-control" id="title" placeholder="Enter title (max 50 character)" value="{{ old('title', $data_detail->title) }}" maxlength="50">
              </div>
              <div class="form-group">
                <label for="body">Body</label>
                <input name="body" type="text" class="form-control" id="body" placeholder="Enter body (max 100 character)" value="{{ old('body', $data_detail->body) }}" maxlength="100">
              </div>
              <div class="form-group">
                <label for="url_ref">URL Link</label>
                <input name="url_ref" type="text" class="form-control" id="url_ref" placeholder="example: https://google.com or google.com" value="{{ old('url_ref', $data_detail->url_ref) }}" required>
              </div>
              <div class="form-group">
                <input type="checkbox" name="status" id="status" checked data-bootstrap-switch data-on-color="success">
              </div>
              @if (isset($uuid))
                <input name="uuid" type="text" id="uuid" class="form-control" value="{{ $uuid }}" style="display: none;"> 
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
      <script type="text/javascript">
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

        $("input[data-bootstrap-switch]").each(function(){
          const status = "{!! $data_detail->status !!}" !== '' ? parseInt("{!! $data_detail->status !!}") : 1;
          if (status === 0){
            $("input[data-bootstrap-switch]").bootstrapSwitch('state',false);
            return
          }
          $(this).bootstrapSwitch('state',true);
        });
      </script>
    @endsection
</html>