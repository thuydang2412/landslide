var URL_GET_KML_DATA = "map/kmlData";
var URL_GET_DISTRICT_INFO = "place/get-district";
var map;

var kmlMap = [];

$(document).on("change", ".cb-layer", cbLayerOnChange);

function initMap() {
    setupMap();
    loadKMLData();
}

function setupMap() {
    $.ajax({
        url: URL_GET_DISTRICT_INFO,
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(result) {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: result.data.lat, lng: result.data.lon},
            zoom: 10,
            styles: [
                {
                    "featureType": "landscape",
                    "stylers": [
                        {
                            "hue": "#FFBB00"
                        },
                        {
                            "saturation": 43.400000000000006
                        },
                        {
                            "lightness": 37.599999999999994
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "stylers": [
                        {
                            "hue": "#FFC200"
                        },
                        {
                            "saturation": -61.8
                        },
                        {
                            "lightness": 45.599999999999994
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "stylers": [
                        {
                            "hue": "#FF0300"
                        },
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": 51.19999999999999
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "stylers": [
                        {
                            "hue": "#FF0300"
                        },
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": 52
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "stylers": [
                        {
                            "hue": "#0078FF"
                        },
                        {
                            "saturation": -13.200000000000003
                        },
                        {
                            "lightness": 2.4000000000000057
                        },
                        {
                            "gamma": 1
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "stylers": [
                        {
                            "hue": "#00FF6A"
                        },
                        {
                            "saturation": -1.0989010989011234
                        },
                        {
                            "lightness": 11.200000000000017
                        },
                        {
                            "gamma": 1
                        }
                    ]
                }
            ]

        });
    }).fail(function() {

    });
}

function loadKMLData() {
    $.ajax({
        url: URL_GET_KML_DATA,
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(result) {
        kmlMap = [];
        for(var i=0; i<result.length; i++) {
            var item = result[i];
            kmlMap.push({id: item.kml_id, name: item.kml_name, fileName: item.kml_file_name, kmlObject: null});
        }

        generateLeftPanel(result);
        loadKMLLayer();
    }).fail(function() {

    });
}

function generateLeftPanel(result) {
    var $leftSelectPanel = $("#left_select_panel");

    for(var i=0; i<result.length; i++) {
        var item = result[i];
        var $checkBoxTemplate = $($("#check_box_template").html());
        $checkBoxTemplate.find(".cb-layer").attr("layer-id", item.kml_id);
        $checkBoxTemplate.find(".layer-name").text(item.kml_name);
        $leftSelectPanel.append($checkBoxTemplate);
    }
}

function loadKMLLayer() {

    // var marker = new google.maps.Marker({
    //     position: { lat: 22.498156, lng: 104.531178 },
    //     label: "Xín Mần",
    //     map: map
    // });

    var i;
    for (i=0; i< kmlMap.length; i++) {
        var kmlItem = kmlMap[i];

        //var src = "/data/Soil.kml";
        var src = kmlItem.fileName + "?time=10";

        var kmlLayer = new google.maps.KmlLayer(src);
        kmlItem.kmlObject = kmlLayer;

        // Background
        // if (i ==0) {
        //     kmlLayer.setMap(map);
        // }

        kmlLayer.addListener('click', function(kmlEvent) {
            console.log(kmlEvent);
            var text = kmlEvent.featureData.description;
            //console.log(text);
        });
    }
}

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
    var selectedLayer = null;
    for (var i=0; i<kmlMap.length; i++) {
        if (kmlMap[i].id === id) {
            selectedLayer = kmlMap[i].kmlObject;
        }
    }

    if (selectedLayer != null) {
        if (isShow) {
            selectedLayer.setMap(map);
        } else {
            selectedLayer.setMap(null);
        }
    }
}