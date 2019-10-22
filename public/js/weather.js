$(document).ready(function() {
    initPage();
});


var tableColumn = [
    {index: 0, key: "precipMM", title: "Lượng mưa (mm)"},
    {index: 1, key: "tempC", title: "Nhiệt độ (°C)"},
    {index: 2, key: "humidity", title: "Độ ẩm (%)"},
    {index: 3, key: "pressure", title: "Áp suất"},
    {index: 4, key: "windspeedKmph", title: "Tốc độ gió (km/h)"},
];

var tableColumnVisible = [0, 1];
var graphTypeVisible = [];

function initPage() {
    initFormData();


}

// Load station information in case click on marker
function loadInitStation() {
    var stationId = $("#input-station-id").val();

    if (stationId != "") {
        $("#input-station").val(stationId).trigger('change');

        // Search time 7 days
        var $inputEndDate = $("#input-end-date");
        $inputEndDate.val(moment().add(7, 'days').format('DD/MM/YYYY'));

        // Check all option
        $("#check-box-precip").attr('checked','checked');
        $("#check-box-temp").attr('checked','checked');
        $("#check-box-humi").attr('checked','checked');
        $("#check-box-wind").attr('checked','checked');
        doSearch();
    }
}

$(document).on('click', "#btn-search", doSearch);

function initFormData() {
    // Calendar
    var $inputStartDate = $("#input-start-date");
    $inputStartDate.datepicker({
        dateFormat : 'dd/mm/yy',
    });
    $inputStartDate.val(moment().format('DD/MM/YYYY'));

    var $inputEndDate = $("#input-end-date");
    $inputEndDate.datepicker({
        dateFormat : 'dd/mm/yy',
    });
    $inputEndDate.val(moment().format('DD/MM/YYYY'));

    // Sub district
    var $inputStation = $("#input-station");
    var dataSourceStation = [];

    $.ajax({
        url: "/thoi-tiet/get-station",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(response) {
        var data =response.data;
        for (var i=0; i< data.length; i++) {
            dataSourceStation.push({id: data[i]["id"], text: data[i]["name"]});
        }
        $inputStation.select2({
            width: '100%',
            data : dataSourceStation,
            minimumResultsForSearch: Infinity,
        });

        loadInitStation();

    }).fail(function() {

    });
}

function doSearch() {
    showLoading();
    var $inputStation = $("#input-station");

    // Get list column search
    tableColumnVisible = [];
    $(".check-box-column-type").each(function() {
        if ($(this).is(":checked")) {
            tableColumnVisible.push($(this).val());
        }
    });

    // Get list grap search
    graphTypeVisible = [];
    $(".check-box-graph-type").each(function() {
        if ($(this).is(":checked")) {
            graphTypeVisible.push($(this).val());
        }
    });

    var stationId = $inputStation.val();
    var startDate = moment($("#input-start-date").val(), "DD/MM/YYYY").format("YYYY-MM-DD");
    var endDate = moment($("#input-end-date").val(), "DD/MM/YYYY").format("YYYY-MM-DD");

    var data = {stationId : $inputStation.val(), startDate : startDate, endDate : endDate};

    $.ajax({
        url: "/thoi-tiet/search",
        type: 'GET',
        data: data,
        beforeSend: function() {
        }
    }).done(function(response) {
        hideLoading();
        displayData(response.data);
    }).fail(function() {
        hideLoading();
    });
}

// function fillToTable(data) {
//     var $blockSearchContent = $("#block-search-content");
//     $blockSearchContent.html("");
//
//     var i, j, k;
//     for (i=0; i<data.length; i++) {
//         var dataLv1 = data[i];
//         var subDistrictName = dataLv1.sub_district.name;
//
//         var arrDataLv2 = dataLv1.lv2;
//         for (var j=0; j<arrDataLv2.length; j++) {
//             var dataLv2 = arrDataLv2[j];
//             var date =  moment(dataLv2.date, "YYYY-MM-DD").format("DD/MM/YYYY");
//             var arrDataLv3 = dataLv2.lv3;
//             for (k=0; k<arrDataLv3.length; k++) {
//                 var dataLv3 = arrDataLv3[k];
//                 var time = dataLv3.time_val/100 + ":00";
//                 var tempC = dataLv3.tempC;
//                 var precipMM = dataLv3.precipMM;
//
//                 var rowSearchTemplateHtml = $("#row-search-template").html();
//                 var $rowSearch = $(rowSearchTemplateHtml);
//
//                 $rowSearch.find(".district").text(subDistrictName);
//                 $rowSearch.find(".date").text(date);
//                 $rowSearch.find(".time").text(time);
//                 $rowSearch.find(".tempC").text(tempC);
//                 $rowSearch.find(".precipMM").text(precipMM);
//
//                 $blockSearchContent.append($rowSearch);
//             }
//         }
//     }
// }

var dataTable = null;
function fillToTable(data) {
    if (dataTable) {
        dataTable.clear();
        dataTable.destroy();
    }

    var tableData = $("#tbl-data");
    var tableRowHeader = tableData.find("#tbl-data-row-head");

    tableRowHeader.html("");
    tableRowHeader.append($("<th>Ngày</th>"));
    tableRowHeader.append($("<th>Giờ</th>"));

    for (var i = 0; i< tableColumnVisible.length; i++) {
        var title = tableColumn[tableColumnVisible[i]].title;
        tableRowHeader.append($("<th>" + title + "</th>"));
    }


    //if(!dataTable) {
        dataTable = $('#tbl-data').DataTable({
            "lengthChange": false,
            "searching": false,
            "columnDefs": [
                { orderable: false, targets: 1 },
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

        });
    //}

    for (var i = 0; i < data.length; i++) {
        var item = data[i];

        // date
        item[0] = moment(item[0], "YYYY-MM-DD").format("DD/MM/YYYY");
        // time
        item[1] = ("0" + item[1] / 100).slice(-2) + ":00";

        dataTable.row.add(item);
    }

    dataTable.draw();
}

function displayData(data) {
    var arrDataSimpleFormat = [];
    var arrDataGraph = [];

    for (var i=0; i<data.length; i++) {
        var dataLv1 = data[i];

        var arrDataLv2 = dataLv1.lv2;
        for (var j=0; j<arrDataLv2.length; j++) {
            var dataLv2 = arrDataLv2[j];
            var date =  dataLv2.date;
            var arrDataLv3 = dataLv2.lv3;
            for (k=0; k<arrDataLv3.length; k++) {
                var dataLv3 = arrDataLv3[k];
                var time = dataLv3.time_val;

                var dataSimpleFormat = [];
                dataSimpleFormat.push(date);
                dataSimpleFormat.push(time);
                for (var m=0; m<tableColumnVisible.length; m++) {
                    var keyData = tableColumn[tableColumnVisible[m]].key;
                    dataSimpleFormat.push(dataLv3[keyData]);
                }

                arrDataSimpleFormat.push(dataSimpleFormat);

                // Push graph data
                arrDataGraph.push({date: date, time: time, data: dataLv3});
            }
        }
    }

    fillToTable(arrDataSimpleFormat);
    drawGraph(arrDataGraph)
}


// Draw graph ///////////////////////////////////////////////////////////////////////////////////////////////////
var plot_precipmm;
var plot_tempC;
var plot_humidity;
var plot_pressure;
var plot_windSpeed;

function drawGraph(data) {
    var shouldDisplayGraphPrecipmm = graphTypeVisible.indexOf("0") >= 0 ? 1 : 0;
    var shouldDisplayGraphTempC= graphTypeVisible.indexOf("1") >= 0 ? 1 : 0;
    var shouldDisplayGraphHumidity = graphTypeVisible.indexOf("2") >= 0 ? 1 : 0;
    var shouldDisplayGraphPressure = graphTypeVisible.indexOf("3") >= 0 ? 1 : 0;
    var shouldDisplayGraphWindSpeed = graphTypeVisible.indexOf("4") >= 0 ? 1 : 0;

    var dataGraphPrecipmm = [];
    dataGraphPrecipmm.length = 0;

    var dataGraphTempC = [];
    dataGraphTempC.length = 0;

    var dataGraphHumidity = [];
    dataGraphHumidity.length = 0;

    var dataGraphPressure = [];
    dataGraphPressure.length = 0;

    var dataGraphWindSpeed = [];
    dataGraphWindSpeed.length = 0;

    var i;
    var totalPrecipMM = 0;
    for(i=0; i<data.length; i++) {
        var item = data[i];

        var date = item.date;
        var time = item.time;
        var precipMM = item.data['precipMM'];
        var tempC = item.data['tempC'];
        var humidity = item.data['humidity'];
        var pressure = item.data['pressure'];
        var windSpeed = item.data['windspeedKmph'];

        var dateUnix = moment(date, "YYYY-MM-DD").unix() + time/100 * 3600;
        totalPrecipMM += precipMM;
        dataGraphPrecipmm.push([dateUnix * 1000, totalPrecipMM]);

        dataGraphTempC.push([dateUnix * 1000, tempC]);
        dataGraphHumidity.push([dateUnix * 1000, humidity]);
        dataGraphPressure.push([dateUnix * 1000, pressure]);
        dataGraphWindSpeed.push([dateUnix * 1000, windSpeed]);
    }



    if (shouldDisplayGraphPrecipmm) {
        $("#precipmm-graph-panel-container").show();
        plot_precipmm = _drawGraph(plot_precipmm, 'precipmm-graph-panel', dataGraphPrecipmm, '<b>Ngày</b>', '<b></b>');
    } else {
        $("#precipmm-graph-panel-container").hide();
    }

    if (shouldDisplayGraphTempC) {
        $("#temp-graph-panel-container").show();
        plot_tempC = _drawGraph(plot_tempC, 'temp-graph-panel', dataGraphTempC, '<b>Ngày</b>', '<b></b>');
    } else {
        $("#temp-graph-panel-container").hide();
    }

    if (shouldDisplayGraphHumidity) {
        $("#humidity-graph-panel-container").show();
        plot_humidity = _drawGraph(plot_humidity, 'humidity-graph-panel', dataGraphHumidity, '<b>Ngày</b>', '<b></b>');
    } else {
        $("#humidity-graph-panel-container").hide();
    }

    if (shouldDisplayGraphPressure) {
        $("#pressure-graph-panel-container").show();
        plot_pressure = _drawGraph(plot_pressure, 'pressure-graph-panel', dataGraphPressure, '<b>Ngày</b>', '<b></b>');
    } else {
        $("#pressure-graph-panel-container").hide();
    }

    if (shouldDisplayGraphWindSpeed) {
        $("#wind-speed-graph-panel-container").show();
        plot_windSpeed = _drawGraph(plot_windSpeed, 'wind-speed-graph-panel', dataGraphWindSpeed, '<b>Ngày</b>', '<b></b>');
    } else {
        $("#wind-speed-graph-panel-container").hide();
    }

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

    return plot;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////