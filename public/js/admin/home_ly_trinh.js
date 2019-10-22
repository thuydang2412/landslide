var isAdmin;

var directionsDisplay;
var directionsService;
var selectedRoute = "";
var selectedRouteStatus = "truotlo";
var isShowRouteByGoogle = true;

var divTruotLo;
var divNgap;
var divDuongXau;
var divSuaDuong;
var divCamDuong;

var newMarkerItem;
var currentMarkerId; // Id of marker when click

var route_data = {
    "quoclo_40b" : {"poly" : poly_QuocLo40B, "waypoints" : waypoints_QuocLo40B, "startKm" : 0, "endKm" : 141.421, "kml" : "ql40b.kml"},
    "quoclo_14g" : {"poly" : poly_QuocLo14G, "waypoints" : waypoints_QuocLo14G,  "startKm" : 24, "endKm" : 66, "kml" : "ql14g.kml"},
    "quoclo_14E" : {"poly" : poly_QuocLo14E, "waypoints" : waypoints_QuocLo14E,  "startKm" : 0, "endKm" : 89.650, "kml" : "ql14e.kml"},
    "quoclo_14D" : {"poly" : poly_QuocLo14D, "waypoints" : waypoints_QuocLo14D,  "startKm" : 0, "endKm" : 74.400, "kml" : "ql14d.kml"},
    "quoclo_14B" : {"poly" : poly_QuocLo14B, "waypoints" : waypoints_QuocLo14B,  "startKm" : 32.126, "endKm" : 73.380, "kml" : "ql14b.kml"},
    "hcm" : {"poly" : poly_HCM, "waypoints" : waypoints_HCM,  "startKm" : 1320.380, "endKm" : 1414, "kml" : "hcm.kml"},
    "hcm_nhanh_tay" : {"poly" : poly_HCM_nhanhTay, "waypoints" : waypoints_HCM_nhanhTay,  "startKm" : 410, "endKm" : 497.500, "kml" : "hcm_nhanhtay.kml"}
};


var route_status = {
    "truotlo" : {"icon" : "truotlo.png"},
    "ngap" : {"icon" : "ngap.png"},
    "duongxau" : {"icon" : "duongxau.png"},
    "suaduong" : {"icon" : "suaduong.png"},
    "camduong" : {"icon" : "camduong.png"}
};

$(document).on('click', '#button-search', searchButtonOnClick);
$(document).on('click', '#button-save', saveButtonOnClick);
$(document).on('click', '#button-delete', deleteButtonOnClick);

$('#select-route').on('change', selectRouteOnChange);
$('#select-route-status').on('change', selectRouteStatusOnChange);

$(document).ready(function() {
    setup();
});


function initHomeLyTrinh() {
    loadPointLyTrinh();
    initRoute();
}

function setup() {
    $('[data-toggle="popover"]').popover();

    isAdmin = $("#input_is_admin").val();

    // Normal user
    if (isAdmin != 1) {
        divTruotLo = $("#icon_truotlo_container");
        divNgap = $("#icon_ngap_container");
        divDuongXau = $("#icon_duongxau_container");
        divSuaDuong = $("#icon_suaduong_container");
        divCamDuong = $("#icon_camduong_container");
    }
}

function loadPointLyTrinh() {
    // Check show only with admin
    if (isAdmin != 1) {
        //return;
    }

    showLoading();
    $.ajax({
        url: "/trang-chu/point-ly-trinh",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(response) {
        //console.log(response);

        hideLoading();

        // Show / Hidden icon type
        if (isAdmin != 1) {
            showHideIconType(response);
        }

        // Show marker
        for(var i=0; i<response.length; i++) {
            var item = response[i];
            showLyTrinhMarker(item);
        }
    }).fail(function() {

    });
}

function initRoute() {
    directionsService = new google.maps.DirectionsService();

    if (isShowRouteByGoogle) {
        directionsDisplay = new google.maps.DirectionsRenderer({
            // polylineOptions: {
            //     strokeColor: "#FF0000",
            // },
            // suppressMarkers: true,
        });
        directionsDisplay.setMap(map);
    }



    //setupRoute(waypoints_QuocLo40B);
}

function selectRouteOnChange() {
    var key = this.value;
    selectedRoute = key;
    setupRoute(route_data[key].waypoints);
    //showKmlData(route_data[key].kml);
}

function selectRouteStatusOnChange() {
    var key = this.value;
    selectedRouteStatus = key;
}


function setupRoute(waypoints) {
    showLoading();
    var request = {
        origin: waypoints[0].location,
        destination: waypoints[waypoints.length - 1].location,
        waypoints: waypoints,
        optimizeWaypoints: true,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };


    directionsService.route(request, function(response, status) {
        hideLoading();
        if (status == google.maps.DirectionsStatus.OK) {
            if (isShowRouteByGoogle) {
                directionsDisplay.setDirections(response);
            }

            route_data[selectedRoute].poly = new google.maps.Polyline({
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
                        route_data[selectedRoute].poly.getPath().push(nextSegment[k]);
                    }
                }
            }
        }
    });

    $("#route-description").html("Từ km " + route_data[selectedRoute].startKm + " - Đến km " + route_data[selectedRoute].endKm);
}

function showKmlData(kmlFileName) {
    var kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/" + kmlFileName + "?v=5";
    var kmlLayer = new google.maps.KmlLayer(kmlSrc);
    kmlLayer.setMap(map);
}

function searchButtonOnClick() {
    if (selectedRoute == "") {
        alert("Phải chọn tuyến đường");
        return;
    }

    var distanceSt = $("#input_distance").val();
    var latSt = $("#input_lat").val();
    var lonSt = $("#input_lon").val();


    if (distanceSt.length == 0 && latSt.length == 0 && lonSt.length == 0) {
        alert("Phải nhập lý trình Hoặc kinh độ, vĩ độ");
        return;
    }

    var findObjLatLng;
    var distanceOriginal = "null";


    // Ly trinh /////////////////////////////////////////////
    if (distanceSt.length > 0) {
        var arr = distanceSt.split("+");

        var distanceKm = arr[0].trim();
        var distanceMet = 0;
        if (arr.length >= 2) {
            distanceMet = arr[1].trim();
        }

        if (isNaN(distanceKm) || isNaN(distanceMet)) {
            alert("Định dạng khoảng cách không hợp lệ");
            return;
        }

        distanceOriginal = distanceKm * 1000 + distanceMet * 1;

        var startKm = route_data[selectedRoute].startKm * 1000;
        var endKm = route_data[selectedRoute].endKm * 1000;

        if (distanceOriginal < startKm || distanceOriginal > endKm) {
            alert("Dữ liệu nhập phải nằm trong khoảng từ km số " + route_data[selectedRoute].startKm + " đến km số " + route_data[selectedRoute].endKm);
            return;
        }

        var distance = distanceOriginal - startKm;

        findObjLatLng = route_data[selectedRoute].poly.GetPointAtDistance(distance);
    }

    // Lat, Lng ///////////////////////////////////////////////////////////////////////////
    else {
        if (isNaN(latSt) || isNaN(latSt)) {
            alert("Định dạng tọa độ không hợp lệ");
            return;
        }

        findObjLatLng = new google.maps.LatLng(latSt, lonSt);
    }


    // Show marker
    newMarkerItem = {};
    newMarkerItem.lat = findObjLatLng.lat();
    newMarkerItem.lon = findObjLatLng.lng();
    newMarkerItem.type = selectedRouteStatus;
    newMarkerItem.km = distanceOriginal;

    showLyTrinhMarker(newMarkerItem);
    map.panTo(findObjLatLng);
    map.setZoom(20);

    // Show save button
    $("#button-save").show();
}

function saveButtonOnClick() {
    if (newMarkerItem == null) {
        return;
    }

    // Save info to database
    var data = {
        _token : $("#input-token-field").val(),
        lat: newMarkerItem.lat,
        lon: newMarkerItem.lon,
        type: selectedRouteStatus,
        route: selectedRoute,
        km: newMarkerItem.km
    };


    showLoading();
    $.ajax({
        url: "/admin/save-route-point",
        type: 'POST',
        data: data,
        beforeSend: function() {
        }
    }).done(function(result) {
        hideLoading();

        console.log(result);

        // Hide save button
        $("#button-save").hide();

        // Reload marker
        location.reload();

    }).fail(function() {

    });
}

function deleteButtonOnClick() {
    if (currentMarkerId == null) {
        return;
    }

    console.log("Delete: " + currentMarkerId);

    var data = {
        _token : $("#input-token-field").val(),
       id: currentMarkerId
    };

    showLoading();
    $.ajax({
        url: "/admin/delete-route-point",
        type: 'POST',
        data: data,
        beforeSend: function() {
        }
    }).done(function(response) {
        hideLoading();

        // Hide delete button
        $("#button-delete").hide();

        // Reload marker
        location.reload();
    }).fail(function() {

    });
}

function showLyTrinhMarker(item) {
    var iconOption = {
        origin: new google.maps.Point(0,0),
        //labelOrigin:  new google.maps.Point(10, 25), //position the label with the labelOrigin
        anchor: new google.maps.Point(10,10), //where the icon's hotspot should be located (which is based on the origin)
        url: "http://quangnam.truotlo.com/images/canhbao/" + route_status[item.type].icon,
        scaledSize: new google.maps.Size(20,20)
    };

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(item.lat, item.lon),
        map: map,
        icon: iconOption
    });

    // Loại hình
    var type = item.type;
    var typeText = "";
    if (type == "truotlo") {
        typeText = "Trượt lở";
    } else if (type == "ngap") {
        typeText = "Ngập";
    } else if (type == "duongxau") {
        typeText = "Đường xấu";
    } else if (type == "suaduong") {
        typeText = "Sửa đường";
    } else if (type == "camduong") {
        typeText = "Cấm đường";
    }

    // Tuyến đường
    var route = item.route;
    var routeText = "";
    if (route == "quoclo_40b") {
        routeText = "Quốc lộ 40B";
    } else if (route == "quoclo_14g") {
        routeText = "Quốc lộ 14G";
    } else if (route == "quoclo_14E") {
        routeText = "Quốc lộ 14E";
    } else if (route == "quoclo_14D") {
        routeText = "Quốc lộ 14D";
    } else if (route == "quoclo_14B") {
        routeText = "Quốc lộ 14B";
    } else if (route == "hcm") {
        routeText = "Hồ Chí Minh";
    } else if (route == "hcm_nhanh_tay") {
        routeText = "Hồ Chí Minh nhánh Tây";
    }

    // Km
    var kmValue = item.km;
    var distanceText = "";

    if (kmValue != "null") {
        var kmText = Math.floor(kmValue / 1000);
        var mText = kmValue % 1000;
        distanceText = "Km " + kmText + "+" + mText + ", ";
    }


    var infoContent = typeText + ": " + distanceText + routeText;

    var infoWindow = new google.maps.InfoWindow({
        content: infoContent
    });

    marker.addListener('click', function() {
        infoWindow.open(map, marker);

        currentMarkerId = item.id;
        console.log("Click marker: " + currentMarkerId);
        $("#button-delete").show();
    });
}


function showHideIconType(response) {
    for(var i=0; i<response.length; i++) {
        var item = response[i];
        var type = item.type;

        if (type == "truotlo") {
            divTruotLo.show();
        } else if (type == "ngap") {
            divNgap.show();
        } else if (type == "duongxau") {
            divDuongXau.show();
        } else if (type == "suaduong") {
            divSuaDuong.show();
        } else if (type == "camduong") {
            divCamDuong.show();
        }

    }
}