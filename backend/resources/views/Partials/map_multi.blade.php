<style>
    #map {
        height: 600px;

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

<?php
    /** Buildings */
    $buildingsArr = [];
    $buildings = $data->buildings;

    /** Universities */
    $uniArr = [];
    $universities = $data->universities;
?>

@section('script-2')

<script src="{{ asset('assets/js/components/map.polygon.js')}}"></script>
<script>
    function initMap() {
        var position = {
            lat: {{ $lat ? $lat : 00000 }},
            lng: {{ $lng ? $lng : 00000 }}
        };

        var buildingsList = [
            @foreach($buildings as $buildingKey => $buildingValue)
            ['{{ $buildingValue->building_name }}', {{ $buildingValue->lat }}, {{ $buildingValue->long }}],
            @endforeach
        ];

        var uniList = [
            @foreach($universities as $uniKey => $uniValue)
            ['{{ $uniValue->uni_name . ($uniValue->type_title != '' ? ' - ' . $uniValue->type_title : '') }}', {{ $uniValue->lat }}, {{ $uniValue->long }}, {{ $uniValue->show_list }}],
            @endforeach
        ];

        var center = new google.maps.LatLng(position['lat'], position['lng']);

        var options = {
            'zoom': 12,
            'center': center,
            'mapTypeId': google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map'), options);
        window.map = map;

        var infowindow = new google.maps.InfoWindow();

        var buildingsMarker, b;

        for (b = 0; b < buildingsList.length; b++) {
            buildingsMarker = new google.maps.Marker({
                position: new google.maps.LatLng(buildingsList[b][1], buildingsList[b][2]),
                map: map
            });
            buildingsMarker.setIcon("{{URL::to('assets/icons/building.png')}}");
            google.maps.event.addListener(buildingsMarker, 'click', (function (buildingsMarker, b) {
                return function () {
                    infowindow.setContent(buildingsList[b][0]);
                    infowindow.open(map, buildingsMarker);
                }
            })(buildingsMarker, b));
        }

        var universitiesMarker, u;

        for (u = 0; u < uniList.length; u++) {
            universitiesMarker = new google.maps.Marker({
                position: new google.maps.LatLng(uniList[u][1], uniList[u][2]),
                map: map
            });
            if (uniList[u][3] == 0) {
                universitiesMarker.setIcon("{{URL::to('assets/icons/university.png')}}");
            } else {
                universitiesMarker.setIcon("{{URL::to('assets/icons/uni_show_list.png')}}");
            }

            google.maps.event.addListener(universitiesMarker, 'click', (function (universitiesMarker, u) {
                return function () {
                    infowindow.setContent(uniList[u][0]);
                    infowindow.open(map, universitiesMarker);
                }
            })(universitiesMarker, u));
        }

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });
        var markersArray = [];
        function clearOverlays() {
            for (var i = 0; i < markersArray.length; i++ ) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }

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

        var defaultIcon = new google.maps.MarkerImage("{{URL::to('assets/icons/City.png')}}");

        var marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: defaultIcon,
            title: 'City Center'
        });

        google.maps.event.addListener(map, 'click', function(event) {
            clearOverlays();
            var marker = new google.maps.Marker({position: event.latLng, map: map});
            document.getElementById("lat").value = event.latLng.lat();
            document.getElementById("lng").value = event.latLng.lng();
            markersArray.push(marker);
        });
        
        init_polyLine();
    }
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTaEX3Xz28PtF73MqtHdFxn8Mr66qBueI&signed_in=true&libraries=places&callback=initMap" async defer></script>
@stop()



