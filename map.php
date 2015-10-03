<!DOCTYPE html>
<html>
    <head>
        <title>Geocoding service</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body>
        <div id="map" style="height: 500px; width: 800px; float: left;"></div>
        <div style="float: left;">
            <input id="address" type="textbox" value="">
            <input id="submit" type="button" value="搜尋">
            <hr />
            <br />經度：<input id="longtitude" type="textbox" value="">
            <br />緯度：<input id="latitude" type="textbox" value="">

        </div>
        <script>
            var markers = [];

            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

// Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                setMapOnAll(null);
            }


            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 8,
                    center: {lat: -34.397, lng: 150.644}
                });
                var geocoder = new google.maps.Geocoder();

                document.getElementById('submit').addEventListener('click', function () {
                    geocodeAddress(geocoder, map);
                });
            }

            function geocodeAddress(geocoder, resultsMap) {
                clearMarkers();
                var address = $('#address').val();
                var elementLat = $('#latitude');
                var elementLng = $('#longtitude');

                geocoder.geocode({'address': address}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        resultsMap.setCenter(results[0].geometry.location);
                        resultsMap.setZoom(17);
                        var marker = new google.maps.Marker({
                            map: resultsMap,
                            position: results[0].geometry.location,
                            draggable: true
                        });
                        google.maps.event.addListener(marker, 'dragend', function (e) {
                            elementLat.val(e.latLng.lat());
                            elementLng.val(e.latLng.lng());
                            $('#address').val(e.latLng.toString());
                        });
                        elementLng.val(results[0].geometry.location.lng());
                        elementLat.val(results[0].geometry.location.lat());
                        markers.push(marker);
                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }

        </script>
        <script src="http://code.jquery.com/jquery-2.1.4.min.js">
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?callback=initMap"
        async defer></script>
    </body>
</html>