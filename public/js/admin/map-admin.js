var map;
var directionsDisplay;
var directionsService;

$(document).on('click', '#button_search', searchButtonOnClick);

$(document).ready(function() {
    initMap();
});

function initMap() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsService = new google.maps.DirectionsService();

    var myLocation = new google.maps.LatLng(20.9658649,105.7718411);
    var myOptions = {
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: myLocation
    }

    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
    initRoute();
}

var polyline;
function initRoute() {
    var request = {
        origin: new google.maps.LatLng(15.588754,108.551804),
        destination: new google.maps.LatLng(14.9951,108.063004),
        waypoints: [
            {location:new google.maps.LatLng(15.5292,108.428001), stopover:false},
            {location:new google.maps.LatLng(15.5112,108.388), stopover:false},
            {location:new google.maps.LatLng(15.5082,108.379997), stopover:false},
            {location:new google.maps.LatLng(15.467399,108.294177), stopover:false},
            {location:new google.maps.LatLng(15.400921,108.247864), stopover:false},
            {location:new google.maps.LatLng(15.385767,108.244349), stopover:false},
            {location:new google.maps.LatLng(15.331884,108.155991), stopover:false},
            {location:new google.maps.LatLng(15.281533,108.130568), stopover:false},
            {location:new google.maps.LatLng(15.262266,108.145356), stopover:false},
            {location:new google.maps.LatLng(15.24654,108.112912), stopover:false},
            {location:new google.maps.LatLng(15.203634,108.102476), stopover:false},
            {location:new google.maps.LatLng(15.200975,108.109071), stopover:false},
            {location:new google.maps.LatLng(15.18248,108.116193), stopover:false},
            {location:new google.maps.LatLng(15.172388,108.120064), stopover:false},
            {location:new google.maps.LatLng(15.106373,108.109602), stopover:false},
            {location:new google.maps.LatLng(15.093622,108.098554), stopover:false},
            {location:new google.maps.LatLng(15.016,108.114998), stopover:false}

        ],
        optimizeWaypoints: true,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };


    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);

            polyline = new google.maps.Polyline({
                path: [],
                strokeColor: '#FF0000',
                strokeWeight: 3
            });

            var legs = response.routes[0].legs;

            for (i=0;i<legs.length;i++) {
                var steps = legs[i].steps;

                for (j=0;j<steps.length;j++) {
                    var nextSegment = steps[j].path;
                    for (k=0;k<nextSegment.length;k++) {
                        polyline.getPath().push(nextSegment[k]);
                    }
                }
            }

            console.log(polyline.getPath().length);

        }
    });
}

function searchButtonOnClick() {
    var distance = $("#input_distance").val();
    var latlon1 = polyline.GetPointAtDistance(distance);
    var lat = latlon1.lat();
    var lng = latlon1.lng();

    $("#lat_val").text(lat);
    $("#lgn_val").text(lng);

    var marker = new google.maps.Marker({
        position: latlon1,
        map: map,
        title: 'Hello World!'
    });
}