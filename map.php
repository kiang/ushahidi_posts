<!DOCTYPE html>
<html>
    <head>
        <title>Geocoding service</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" />
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #map {
                height: 500px;
            }
            .item_control{
                text-align: right;
                padding:6px 0 0;
            }
            .item_control span{
                margin-left:6px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <h1>所有資料</h1>
                <div class="col-md-8">
                    <div id="map"></div>
                </div><!-- col-md-8 -->
                <div class="col-md-4">
                    <div class="col-md-12">
                        <input id="address" type="textbox" value="">
                        <input id="submit" type="button" value="搜尋">
                        <hr />
                        <br />經度：<input id="longtitude" type="textbox" value="">
                        <br />緯度：<input id="latitude" type="textbox" value="">

                    </div>
                    <div class="clearfix"></div>
                    <div class="list-group" id="relative_location">
                        <a class="list-group-item" data-sn="0">
                            <h4 class="list-group-item-heading">台南市政府_0</h4>
                            <p class="list-group-item-text">台南行政中心</p>
                            <div class="item_control">
                                <span data-toggle="tooltip" data-placement="top" title="合併" class="glyphicon glyphicon-pushpin btn btn-primary"></span>
                                <span data-toggle="tooltip" data-placement="top" title="刪除" class="glyphicon glyphicon-trash btn btn-danger"></span>
                            </div>
                        </a>
                        <a class="list-group-item" data-sn="0">
                            <h4 class="list-group-item-heading">台南市政府_0</h4>
                            <p class="list-group-item-text">台南行政中心</p>
                            <div class="item_control">
                                <span data-toggle="tooltip" data-placement="top" title="合併" class="glyphicon glyphicon-pushpin btn btn-primary"></span>
                                <span data-toggle="tooltip" data-placement="top" title="刪除" class="glyphicon glyphicon-trash btn btn-danger"></span>
                            </div>
                        </a>
                        <a class="list-group-item" data-sn="0">
                            <h4 class="list-group-item-heading">台南市政府_0</h4>
                            <p class="list-group-item-text">台南行政中心</p>
                            <div class="item_control">
                                <span data-toggle="tooltip" data-placement="top" title="合併" class="glyphicon glyphicon-pushpin btn btn-primary"></span>
                                <span data-toggle="tooltip" data-placement="top" title="刪除" class="glyphicon glyphicon-trash btn btn-danger"></span>
                            </div>
                        </a>
                        <a class="list-group-item" data-sn="0">
                            <h4 class="list-group-item-heading">台南市政府_0</h4>
                            <p class="list-group-item-text">台南行政中心</p>
                            <div class="item_control">
                                <span data-toggle="tooltip" data-placement="top" title="合併" class="glyphicon glyphicon-pushpin btn btn-primary"></span>
                                <span data-toggle="tooltip" data-placement="top" title="刪除" class="glyphicon glyphicon-trash btn btn-danger"></span>
                            </div>
                        </a>
                    </div>
                </div><!-- col-md-4 -->
            </div><!-- row -->
        </div><!-- container -->

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