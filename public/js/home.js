var map;
var listPoly = [];

var plot_precipmm;
var plot_tempC;

var URL_GET_RECENT_PRECIPITATION = "precipitation/recent";

var isShowKml = true;

$(document).on("change", ".cb-layer", cbLayerOnChange);

$(document).ready(function() {
    // $('.bxslider').bxSlider({
    //     auto: true,
    // });

    initMap();

    // Canh bao
    var isShowPopupCanhBao = $("#isShowPopupCanhBao").val();
    if (isShowPopupCanhBao == 1) {
        $("#warningMucDoTruotLoPopupModal").modal({
            backdrop: 'static',
            toggle: 'true'
        });
    }

});

// (function titleScroller(text) {
//     document.title = text;
//     setTimeout(function () {
//         titleScroller(text.substr(1) + text.substr(0, 1));
//     }, 500);
// }('Bản thử nghiệm Đề tài: Nghiên cứu dự báo nguy cơ tai biến trượt lở mái dốc dọc các tuyến giao thông trọng điểm miền núi tỉnh Quảng Nam và đề xuất giải pháp ứng phó Mã số Đề tài: ĐTĐLCN. 23/17 '));


function initMap() {
    // Setup map
    setupMap();

    // Load all station
    loadAllStationMarker();

    // Load sub district marker
    loadSubDistrictMarker();

    // Load sub district marker
    loadSubSubDistrictMarker();

    // Load KML Data
    //loadKMLData();

    // Load world weather data
    // loadPrecipitationData();

    var truotLoIcon = "http://quangnam.truotlo.com/images/fallingrocks.png";
    loadDiemTruotMarker(arr_tsd, arr_tsd_data, truotLoIcon);
    loadDiemTruotMarker(arr_hcm, arr_hcm_data, truotLoIcon);
    loadDiemTruotMarker(arr_ql_40B, arr_ql_40B_data, truotLoIcon);
    loadDiemTruotMarker(arr_ql_24c, arr_ql_24c_data, truotLoIcon);
    loadDiemTruotMarker(arr_ql_14d, arr_ql_14d_data, truotLoIcon);
    loadDiemTruotMarker(arr_ql_14g, arr_ql_14g_data, truotLoIcon);
    loadDiemTruotMarker(arr_ql_14b, arr_ql_14b_data, truotLoIcon);
    loadDiemTruotMarker(arr_ql_14e, arr_ql_14e_data, truotLoIcon);
    loadDiemTruotMarker(arr_duong_tinh, arr_duong_tinh_data, truotLoIcon);

    loadLabelMarker(arr_phan_vung_khi_hau, arr_phan_vung_khi_hau_data);

}

function setupMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 15.5193887, lng: 107.9616405},
        zoom: 9,
        options: {
            //draggable: false,
            //zoomControl: false,
            //scrollwheel: false,
            //disableDoubleClickZoom: true
        } 
    });

    google.maps.event.addListener(map, 'zoom_changed', function() {
        var zoomLevel = map.getZoom();

        var isShowSubDistrict = zoomLevel > 11;

        for(var i=0; i< arrSubSubDistrictMarker.length; i++) {
            arrSubSubDistrictMarker[i].setVisible(isShowSubDistrict);
        }
    });

    initHomeLyTrinh();
    initHomeRoute();
}

var arrStationMarker = [];
function loadAllStationMarker() {
    $.ajax({
        url: "/trang-chu/station",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(result) {
        var stationIcon = "http://quangnam.truotlo.com/images/station.png";
        for (var i=0; i< result.length; i++) {
            var marker = loadMarker(result[i].name, result[i].latitude, result[i].longitude, stationIcon);
            marker.data = result[i].id;
            arrStationMarker.push(marker);

            google.maps.event.addListener(marker, 'click', function(){
                var stationId = this.data;
                window.open("/thoi-tiet?stationId=" + stationId,"_blank");
            });
        }
    }).fail(function() {
    });
}

var arrSubDistrictMarker = [];
function loadSubDistrictMarker() {
    $.ajax({
        url: "/trang-chu/sub-district",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(result) {
        for (var i=0; i< result.length; i++) {
            var marker = loadMarkerTextOnly(result[i].name, result[i].lat, result[i].lon, "labels-huyen");
            marker.setVisible(true);
            arrSubDistrictMarker.push(marker);
        }

    }).fail(function() {

    });
}

var arrSubSubDistrictMarker = [];
function loadSubSubDistrictMarker() {
    $.ajax({
        url: "/trang-chu/sub-sub-district",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(result) {
        for (var i=0; i< result.length; i++) {
            var marker = loadMarkerTextOnly(result[i].name, result[i].lat, result[i].lon, "labels-xa");
            //marker.setVisible(true);
            arrSubSubDistrictMarker.push(marker);
        }

    }).fail(function() {

    });
}

function loadMarker(name, lat, lon, icon) {
    // var pIcon;
    // if (icon == null) {
    //     pIcon = new google.maps.MarkerImage('http://quangnam.truotlo.com/images/pixel.png',
    //         null,
    //         null,
    //         null,
    //         new google.maps.Size(0, 0));
    // } else {
    //     pIcon = new google.maps.MarkerImage(icon,
    //         null,
    //         null,
    //         null,
    //         new google.maps.Size(10, 10));
    // }


    // var marker = new MarkerWithLabel({
    //     position:new google.maps.LatLng(lat, lon),
    //     icon: pIcon,
    //     labelContent: name,
    //     //labelAnchor: new google.maps.Point(50, -50),
    //     labelClass: "labels-map",
    //     map: map
    // });


    var markerOption = {
        position: new google.maps.LatLng(lat, lon),
        map: map
    };


    // Setup label
    if (name != "") {
        markerOption.label = {
            text: name,
            fontSize: "12px"
        };
    }

    // Setup icon
    var iconOption = {
        origin: new google.maps.Point(0,0),
        labelOrigin:  new google.maps.Point(10, 25), //position the label with the labelOrigin
        anchor: new google.maps.Point(10,10) //where the icon's hotspot should be located (which is based on the origin)
    };

    if (icon != "") {
        iconOption.url = icon;
        iconOption.scaledSize = new google.maps.Size(9,9);
    } else {
        iconOption.url = "http://quangnam.truotlo.com/images/pixel.png";
        iconOption.scaledSize = new google.maps.Size(0,0);
    }
    markerOption.icon = iconOption;

    var marker = new google.maps.Marker(markerOption);
    marker.setVisible(false);

    return marker;
}


function loadMarkerTextOnly(name, lat, lon, textStyle) {
    // var markerOption = {
    //     position: new google.maps.LatLng(lat, lon),
    //     icon: {url: "http://quangnam.truotlo.com/images/pixel.png", scaledSize: new google.maps.Size(0,0)},
    //     map: map,
    // };
    //
    //
    // // Setup label
    //
    // markerOption.label = {
    //     text: name,
    //     fontSize: "12px"
    // };
    //
    // var marker = new google.maps.Marker(markerOption);
    // marker.setVisible(false);
    //
    // return marker;


    pIcon = new google.maps.MarkerImage('http://quangnam.truotlo.com/images/pixel.png',
        null,
        null,
        null,
        new google.maps.Size(0, 0));

    var marker = new MarkerWithLabel({
        position:new google.maps.LatLng(lat, lon),
        icon: pIcon,
        labelContent: name,
        //labelAnchor: new google.maps.Point(50, -50),
        labelClass: textStyle,
        map: map
    });

    marker.setVisible(false);

    return marker;
}

/////////////////////////////////////////////////////////////////////////////////////////
var kmlMap = [];
function loadKMLData() {
    $.ajax({
        url: "map/kmlData",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(result) {
        kmlMap = [];
        for(var i=0; i<result.length; i++) {
            var item = result[i];
            kmlMap.push({id: item.kml_id, name: item.kml_name, fileName: item.kml_file_name, kmlObject: null});
        }
        loadKMLLayer();
    }).fail(function() {

    });
}

function loadKMLLayer() {
    var i;
    for (i=0; i< kmlMap.length; i++) {
        var kmlItem = kmlMap[i];
        var src = kmlItem.fileName + "?time=11";
        var kmlLayer = new google.maps.KmlLayer(src);
        //kmlItem.kmlObject = kmlLayer;
        kmlLayer.setMap(map);
    }

    setTimeout(function() {
        map.setZoom(map.getZoom());
    }, 1000);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

function displayBoundary(data) {
    var i, j;

    for (i=0; i< data.length; i++) {
        var arrCoords = [];
        var arrBoundary = data[i].boundary;

        if (arrBoundary.length > 0) {
            for (j=0; j<arrBoundary.length; j++) {
                arrCoords.push({lat: arrBoundary[j].lat, lng: arrBoundary[j].lon});
            }
            arrCoords.push({lat: arrBoundary[0].lat, lng: arrBoundary[0].lon});
        }

        var polyBoundary = new google.maps.Polygon({
            paths: arrCoords,
            strokeColor: '#000000',
            strokeOpacity: 0.0,
            strokeWeight: 2,
            fillColor: data[i].weather_info.warning_level,
            fillOpacity: 0.95
        });

        listPoly.push(polyBoundary);
        polyBoundary.setMap(map);
    }
}

function loadPrecipitationData() {
    var $inputSubDistrict = $("#input-sub-district");

    var startDate = moment().format("YYYY-MM-DD");
    var endDate = moment().add(5, 'days').format("YYYY-MM-DD");

    var data = {startDate : startDate, endDate : endDate};

    $.ajax({
        url: URL_GET_RECENT_PRECIPITATION,
        type: 'GET',
        data: data,
        beforeSend: function() {
        }
    }).done(function(response) {
        displayData(response.data);
    }).fail(function() {

    });
}

// Convert data from nest format to simple format
function displayData(data) {
    var arrDataSimpleFormat = [];
    for (var i=0; i<data.length; i++) {
        var dataLv1 = data[i];
        var subDistrictName = dataLv1.station.name;

        var arrDataLv2 = dataLv1.lv2;
        for (var j=0; j<arrDataLv2.length; j++) {
            var dataLv2 = arrDataLv2[j];
            var date =  dataLv2.date;
            var arrDataLv3 = dataLv2.lv3;
            for (k=0; k<arrDataLv3.length; k++) {
                var dataLv3 = arrDataLv3[k];
                var time = dataLv3.time_val;
                var tempC = dataLv3.tempC;
                var precipMM = dataLv3.precipMM;
                var humidity = dataLv3.humidity;
                var pressure = dataLv3.pressure;
                var weatherIcon = dataLv3.weather_icon;

                var dataSimpleFormat = {
                    'district' : subDistrictName,
                    'date' : date,
                    'time' : time,
                    'tempC' : tempC,
                    'precipMM' : precipMM,
                    'humidity' : humidity,
                    'pressure' : pressure,
                    'weatherIcon' : weatherIcon,
                };

                arrDataSimpleFormat.push(dataSimpleFormat);
            }
        }
    }

    fillToTable(arrDataSimpleFormat);
    drawGraph(arrDataSimpleFormat);
}

var dataTable = null;
function fillToTable(data) {
    if(!dataTable) {
        dataTable = $('#tbl-data').DataTable({
            "lengthChange": false,
            "searching": false,
            "columnDefs": [
                { orderable: false, targets: 1 },
                { orderable: false, targets: 3 },
                { type: 'date-eu', targets: 0, orderData: [0, 1] }
            ],

            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<i class="fa fa-arrow-left" aria-hidden="true"></i>',
                    "sNext": '<i class="fa fa-arrow-right" aria-hidden="true"></i>'
                },
                "sEmptyTable": "Không có dữ liệu",
                "sInfo": ""
            },
            responsive: true

        });
    }

    dataTable.clear();

    for (var i = 0; i < data.length; i++) {
        var item = data[i];

        var subDistrictName = item['subDistrictName'];
        var date = moment(item["date"], "YYYY-MM-DD").format("DD/MM/YYYY");
        var time = ("0" + item['time'] / 100).slice(-2) + ":00";
        var tempC = item['tempC'];
        var precipMM = item['precipMM'];
        var humidity = item['humidity'];
        var pressure = item['pressure'];
        var weatherIcon = item['weatherIcon'];

        dataTable.row.add([date, time, tempC,
            '<span style="display: block; text-align: center;"><img class="weather-icon text-center" src="' + weatherIcon + '" alt="" width="30px" height="30px" /></span>',
            precipMM, humidity, pressure]);
    }

    dataTable.draw();
}

function drawGraph(data) {
    var dataGraphPrecipmm = [];
    dataGraphPrecipmm.length = 0;

    var dataGraphTempC = [];
    dataGraphTempC.length = 0;

    var i;
    var totalPrecipMM = 0;
    for(i=0; i<data.length; i++) {
        var item = data[i];

        var date = item['date'];
        var time = item['time'];
        var precipMM = item['precipMM'];
        var tempC = item['tempC'];

        var dateUnix = moment(date, "YYYY-MM-DD").unix() + time/100 * 3600;

        totalPrecipMM += precipMM;
        dataGraphPrecipmm.push([dateUnix * 1000, totalPrecipMM]);
        dataGraphTempC.push([dateUnix * 1000, tempC]);
    }


    _drawGraph(plot_precipmm, 'precipmm-graph-panel', dataGraphPrecipmm, '<b>Ngày</b>', '<b>mm</b>');
    //_drawGraph(plot_tempC, 'tempC-graph-panel', dataGraphTempC, '<b>Ngày</b>', '<b>°C&nbsp;</b>');
}

function _drawGraph(plot, chartName, listPoint, AxisX, AxisY){
    if(plot != null) {
        plot.destroy();
    }

    if(listPoint.length == 0) {
        return;
    }

    plot = $.jqplot(chartName, [ listPoint ], {
        width: "100%",
        series : [ {
            showMarker : false
        } ],
        axes : {
            xaxis : {
                numberTicks: 6,
                renderer:$.jqplot.DateAxisRenderer,
                tickRenderer: $.jqplot.canvasAxisTickRenderer,
                tickOptions:{formatString:'%d/%m'},
                min : listPoint[0][0],
                max: listPoint[listPoint.length-1][0],
                label : AxisX
            },
            yaxis : {
                label : AxisY,
                min: 0
            }
        }
    });
}

//////////////////////////////////////////////

function cbLayerOnChange() {
    var id = $(this).attr('layer-id');
    console.log(id);
    if($(this).is(":checked")) {
        showLayer(id, true);
    } else {
        showLayer(id, false);
    }
}

function showLayer(id, isShow) {
    var arr;
    //var kmlLayer;
    var kmlSrc;
    var version = "112";

    if (id == "tsd" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/tsd.kml?time=" + version;
            if (kml_tsd == null) {
                kml_tsd = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_hcm_nhanhtay;

            if (isShow) {
                kml_tsd.setMap(map);
            } else {
                kml_tsd.setMap(null);
            }

        } else {
            arr = arr_tsd;
        }
    }

    if (id == "hcm" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/hcm.kml?time=" + version;
            if (kml_hcm == null) {
                kml_hcm = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_hcm;

            if (isShow) {
                kml_hcm.setMap(map);
            } else {
                kml_hcm.setMap(null);
            }

        } else {
            arr = arr_hcm;
        }
    }

    if (id == "ql_40B" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/ql40b.kml?time=" + version;
            if (kml_ql_40b == null) {
                kml_ql_40b = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_ql_40b;

            if (isShow) {
                kml_ql_40b.setMap(map);
            } else {
                kml_ql_40b.setMap(null);
            }

        } else {
            arr = arr_ql_40B;
        }
    }

    if (id == "ql_24c" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/ql24c.kml?time=" + version;
            if (kml_ql_24c == null) {
                kml_ql_24c = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_ql_24c;

            if (isShow) {
                kml_ql_24c.setMap(map);
            } else {
                kml_ql_24c.setMap(null);
            }

        } else {
            arr = arr_ql_24c;
        }
    }

    if (id == "ql_14d" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/ql14d.kml?time=" + version;
            if (kml_ql_14d == null) {
                kml_ql_14d = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_ql_14d;

            if (isShow) {
                kml_ql_14d.setMap(map);
            } else {
                kml_ql_14d.setMap(null);
            }

        } else {
            arr = arr_ql_14d;
        }
    }

    if (id == "ql_14g" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/ql14g.kml?time=" + version;
            if (kml_ql_14g == null) {
                kml_ql_14g = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_ql_14g;

            if (isShow) {
                kml_ql_14g.setMap(map);
            } else {
                kml_ql_14g.setMap(null);
            }

        } else {
            arr = arr_ql_14g;
        }
    }

    if (id == "ql_14b" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/ql_14b.kml?time=" + version;
            if (kml_ql_14b == null) {
                kml_ql_14b = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_ql_14b;

            if (isShow) {
                kml_ql_14b.setMap(map);
            } else {
                kml_ql_14b.setMap(null);
            }

        } else {
            arr = arr_ql_14b;
        }
    }

    if (id == "ql_14e" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/ql14e.kml?time=" + version;
            if (kml_ql_14e == null) {
                kml_ql_14e = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_ql_14e;

            if (isShow) {
                kml_ql_14e.setMap(map);
            } else {
                kml_ql_14e.setMap(null);
            }

        } else {
            arr = arr_ql_14e;
        }
    }

    if (id == "duong_tinh" || id == "all_routes") {
        if (isShowKml) {
            kmlSrc = "http://quangnam.truotlo.com/data/diemtruot/duongtinh.kml?time=" + version;
            if (kml_duong_tinh == null) {
                kml_duong_tinh = new google.maps.KmlLayer(kmlSrc);
            }

            //kmlLayer = kml_duong_tinh;

            if (isShow) {
                kml_duong_tinh.setMap(map);
            } else {
                kml_duong_tinh.setMap(null);
            }

        } else {
            arr = arr_duong_tinh;
        }
    }

    if (id == "vi_tri_tram_wwo") {
        arr = arrStationMarker;

        // If show WWO, don't show subdistrict and vice versa
        for(var j=0; j< arrSubDistrictMarker.length; j++) {
            arrSubDistrictMarker[j].setVisible(!isShow);
        }

    }

    // Layer KML Phan Vung Khi Hau
    if (id == "phan_vung_khi_hau") {
        kmlSrc = "http://quangnam.truotlo.com/data/phan_vung_khi_hau.kml?time=" + version;

        if (kml_phan_vung_khi_hau == null) {
            kml_phan_vung_khi_hau = new google.maps.KmlLayer(kmlSrc);
        }

        //kmlLayer = kml_phan_vung_khi_hau;

        if (isShow) {
            kml_phan_vung_khi_hau.setMap(map);
        } else {
            kml_phan_vung_khi_hau.setMap(null);
        }

        arr = arr_phan_vung_khi_hau;

        // If show WWO, don't show subdistrict and vice versa
        for(var j=0; j< arrSubDistrictMarker.length; j++) {
            arrSubDistrictMarker[j].setVisible(!isShow);
        }
    }

    // Layer KML Dia chat
    if (id == "dia_chat") {
        kmlSrc = "http://quangnam.truotlo.com/data/diachat_50.kml?time=" + version;

        if (kml_dia_chat == null) {
            kml_dia_chat = new google.maps.KmlLayer(kmlSrc);
        }

        //kmlLayer = kml_dia_chat;

        if (isShow) {
            kml_dia_chat.setMap(map);
        } else {
            kml_dia_chat.setMap(null);
        }

        // If show WWO, don't show subdistrict and vice versa
        for(var j=0; j< arrSubDistrictMarker.length; j++) {
            arrSubDistrictMarker[j].setVisible(!isShow);
        }
    }

    if (arr != null) {
        for (var i=0; i< arr.length; i++) {
            arr[i].setVisible(isShow);
        }
    }


    // if (kmlLayer != null) {
    //     if (isShow) {
    //         kmlLayer.setMap(map);
    //     } else {
    //         kmlLayer.setMap(null);
    //     }
    // }

}

function loadDiemTruotMarker(arr, arr_data, icon) {
    for (var i=0; i< arr_data.length; i++) {
        var marker = loadMarker("", arr_data[i].latitude, arr_data[i].longitude, icon);
        arr.push(marker);
    }
}

function loadLabelMarker(arr, arr_data) {
    for (var i=0; i< arr_data.length; i++) {
        var marker = loadMarker(arr_data[i].name, arr_data[i].latitude, arr_data[i].longitude, "");
        arr.push(marker);
    }
}