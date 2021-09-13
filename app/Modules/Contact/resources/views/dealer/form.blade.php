<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Create Dealer')
    @section('content')
      <style>
        .card-footer{
          background-color: transparent;
        }
      </style>
      <div class="card">
        <form action="{{ url('/contact/dealer/post') }}" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="pac-input" placeholder="Search Box" value="">
                  @if ($dealer->uuid)
                  <input type="text" name="uuid" class="form-control" value="{{ $dealer->uuid }}" style="display: none">
                  @endif
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div id="googleMap" style="width:100%;height:500px;"></div>
              </div>
            </div>
            <div class="row" style="padding-top: 1rem">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="name">Dealer Name</label>
                  <input name="name" type="text" class="form-control" id="name" placeholder="Enter dealer name (max 100 character)" value="{{ old('name', $dealer->name) }}" maxlength="100" required>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="address">Dealer Address</label>
                  <input name="address" type="text" class="form-control" id="address" placeholder="Enter address name (max 200 character)" value="{{ old('address', $dealer->address) }}" maxlength="200" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="lat">Latitude</label>
                  <input name="lat" type="text" class="form-control" id="lat" value="@if(isset($dealer->lat)) {{$dealer->lat}} @endif" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="lng">Longitude</label>
                  <input name="long" type="text" class="form-control" id="lng" value="@if(isset($dealer->long)) {{$dealer->long}} @endif" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Province</label>
                  <select name="province_id" class="form-control">
                    @foreach ($province as $item)
                    <option value="{{ $item->id }}" @if($dealer->province_id === $item->id) selected @endif>{{ $item->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input name="email" type="email" class="form-control" id="email" placeholder="Enter email" value="{{ old('email', $dealer->email) }}" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="phone_number">Phone Number</label>
                  <input name="phone_number" type="number" class="form-control" id="phone_number" placeholder="Enter phone number" value="{{ old('phone_number', $dealer->phone_number) }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <input type="checkbox" name="status" id="status" checked data-bootstrap-switch data-on-color="success">
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <div class="card-footer">
      </div>
      <script>
        $("input[data-bootstrap-switch]").each(function(){
          $(this).bootstrapSwitch('state',true);
        });

        function myMap() {
          const center = { lat: -0.502106, lng: 117.153709 };
          let map = new google.maps.Map(document.getElementById("googleMap"), {
            zoom: 5,
            center: center,
          });
          let markerDB ;
          const latDB = "{!! $dealer->lat !!}"
          const lngDB = "{!! $dealer->long !!}"
          if (latDB && lngDB) {
            const myLatlng = new google.maps.LatLng(parseFloat(latDB), parseFloat(lngDB));
            map = new google.maps.Map(document.getElementById("googleMap"), {
              zoom: 8,
              center: myLatlng,
            });
            markerDB = new google.maps.Marker({
              position: { lat: parseFloat(latDB), lng: parseFloat(lngDB) },
              draggable: true,
              animation: google.maps.Animation.DROP,
              map: map,
              center: myLatlng,
            });
            markerDB.addListener('dragend', handleDragMarker);
          }
          const input = document.getElementById("pac-input");
          const searchBox = new google.maps.places.SearchBox(input);
          // map.controls[google.maps.ControlPosition.TOP_MIDDLE].push(input);
          // Bias the SearchBox results towards current map's viewport.
          map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
          });
          let markers = [];
          // Listen for the event fired when the user selects a prediction and retrieve
          // more details for that place.
          searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();
            markerDB.setMap(null);
            if (places.length == 0) {
              return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
              marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
              if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
              }
              const icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25),
              };
              // Create a marker for each place.
              let marker = new google.maps.Marker({
                  map,
                  draggable: true,
                  animation: google.maps.Animation.DROP,
                  title: place.name,
                  position: place.geometry.location,
                })
              valName(place.name)
              valAddress(place.formatted_address)
              latLngValue(place.geometry.location.lat(),place.geometry.location.lng())
              markers.push(
                marker
              );
              marker.addListener('dragend', handleDragMarker);
              if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
              } else {
                bounds.extend(place.geometry.location);
              }
            });
            map.fitBounds(bounds);
          });
        }

        function valName(string) {
          $('#name').val(string)
        }

        function valAddress(string) {
          $('#address').val(string)
        }

        function handleDragMarker(e) {
          latLngValue(e.latLng.lat(),e.latLng.lng())
        }

        function latLngValue(lat,lng) {
          $('#lat').val(lat);
          $('#lng').val(lng);
        }
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key={{env('GMAPS_API')}}&callback=myMap&libraries=places"></script>
    @endsection
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

</html>