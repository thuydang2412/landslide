$(document).on("click", "#btnColorSetting", btnColorSettingOnClick);

function btnColorSettingOnClick(){
    var colorSettingArr = [];
    $(".color-setting").each(function () {
        var warningId = $(".warning-id", $(this)).val();
        var color = $(".input-color", $(this)).val();
        var fromNumber = $(".input-from", $(this)).val();
        var toNumber = $(".input-to", $(this)).val();
        colorSettingArr.push({'warningId': warningId, 'color' : color, 'fromNumber': fromNumber, 'toNumber': toNumber});
    });

    var jsonData = JSON.stringify(colorSettingArr);
    var data = {'data': jsonData};

    $.ajaxSetup(
    {
        headers:
        {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: "/admin/save-color",
        type: 'POST',
        data: data,
        beforeSend: function() {
        }
    }).done(function(response) {
        alert("Dữ liệu được lưu thành công");
    }).fail(function() {

    });
}