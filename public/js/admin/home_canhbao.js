$(document).ready(function() {

    $.ajax({
        url: "/trang-chu/warning-canh-bao",
        type: 'GET',
        beforeSend: function() {
        }
    }).done(function(response) {

        var html = response.html;
        $("#popup-warning-content").html(html);

        $("#warningPopupModal").modal({
            backdrop: 'static',
            toggle: 'true'
        });

    }).fail(function() {

    });

});