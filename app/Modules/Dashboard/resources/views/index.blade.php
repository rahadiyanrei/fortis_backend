<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages')
    Dashboard
    <br>
    <h5>Hi {{ Sentinel::getUser()->fullname }}, Welcome Back !</h5>
    @endsection
    @section('content')
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="overlay-wrapper">
                    <div class="overlay wheel-overlay">
                        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        <div class="text-bold pt-2">Loading...</div>
                    </div>
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="wheel">0</h3>
                            <p>Wheels</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-model-s"></i>
                        </div>
                        <a href="{{ url('wheel/pako') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="overlay-wrapper">
                    <div class="overlay blog-overlay">
                        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        <div class="text-bold pt-2">Loading...</div>
                    </div>
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="blog">0</h3>
                            <p>Blogs</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-code-working"></i>
                        </div>
                        <a href="{{ url('blog') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="overlay-wrapper">
                    <div class="overlay gallery-overlay">
                        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        <div class="text-bold pt-2">Loading...</div>
                    </div>
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="gallery">0</h3>
                            <p>Galleries</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-images"></i>
                        </div>
                        <a href="{{ url('gallery') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    <script type="text/javascript">
        $(document).ready(function(){
            $.ajax({
              type: "GET",
              url: "{!! url('dashboard/wheel') !!}",
              cache: false,
              contentType: false,
              processData: false,
              success: function(resp) {
                $('#wheel').html(resp.count)
                $('.wheel-overlay').hide()
              },
            });

            $.ajax({
              type: "GET",
              url: "{!! url('dashboard/blog') !!}",
              cache: false,
              contentType: false,
              processData: false,
              success: function(resp) {
                $('#blog').html(resp.count)
                $('.blog-overlay').hide()
              },
            });

            $.ajax({
              type: "GET",
              url: "{!! url('dashboard/gallery') !!}",
              cache: false,
              contentType: false,
              processData: false,
              success: function(resp) {
                $('#gallery').html(resp.count)
                $('.gallery-overlay').hide()
              },
            });
        })
        $.widget.bridge('uibutton', $.ui.button)
    </script>

</html>