$(document).ready(function () {
    $.ajax({
        url: "/api/sunat",
        type: 'GET',
        dataType: 'json',
        success: function (json) {
            console.log(json.compra);
            $('#tasaCompra').html('Compra: '+json.compra);
            $('#tasaVenta').html('Venta: '+json.venta);
        }
    });

    $.ajax({
        url: "/dashboard/get/notifications",
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data.notifications);
            var quantity_notifications_unread = 0;
            for( var i = 0; i<data.notifications.length; i++ )
            {
                //console.log(data.notifications[i]);
                if ( data.notifications[i].read == 0 )
                {
                    //console.log(data.notifications[i].read);
                    quantity_notifications_unread = quantity_notifications_unread + 1;
                    renderTemplateNotificationUnread( data.notifications[i] )
                }

            }
            for( var j = 0; j<data.notifications.length; j++ )
            {
                //console.log(data.notifications[i]);
                if ( data.notifications[j].read == 1 )
                {
                    renderTemplateNotificationRead( data.notifications[j] )
                }

            }
            //console.log(quantity_notifications_unread);
            if ( quantity_notifications_unread > 0 )
            {
                $('#total_notifications').show();
                $('#read-all').show();
                $('#total_notifications').html(quantity_notifications_unread);
                $('#quantity_notifications').html(quantity_notifications_unread + ' notificación(es)');
            } else {
                $('#total_notifications').hide();
                $('#read-all').hide();
                $('#quantity_notifications').html(quantity_notifications_unread + ' notificación(es)');

            }

        }
    });

    $(document).on('click', '[data-read]', readNotification);
    $('#read-all').on('click', readAllNotification);

});

function readAllNotification() {
    $.confirm({
        icon: 'fas fa-check-double',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de marcar como leído todas las notificaciones?',
        content: '',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/leer/todas/notificaciones',
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            //console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Proceso cancelado.");
                },
            },
        }
    });
}

function readNotification() {
    var id_notification_user = $(this).attr('data-read');
    var content_message = $(this).attr('data-content');
    $.confirm({
        icon: 'fas fa-check-double',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de marcar como leído esta notificación?',
        content: content_message,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/read/notification/'+id_notification_user,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            //console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Proceso cancelado.");
                },
            },
        }
    });
}

function renderTemplateNotificationUnread(notification) {
    var clone = activateTemplate('#notification-unread');
    clone.querySelector("[data-message]").innerHTML = notification.message;
    clone.querySelector("[data-time]").innerHTML = notification.time;
    clone.querySelector("[data-read]").setAttribute('data-read', notification.id_notification_user);
    clone.querySelector("[data-read]").setAttribute('data-content', notification.message);
    clone.querySelector("[data-go]").setAttribute('href', notification.url_go);

    $('#body-notifications').append(clone);
}

function renderTemplateNotificationRead(notification) {
    var clone = activateTemplate('#notification-read');
    clone.querySelector("[data-message]").innerHTML = notification.message;
    clone.querySelector("[data-time]").innerHTML = notification.time;
    clone.querySelector("[data-go]").setAttribute('href', notification.url_go);

    $('#body-notifications').append(clone);
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

