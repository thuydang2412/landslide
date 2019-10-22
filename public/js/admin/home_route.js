$(document).ready(function() {
});

function initHomeRoute() {
    getRouteWarningData();
}

function getRouteWarningData() {
    $.ajax({
        url: "/api/route-warning",
        type: 'POST',
        data: {},
        beforeSend: function() {
        }
    }).done(function(response) {
        showAllRouteWarningOnMap(response);
    }).fail(function() {

    });
}

var isShowRouteWarning = true;

function showAllRouteWarningOnMap(response) {
    console.log(response);
    for (var i = 0; i < response.length; i++) {
        var route = response[i];

        var points = route.points;
        var color = route.warning_color;
        var wayPoints = [];
        for (var j = 0; j < points.length; j++) {
            var point = points[j];

            var wayPoint = {location:new google.maps.LatLng(point["latitude"], point["longitude"]), stopover:false};
            wayPoints.push(wayPoint);
        }

        console.log("Show route warning on map");
        showRouteWarningOnMap(wayPoints, color);

    }
}

function showRouteWarningOnMap(waypoints, color) {
    if (!isShowRouteWarning) {
        return;
    }

    var directionsDisplay = new google.maps.DirectionsRenderer({
        polylineOptions: {
            strokeColor: color,
        },
        suppressMarkers: true,
        preserveViewport: true
    });
    directionsDisplay.setMap(map);

    var request = {
        origin: waypoints[0].location,
        destination: waypoints[waypoints.length - 1].location,
        waypoints: waypoints,
        optimizeWaypoints: true,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };


    var directionsService = new google.maps.DirectionsService();
    directionsService.route(request, function(response, status) {
        hideLoading();
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
    });
}