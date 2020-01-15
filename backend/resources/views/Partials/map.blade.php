<style>
  #map {
    height: 340px;
  
  }
.controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

#pac-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 12px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 300px;
}

#pac-input:focus {
  border-color: #4d90fe;
}

.pac-container {
  font-family: Roboto;
}

#type-selector {
  color: #fff;
  background-color: #4d90fe;
  padding: 5px 11px 0px 11px;
}

#type-selector label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;
}

</style>
<input id="pac-input" class="controls" type="text" placeholder="Search Box">
<div id="map"></div>
<button id="togglePolygon" class="btn btn-white" type="button">Toggle Polygon</button>
<script>
  function initMap() {
    var markersArray = [];

    var position = {
      lat: {{ $lat ? $lat : 00000 }},
      lng: {{ $lng ? $lng : 00000 }}
    }

    function clearOverlays() {
      for (var i = 0; i < markersArray.length; i++ ) {
        markersArray[i].setMap(null);
      }
      markersArray.length = 0;
    }
    var myLatlng = {lat: 51.507191, lng: -0.127265};

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15,
      center: position,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();
      if (places.length == 0) {
        return;
      }
      clearOverlays();
      // For each place, get the icon, name and location.
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        clearOverlays();
        // Create a marker for each place.
        markersArray.push(new google.maps.Marker({
          map: map,
          title: place.name,
          position: place.geometry.location
        }));

        document.getElementById("lat").value = place.geometry.location.lat();
        document.getElementById("lng").value = place.geometry.location.lng();

        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    })

    var marker = new google.maps.Marker({
      position: position,
      map: map,
      title: 'Click to zoom'
    });

    google.maps.event.addListener(map, 'click', function(event) {
      clearOverlays();
      var marker = new google.maps.Marker({position: event.latLng, map: map});
      document.getElementById("lat").value = event.latLng.lat();
      document.getElementById("lng").value = event.latLng.lng();
      markersArray.push(marker);
    });

    window.map = map;
    
}
//
var form = document.querySelector('form')
form.addEventListener('keydown', function(e){
  if(e.target.localName == "textarea") {
      return true
  }
  var code = e.keyCode || e.which;
  if (code == 13) {
    e.preventDefault();
    return false;
  }
});

    window.MapControl = {}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTaEX3Xz28PtF73MqtHdFxn8Mr66qBueI&signed_in=true&libraries=places&callback=initMap" async defer>
</script>
