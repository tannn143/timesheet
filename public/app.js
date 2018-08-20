QTHC = {};
QTHC.ajaxLoading = false;
QTHC.getWS = function(url, settings) {
    if (QTHC.ajaxLoading) return;
    var chain = $.ajax({
        'url' : QTHC.baseUrl + url,
        'type' : 'GET',
        'dataType' : 'json',
        'cache' : false,
        'beforeSend' : function() {
            QTHC.ajaxLoading = true;
            if (typeof settings.beforeSend != 'undefined') {
                settings.beforeSend();
            }
        }
    });
    if (typeof settings.onDone != 'undefined') {
        chain = chain.done(settings.onDone);
    }
    if (typeof settings.onFail != 'undefined') {
        chain = chain.fail(settings.onFail);
    }
    if (typeof settings.onFinally != 'undefined') {
        chain.always(function() {
            QTHC.ajaxLoading = false;
            settings.onFinally();
        });
    } else {
        chain.always(function() {
            QTHC.ajaxLoading = false;
        });
    }
};

QTHC.postWS = function(url, settings) {
    if (QTHC.ajaxLoading) return;
    var chain = $.ajax({
        'url' : QTHC.baseUrl + url,
        'type' : 'POST',
        'dataType' : 'json',
        'cache' : false,
        'data' : settings.data,
        'beforeSend' : function() {
            QTHC.ajaxLoading = true;
            if (typeof settings.beforeSend != 'undefined') {
                settings.beforeSend();
            }
        }
    });
    if (typeof settings.onDone != 'undefined') {
        chain = chain.done(settings.onDone);
    }
    if (typeof settings.onFail != 'undefined') {
        chain = chain.fail(settings.onFail);
    }
    if (typeof settings.onFinally != 'undefined') {
        chain.always(function() {
            QTHC.ajaxLoading = false;
            settings.onFinally();
        });
    } else {
        chain.always(function() {
            QTHC.ajaxLoading = false;
        });
    }
};

QTHC.alertSuccess = function(message) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-full-width",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "1500",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "slideUp"
    }
    toastr.success(message);
};

QTHC.alertError = function(message) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-full-width",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "1500",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "slideDown",
        "hideMethod": "slideUp"
    }
    toastr.error(message);
};

QTHC.popupSuccess = function(message) {
    swal("Thành công!", message, "success");
};

QTHC.popupError = function (message) {
    swal({
        title: "Lỗi!",
        text: message,
        type: "warning",
        confirmButtonText: 'OK'
    });
};

QTHC.confirm = function(message, callback) {
    swal({
            title: "Bạn có chắc chắn?",
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#F73939',
            confirmButtonText: 'OK, đồng ý!',
            cancelButtonText: 'Bỏ qua',
            closeOnConfirm: true
        },
        callback
    );
};

QTHC.formatNumber = function(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

QTHC.h = function(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
};


