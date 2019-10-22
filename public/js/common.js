var showErrorMessage = function(message) {
    toastr.error(message);
};


var isShowLoading = false;
function showLoading() {
    if (!isShowLoading) {
        $.LoadingOverlay("show");
        isShowLoading = true;
    }
}

function hideLoading() {
    if (isShowLoading) {
        $.LoadingOverlay("hide");
        isShowLoading = false;
    }
}