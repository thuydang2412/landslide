$(document).ready(function() {
    initPagePessl();
});


var tableColumnPessl = [
    {index: 0, key: "data_5_X_X_6_sum", title: "Lượng mưa (mm)"},
    {index: 1, key: "data_1_X_X_497_avg", title: "Nhiệt độ (°C)"},
];

var tableColumnVisiblePessl = [0, 1];
var graphTypeVisiblePessl = [];

function initPagePessl() {
    initFormDataPessl();
}

// // Load station information in case click on marker
// function loadInitStation() {
//     var stationId = $("#input-station-id").val();
//
//     if (stationId != "") {
//         $("#input-station").val(stationId).trigger('change');
//
//         // Search time 7 days
//         var $inputEndDate = $("#input-end-date");
//         $inputEndDate.val(moment().add(7, 'days').format('DD/MM/YYYY'));
//
//         // Check all option
//         $("#check-box-precip").attr('checked','checked');
//         $("#check-box-temp").attr('checked','checked');
//         $("#check-box-humi").attr('checked','checked');
//         $("#check-box-wind").attr('checked','checked');
//         doSearch();
//     }
// }

$(document).on('click', "#btn-search-pessl", doSearchPessl);

function initFormDataPessl() {
    // Calendar
    var $inputStartDate = $("#input-start-date-pessl");
    $inputStartDate.datepicker({
        dateFormat : 'dd/mm/yy',
    });
    $inputStartDate.val(moment().format('DD/MM/YYYY'));

    var $inputEndDate = $("#input-end-date-pessl");
    $inputEndDate.datepicker({
        dateFormat : 'dd/mm/yy',
    });
    $inputEndDate.val(moment().format('DD/MM/YYYY'));

    // Sub district
    var $inputStation = $("#input-station-pessl");
    var dataSourceStation = [];

    $.ajax({
        url: "/thoi-tiet/get-station-pessl",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(response) {
        var data =response.data;
        for (var i=0; i< data.length; i++) {
            dataSourceStation.push({id: data[i]["station_id"], text: data[i]["name"]});
        }
        $inputStation.select2({
            width: '100%',
            data : dataSourceStation,
            minimumResultsForSearch: Infinity,
        });

        //loadInitStation();

    }).fail(function() {

    });
}

function doSearchPessl() {
    showLoading();
    var $inputStation = $("#input-station-pessl");

    // Get list column search
    tableColumnVisiblePessl = [];
    $(".check-box-column-type-pessl").each(function() {
        if ($(this).is(":checked")) {
            tableColumnVisiblePessl.push($(this).val());
        }
    });

    // Get list grap search
    graphTypeVisiblePessl = [];
    $(".check-box-graph-type-pessl").each(function() {
        if ($(this).is(":checked")) {
            graphTypeVisiblePessl.push($(this).val());
        }
    });

    var stationId = $inputStation.val();
    var startDate = moment($("#input-start-date-pessl").val(), "DD/MM/YYYY").format("YYYY-MM-DD");
    var endDate = moment($("#input-end-date-pessl").val(), "DD/MM/YYYY").format("YYYY-MM-DD");

    var data = {stationId : $inputStation.val(), startDate : startDate, endDate : endDate};

    $.ajax({
        url: "/thoi-tiet/search-pessl",
        type: 'GET',
        data: data,
        beforeSend: function() {
        }
    }).done(function(response) {
        hideLoading();
        displayDataPessl(response.data);
    }).fail(function() {
        hideLoading();
    });
}

var dataTablePessl = null;
function fillToTablePessl(data) {
    if (dataTablePessl) {
        dataTablePessl.clear();
        dataTablePessl.destroy();
    }

    var tableData = $("#tbl-data-pessl");
    var tableRowHeader = tableData.find("#tbl-data-row-head");

    tableRowHeader.html("");
    tableRowHeader.append($("<th>Ngày</th>"));
    tableRowHeader.append($("<th>Giờ</th>"));

    for (var i = 0; i< tableColumnVisiblePessl.length; i++) {
        var title = tableColumn[tableColumnVisiblePessl[i]].title;
        tableRowHeader.append($("<th>" + title + "</th>"));
    }


    //if(!dataTable) {
        dataTablePessl = $('#tbl-data-pessl').DataTable({
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
        var time = item[1];
        if (time.length < 8) {
            item[1] = "0" + time;
        }

        dataTablePessl.row.add(item);
    }

    dataTablePessl.draw();
}

function displayDataPessl(data) {
    var arrDataSimpleFormat = [];
    var arrDataGraph = [];

    // for (var i=0; i<data.length; i++) {
    //     var dataLv1 = data[i];
    //
    //     var arrDataLv2 = dataLv1.lv2;
    //     for (var j=0; j<arrDataLv2.length; j++) {
    //         var dataLv2 = arrDataLv2[j];
    //         var date =  dataLv2.date;
    //         var arrDataLv3 = dataLv2.lv3;
    //         for (k=0; k<arrDataLv3.length; k++) {
    //             var dataLv3 = arrDataLv3[k];
    //             var time = dataLv3.time_val;
    //
    //             var dataSimpleFormat = [];
    //             dataSimpleFormat.push(date);
    //             dataSimpleFormat.push(time);
    //             for (var m=0; m<tableColumnVisible.length; m++) {
    //                 var keyData = tableColumn[tableColumnVisible[m]].key;
    //                 dataSimpleFormat.push(dataLv3[keyData]);
    //             }
    //
    //             arrDataSimpleFormat.push(dataSimpleFormat);
    //
    //             // Push graph data
    //             arrDataGraph.push({date: date, time: time, data: dataLv3});
    //         }
    //     }
    // }

    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        var date =  item.date_value;
        var time =  item.hour_value;

        var dataSimpleFormat = [];
        dataSimpleFormat.push(date);
        dataSimpleFormat.push(time);

        for (var m=0; m<tableColumnVisiblePessl.length; m++) {
            var keyData = tableColumnPessl[tableColumnVisiblePessl[m]].key;
            dataSimpleFormat.push(item[keyData]);
        }

        arrDataSimpleFormat.push(dataSimpleFormat);


        // Push graph data
        arrDataGraph.push({date: date, time: time, data: item});

    }

    fillToTablePessl(arrDataSimpleFormat);
    drawGraphPessl(arrDataGraph)
}


// Draw graph ///////////////////////////////////////////////////////////////////////////////////////////////////
var plot_precipmm;
var plot_tempC;

function drawGraphPessl(data) {
    var shouldDisplayGraphPrecipmm = graphTypeVisiblePessl.indexOf("0") >= 0 ? 1 : 0;
    var shouldDisplayGraphTempC= graphTypeVisiblePessl.indexOf("1") >= 0 ? 1 : 0;

    var dataGraphPrecipmm = [];
    dataGraphPrecipmm.length = 0;

    var dataGraphTempC = [];
    dataGraphTempC.length = 0;


    var i;
    var totalPrecipMM = 0;
    for(i=0; i<data.length; i++) {
        var item = data[i];

        var date = item.data.date_value;
        var time = item.data.hour_value;
        var precipMM = item.data['data_5_X_X_6_sum'];
        var tempC = item.data['data_1_X_X_497_avg'];

        var dateUnix = moment(date + " " + time, "YYYY-MM-DD HH:mm:ss").unix();
        totalPrecipMM += precipMM;
        dataGraphPrecipmm.push([dateUnix * 1000, totalPrecipMM]);

        dataGraphTempC.push([dateUnix * 1000, tempC]);
    }


    if (shouldDisplayGraphPrecipmm) {
        $("#precipmm-graph-panel-container-pessl").show();
        plot_precipmm = _drawGraph(plot_precipmm, 'precipmm-graph-panel-pessl', dataGraphPrecipmm, '<b>Ngày</b>', '<b></b>');
    } else {
        $("#precipmm-graph-panel-container-pessl").hide();
    }

    if (shouldDisplayGraphTempC) {
        $("#temp-graph-panel-container-pessl").show();
        plot_tempC = _drawGraph(plot_tempC, 'temp-graph-panel-pessl', dataGraphTempC, '<b>Ngày</b>', '<b></b>');
    } else {
        $("#temp-graph-panel-container-pessl").hide();
    }


}


/////////////////////////////////////////////////////////////////////////////////////////////////////