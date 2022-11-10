$(document).ready(function () {
    $permissions = JSON.parse($('#permissions').val());
    console.log($permissions);

    $('#newTimeline').on('click', goToCreateNewTimeline)

});

var $permissions;

function goToCreateNewTimeline() {
    $.ajax({
        url: "/dashboard/get/timeline/current",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            if ( json.error == 1 )
            {
                toastr.error(json.message, 'Error',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });

            } else {
                toastr.success(json.message, 'Éxito',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
                setTimeout( function () {
                    location.href = json.url;
                }, 2000 )
            }

        }
    });
}

