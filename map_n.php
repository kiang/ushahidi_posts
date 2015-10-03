<!DOCTYPE html>
<html lang="">
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Ushahidi</title>
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
                    <input id="pac-input" class="controls" type="text" placeholder="輸入地址">

                    <div id="map"></div>
                </div><!-- col-md-8 -->
                <div class="col-md-4">
                    <div class="list-group" id="relative_location">

                    </div><!-- list-group -->
                </div><!-- col-md-4 -->
            </div><!-- row -->
        </div><!-- container -->
        <script src="https://code.jquery.com/jquery-2.1.4.min.js" ></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" ></script>
        <script>
            function initAutocomplete() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 22.997222, lng: 120.212590},
                    zoom: 13,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                // Create the search box and link it to the UI element.
                var input = document.getElementById('pac-input');
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                });

                var markers = [];
                // [START region_getplaces]
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                function getRelative() {
                    $("#relative_location").empty();
                    $list = "";
                    for ($i = 0; $i < 4; $i++) {
                        $locationTitle = "台南市政府";
                        $locationDes = "台南行政中心";
                        $list += '<a class="list-group-item" data-sn="' + $i + '"><h4 class="list-group-item-heading">' + $locationTitle + '_' + $i + '</h4><p class="list-group-item-text">' + $locationDes + '</p><div class="item_control"><span data-toggle="tooltip" data-placement="top" title="合併" class="glyphicon glyphicon-pushpin btn btn-primary"></span> <span data-toggle="tooltip" data-placement="top" title="刪除" class="glyphicon glyphicon-trash btn btn-danger"></span></div></a>';
                    }


                    $("#relative_location").append($list);

                }
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();

                    getRelative();

                    if (places.length == 0) {
                        return;
                    }

                    // Clear out the old markers.
                    markers.forEach(function (marker) {
                        marker.setMap(null);
                    });
                    markers = [];

                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
                        var icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25)
                        };

                        // Create a marker for each place.
                        markers.push(new google.maps.Marker({
                            map: map,
                            icon: icon,
                            title: place.name,
                            position: place.geometry.location
                        }));

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
                // [END region_getplaces]
            }
            $("[data-toggle='tooltip']").tooltip();
            $("#relative_location").on("click", ".glyphicon-pushpin", function () {
                $item = $(this).parent().parent()
                $sn = $item.attr("data-sn");


            });
            $("#relative_location").on("click", ".glyphicon-trash", function () {
                $item = $(this).parent().parent()
                $sn = $item.attr("data-sn");
                $item.fadeOut();

            });

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?signed_in=true&libraries=places&callback=initAutocomplete" async defer>
        </script>
    </body>
</html>