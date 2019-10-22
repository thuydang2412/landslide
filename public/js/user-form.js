
$(document).on('click', '.form-crud .submit-action', btnSubmitOnClick);
$(document).on('click', '.form-crud .delete-action', btnDeleteOnClick);


function btnSubmitOnClick($e) {
    $e.preventDefault();

    var $form = $(this).parents(".form-crud");

    // Check userId exist
    var userId = $("#userId").val();
    if (userId !== "") {
        // Edit
        $form.attr("action", "/admin/edit-user/" + userId);
    } else {
        // Create
        $form.attr("action", "/admin/create-user");
    }

    // Set permissions list
    var listPermissions = [];
    $(".input-check-permission").each(function() {
        if($(this).prop("checked")) {
            listPermissions.push($(this).attr('permission-id'));
        }
    });

    $("#input-permission").val(listPermissions.join());

    $form.submit();
}

function btnDeleteOnClick($e) {
    $e.preventDefault();
    var self = $(this);
    showPopupConfirmDelete(function () {
        var $form = self.parents(".form-crud");
        var userId = $("#userId").val();
        $form.attr("action", "/admin/delete-user/" + userId);
        $form.submit();

    });
}