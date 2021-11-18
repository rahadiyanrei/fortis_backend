<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Blog')
    @section('content')
      <style>
        .card-footer{
          background-color: transparent;
        }
        .note-video-clip{
          width: 100%
        }
      </style>
      <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/codemirror/codemirror.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/codemirror/theme/monokai.css') }}">
      <form method="POST" action="{{ url('blog/post') }}" enctype="multipart/form-data">
        <div class="card card-info">
          @csrf
          @if (isset($uuid))
            <input name="uuid" type="text" id="uuid" class="form-control" value="{{ $uuid }}" style="display: none;">
          @endif
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="image_thumbnail">Blog Banner ({{$image_thumbnail_dimension['width']}} x {{$image_thumbnail_dimension['height']}}) 2 MB</label>
                  <div class="col-md-12">
                    <img id="preview-image-before-upload" src="@if($data_detail->image) {{$data_detail->image}} @else {{ asset('img/product_image_not_found.gif') }} @endif" alt="preview image" style="max-width: 100%">
                  </div>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" name="image" id="image_thumb" accept="image/png">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="title">Title</label>
                  <input name="title" type="text" class="form-control" id="title" placeholder="Enter title (max 100 character)" autocomplete="off" value="{{ old('title', $data_detail->title) }}" maxlength="100">
                </div>
              </div>
              <div class="col-md-12">
                <textarea name="content" id="summernote">
                  {{ old('content', $data_detail->content) }}
                </textarea>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <input type="checkbox" name="status" id="status" checked data-bootstrap-switch data-on-color="success">
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          <div class="overlay" style="display: none">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
          </div>
        </div>
        <div class="card-footer">
        </div>
      </form>
      <script src="{{ asset('plugins/summernote/summernote-bs4.js') }}"></script>
      <script src="{{ asset('plugins/codemirror/codemirror.js') }}"></script>
      <script src="{{ asset('plugins/codemirror/mode/css/css.js') }}"></script>
      <script src="{{ asset('plugins/codemirror/mode/xml/xml.js') }}"></script>
      <script src="{{ asset('plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
      <script type="text/javascript">
        $(function () {
          // Summernote
          $('#summernote').summernote({
            placeholder: 'write here...',
            height: 500,
            callbacks: {
              onImageUpload: function(files, editor, welEditable) {
                sendFile(files[0]);
              }
            },
          })

          function sendFile(file) {
            data = new FormData();
            data.append("image", file);
            $('.overlay').show()
            $.ajax({
              data: data,
              type: "POST",
              url: "{!! url('blog/imageUploadContent') !!}",
              cache: false,
              contentType: false,
              processData: false,
              success: function(resp) {
                $('.overlay').hide()
                $('#summernote').summernote('insertImage', resp.url);
              },
            });
          }
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
      </script>
    @endsection
</html>
