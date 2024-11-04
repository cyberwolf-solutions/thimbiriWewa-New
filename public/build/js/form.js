$(document).ready(function () {
    $(document).on('submit', '.ajax-form', function (event) {
        event.preventDefault();

        var formData = new FormData(this);
        var form = $(this);

        $('#loader').removeClass('d-none');

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response) {
                $('#loader').addClass('d-none');
                if (response.success) {
                    display_success(response.message); // replace with your success message
                } else {
                    display_error(response.message); // replace with your error message
                }
                if (response.url) {
                    setTimeout(function () {
                        window.location.href = response.url;
                    }, 1500);
                }

                if (response.modal) {
                    $('#commonModal').modal('hide');
                }
            },
            error: function (xhr) {
                $('#loader').addClass('d-none');
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                display_error(errorMessage);
            }
        });
    });
    $(".main-content").on("click", '.delete_confirm', function () {
        Swal.fire({
            title: "Are you sure you want to delete this item?",
            text: "You won't be able to revert this!",
            icon: "warning",
            confirmButtonColor: "#01ac62",
            cancelButtonColor: "#696969",
            confirmButtonText: "Yes, confirm!",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                let url = $(this).data('url');
                $('#loader').removeClass('d-none');
                $.ajax({
                    url: url,
                    type: "DELETE",
                    dataType: "JSON",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        $('#loader').addClass('d-none');
                        if (response.success) {
                            display_success(response.message); // replace with your success message
                        } else {
                            display_error(response.message); // replace with your error message
                        }
                        if (response.url) {
                            setTimeout(function () {
                                window.location.href = response.url;
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        $('#loader').addClass('d-none');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        display_error(errorMessage);
                    }
                });
            }
        });
        return false;
    });
    $(".main-content").on("click", '.post_confirm', function (e) {
        e.preventDefault();
        Swal.fire({
            title: $(this).data('title'),
            text: "You won't be able to revert this!",
            icon: "warning",
            confirmButtonColor: "#01ac62",
            cancelButtonColor: "#696969",
            confirmButtonText: "Yes, confirm!",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                let url = $(this).data('url');
                $('#loader').removeClass('d-none');
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        $('#loader').addClass('d-none');
                        if (response.success) {
                            display_success(response.message); // replace with your success message
                        } else {
                            display_error(response.message); // replace with your error message
                        }
                        if (response.url) {
                            setTimeout(function () {
                                window.location.href = response.url;
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        $('#loader').addClass('d-none');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        display_error(errorMessage);
                    }
                });
            }
        });
        return false;
    });

    $(".main-content").on("click", '.confirm', function () {
        Swal.fire({
            title: $(this).data('title'),
            text: "You won't be able to revert this!",
            icon: "warning",
            confirmButtonColor: "#01ac62",
            cancelButtonColor: "#696969",
            confirmButtonText: "Yes, confirm!",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                let url = $(this).data('url');
                $('#loader').removeClass('d-none');
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "JSON",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        $('#loader').addClass('d-none');
                        if (response.success) {
                            display_success(response.message); // replace with your success message
                        } else {
                            display_error(response.message); // replace with your error message
                        }
                        if (response.url) {
                            setTimeout(function () {
                                window.location.href = response.url;
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        $('#loader').addClass('d-none');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        display_error(errorMessage);
                    }
                });
            }
        });
        return false;
    });

    $(".main-content").on("click", '.show-more', function () {
        $('#loader').removeClass('d-none');
        $.ajax({
            url: $(this).data('url'),
            type: "GET",
            data: {
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                $('#loader').addClass('d-none');
                $('#showCanvas').offcanvas('show');
                $('#showCanvas #body').html(response);
            },
            error: function (xhr) {
                $('#loader').addClass('d-none');
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                display_error(errorMessage);
            }
        });
    });

    $(".main-content").on("click", '.send-post-ajax', function () {
        $('#loader').removeClass('d-none');
        var data = $(this).data('data');
        $.ajax({
            url: $(this).data('url'),
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: data,
            dataType: "JSON",
            success: function (response) {
                $('#loader').addClass('d-none');
                if (response.success) {
                    display_success(response.message); // replace with your success message
                } else {
                    display_error(response.message); // replace with your error message
                }
                if (response.url) {
                    setTimeout(function () {
                        window.location.href = response.url;
                    }, 1500);
                }
            },
            error: function (xhr) {
                $('#loader').addClass('d-none');
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                display_error(errorMessage);
            }
        });
    });

});


$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {
    $('#loader').removeClass('d-none');
    var title1 = $(this).data("title");
    var title2 = $(this).data("bs-original-title");
    var title = (title1 != undefined) ? title1 : title2;
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var location = ($(this).data('location') == '') ? '' : $(this).data('location');
    var url = $(this).data('url');
    var binding = ($(this).attr('data-binding') == undefined) ? '' : $(this).attr('data-binding');
    url += binding;
    var scrollable = $(this).data('scrollable');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $("#commonModal .modal-dialog").addClass('modal-dialog-' + location);
    if (scrollable) {
        $("#commonModal .modal-dialog").addClass(' modal-dialog-scrollable');
    }
    $.ajax({
        url: url,
        success: function (data) {
            $('#loader').addClass('d-none');
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');

            /**
     * flatpickr
     */
            var flatpickrExamples = document.querySelectorAll("[data-provider]");
            Array.from(flatpickrExamples).forEach(function (item) {
                if (item.getAttribute("data-provider") == "flatpickr") {
                    var dateData = {};
                    var isFlatpickerVal = item.attributes;
                    dateData.disableMobile = "true";
                    if (isFlatpickerVal["data-date-format"])
                        dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                    if (isFlatpickerVal["data-enable-time"]) {
                        (dateData.enableTime = true),
                            (dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString() + " H:i");
                    }
                    if (isFlatpickerVal["data-altFormat"]) {
                        (dateData.altInput = true),
                            (dateData.altFormat = isFlatpickerVal["data-altFormat"].value.toString());
                    }
                    if (isFlatpickerVal["data-minDate"]) {
                        dateData.minDate = isFlatpickerVal["data-minDate"].value.toString();
                        dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                    }
                    if (isFlatpickerVal["data-maxDate"]) {
                        dateData.maxDate = isFlatpickerVal["data-maxDate"].value.toString();
                        dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                    }
                    if (isFlatpickerVal["data-deafult-date"]) {
                        dateData.defaultDate = isFlatpickerVal["data-deafult-date"].value.toString();
                        dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                    }
                    if (isFlatpickerVal["data-multiple-date"]) {
                        dateData.mode = "multiple";
                        dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                    }
                    if (isFlatpickerVal["data-range-date"]) {
                        dateData.mode = "range";
                        dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                    }
                    if (isFlatpickerVal["data-inline-date"]) {
                        (dateData.inline = true),
                            (dateData.defaultDate = isFlatpickerVal["data-deafult-date"].value.toString());
                        dateData.dateFormat = isFlatpickerVal["data-date-format"].value.toString();
                    }
                    if (isFlatpickerVal["data-disable-date"]) {
                        var dates = [];
                        dates.push(isFlatpickerVal["data-disable-date"].value);
                        dateData.disable = dates.toString().split(",");
                    }
                    if (isFlatpickerVal["data-week-number"]) {
                        var dates = [];
                        dates.push(isFlatpickerVal["data-week-number"].value);
                        dateData.weekNumbers = true
                    }
                    flatpickr(item, dateData);
                } else if (item.getAttribute("data-provider") == "timepickr") {
                    var timeData = {};
                    var isTimepickerVal = item.attributes;
                    if (isTimepickerVal["data-time-basic"]) {
                        (timeData.enableTime = true),
                            (timeData.noCalendar = true),
                            (timeData.dateFormat = "H:i");
                    }
                    if (isTimepickerVal["data-time-hrs"]) {
                        (timeData.enableTime = true),
                            (timeData.noCalendar = true),
                            (timeData.dateFormat = "H:i"),
                            (timeData.time_24hr = true);
                    }
                    if (isTimepickerVal["data-min-time"]) {
                        (timeData.enableTime = true),
                            (timeData.noCalendar = true),
                            (timeData.dateFormat = "H:i"),
                            (timeData.minTime = isTimepickerVal["data-min-time"].value.toString());
                    }
                    if (isTimepickerVal["data-max-time"]) {
                        (timeData.enableTime = true),
                            (timeData.noCalendar = true),
                            (timeData.dateFormat = "H:i"),
                            (timeData.minTime = isTimepickerVal["data-max-time"].value.toString());
                    }
                    if (isTimepickerVal["data-default-time"]) {
                        (timeData.enableTime = true),
                            (timeData.noCalendar = true),
                            (timeData.dateFormat = "H:i"),
                            (timeData.defaultDate = isTimepickerVal["data-default-time"].value.toString());
                    }
                    if (isTimepickerVal["data-time-inline"]) {
                        (timeData.enableTime = true),
                            (timeData.noCalendar = true),
                            (timeData.defaultDate = isTimepickerVal["data-time-inline"].value.toString());
                        timeData.inline = true;
                    }
                    flatpickr(item, timeData);
                }
            });

            // Reinitialize Select2
            $('.js-example-basic-single').select2();
            // daterange_set();
            // taskCheckbox();
            // common_bind("#commonModal");
            // commonLoader();
            // select2();
        },
        error: function (data) {
            $('#loader').addClass('d-none');

            data = data.responseJSON;
            display_error(data.error)
        }
    });

});

function display_success(msg) {
    Swal.fire({
        position: 'top-right',
        toast: true,
        icon: 'success',
        title: msg,
        timerProgressBar: true,
        showConfirmButton: false,
        timer: 3000
    });
}

function display_error(error) {
    Swal.fire({
        position: 'top-right',
        toast: true,
        icon: 'error',
        title: error,
        timerProgressBar: true,
        showConfirmButton: false,
        timer: 3000
    });
}
