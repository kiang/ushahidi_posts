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
                    <div class="col-md-12">
                        <div>title: <span id="postTitle"></span></div>
                        <div>content: <span id="postContent"></span></div>
                    </div>
                    <div clas="btn-group">
                        <a href="#" class="btn btn-primary">儲存</a>
                        <a href="#" class="btn btn-default" id="btnPreviousPost">上一筆</a>
                        <a href="#" class="btn btn-default" id="btnNextPost">下一筆</a>
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

        <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?callback=initMap"
        async defer></script>
        <script>
            var markers = [];
            var token = '';
            var offset = 0;
            var currentResult = [];
            var workingIndex = 0;
            var map;

            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

// Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                $('#latitude').val('');
                $('#longtitude').val('');
                setMapOnAll(null);
            }


            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 8,
                    center: {lat: 22.9998999, lng: 120.2268758}
                });
                var geocoder = new google.maps.Geocoder();

                $('#submit').click(function () {
                    geocodeAddress(geocoder, map);
                });

                $.post('http://ushahidi.olc.tw/api/oauth/token', {
                    grant_type: 'client_credentials',
                    client_id: 'ushahidiui',
                    client_secret: '35e7f0bca957836d05ca0492211b0ac707671261',
                    scope: 'posts api'
                }, function (d) {
                    if (d.access_token) {
                        token = d.access_token;
                        getNextPage();
                    } else {
                        alert('can not get token');
                    }
                });
            }

            function getNextPage() {
                $.ajax({
                    url: 'http://ushahidi.olc.tw/api/api/v3/posts?orderby=created&order=desc&limit=10&offset=' + offset,
                    type: 'get',
                    data: {},
                    headers: {
                        Authorization: 'Bearer ' + token
                    },
                    dataType: 'json',
                    success: function (data) {
                        offset += data.count;
                        currentResult = data.results;
                        workingIndex = 0;
                        //console.info(data);
                        getNextPost();
                    }
                });
            }

            function getNextPost() {
                clearMarkers();
                var elementLat = $('#latitude');
                var elementLng = $('#longtitude');
                $('#postTitle').html(currentResult[workingIndex].title);
                $('#postContent').html(currentResult[workingIndex].content);
                if (currentResult[workingIndex].values.location_default && currentResult[workingIndex].values.location_default[0]) {
                    var p = {lat: currentResult[workingIndex].values.location_default[0].lat, lng: currentResult[workingIndex].values.location_default[0].lon};
                    map.setCenter(p);
                    map.setZoom(17);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: p,
                        draggable: true
                    });
                    google.maps.event.addListener(marker, 'dragend', function (e) {
                        elementLat.val(e.latLng.lat());
                        elementLng.val(e.latLng.lng());
                    });
                    elementLng.val(currentResult[workingIndex].values.location_default[0].lon);
                    elementLat.val(currentResult[workingIndex].values.location_default[0].lat);
                    markers.push(marker);
                }
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

            $(function () {
                $('#btnNextPost').click(function () {
                    var maxIndex = currentResult.length - 1;
                    workingIndex += 1;
                    if (workingIndex > maxIndex) {
                        workingIndex = maxIndex;
                    }
                    getNextPost();
                    return false;
                });
                $('#btnPreviousPost').click(function () {
                    workingIndex -= 1;
                    if (workingIndex < 0) {
                        workingIndex = 0;
                    }
                    getNextPost();
                    return false;
                });
            })

        </script>
    </body>
</html>