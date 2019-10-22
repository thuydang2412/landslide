$(document).on('click', '#popup-confirm-delete #btn-delete-confirm', btnConfirmDeletePhotoOnClick);

var confirmDeleteDelegate;

function showPopupConfirmDelete(_confirmDeleteDelegate) {
    $("#popup-confirm-delete").modal("toggle");
    confirmDeleteDelegate = _confirmDeleteDelegate;
}

function btnConfirmDeletePhotoOnClick() {
    $("#popup-confirm-delete").modal("toggle");
    if (confirmDeleteDelegate) {
        confirmDeleteDelegate();
    }
}