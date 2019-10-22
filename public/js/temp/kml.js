var map;
function initMap() {
   basicMap();
}

var kmlLayer;

$(document).on('click', '#btnShow', function(event) {
    kmlLayer.setMap(map);
});

$(document).on('click', '#btnHide', function(event) {
    kmlLayer.setMap(null);
});

function basicMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 22.502913, lng: 104.535469},
        //center: {lat: -19.257753, lng: 146.823688},
        zoom: 10,
        //mapTypeId: 'terrain'
    });

    //var src = "http://canhbao.local/data/Soil.kml";
    var src = "http://canhbao.english4life.info/data/Soil.kml";

    //var kmzLayer = new google.maps.KmlLayer(src);
    //kmzLayer.setMap(map);

    kmlLayer = new google.maps.KmlLayer(src, {
          suppressInfoWindows: true,
          preserveViewport: false,
          map: map
        });

    kmlLayer.addListener('click', function(kmlEvent) {
        console.log(kmlEvent);
        var text = kmlEvent.featureData.description;
        //console.log(text);
      });
}