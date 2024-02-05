$(document).ready(function () {
    $('#sandbox-container .input-daterange').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
        autoclose: true
    });
    $permissions = JSON.parse($('#permissions').val());
    //console.log($permissions);
    $('#dynamic-table').DataTable( {
        ajax: {
            url: "/dashboard/all/quotes/general",
            dataSrc: 'data'
        },
        bAutoWidth: false,
        "aoColumns": [
            { data: 'id' },
            { data: 'code' },
            { data: 'description_quote' },
            { data: null,
                title: 'Fecha Cotización',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.date_quote).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Fecha Válida',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.date_validate).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Forma de pago',
                wrap: true,
                "render": function (item)
                {
                    if ( item.deadline !== null )
                        return '<p> '+ item.deadline.description +'</p>';
                    else
                        return '<p>'+ item.way_to_pay +' </p>'
                }
            },
            { data: null,
                title: 'Tiempo Entrega',
                wrap: true,
                "render": function (item)
                {
                    if ( item.time_delivery == null || item.time_delivery == "" )
                        return "";
                    else
                        return item.time_delivery +' DíAS';
                }
            },
            { data: null,
                title: 'Cliente',
                wrap: true,
                "render": function (item)
                {
                    if ( item.customer !== null )
                        return item.customer.business_name;
                    else
                        return '<p> Sin cliente </p>'
                }
            },
            { data: null,
                title: 'Orden Servicio',
                wrap: true,
                "render": function (item)
                {
                    if ( item.code_customer !== null )
                        return '<p> '+ item.code_customer +'</p>';
                    else
                        return '<p> Sin código </p>'
                }
            },
            { data: null,
                title: 'Total Sin IGV',
                wrap: true,
                "render": function (item)
                {
                    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
                        return ((item.total_quote)/1.18).toFixed(0);
                    } else {
                        return '';
                    }

                }
            },
            { data: null,
                title: 'Total Con IGV',
                wrap: true,
                "render": function (item)
                {
                    if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
                        return ((item.total_quote)/1).toFixed(0);
                    } else {
                        return '';
                    }

                }
            },

            { data: null,
                title: 'Moneda',
                wrap: true,
                "render": function (item)
                {
                    var text = '';
                    if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
                        text = text + '<p> '+ item.currency_invoice +'</p>'
                    }
                    return text;
                }
            },
            { data: null,
                title: 'Estado',
                wrap: true,
                "render": function (item)
                {
                    if ( item.state === 'created' ) {
                        //return '<span class="badge bg-primary">Creada</span>';
                        if ( item.send_state == 1 || item.send_state == true )
                        {
                            return '<span class="badge bg-warning">Enviado</span>';
                        } else {
                            return '<span class="badge bg-primary">Creada</span>';
                        }
                    }

                    if (item.state_active === 'close'){
                        return '<span class="badge bg-danger">Finalizada</span>';
                    } else {
                        if (item.state === 'confirmed' && item.raise_status === 1){
                            if ( item.vb_finances == 1 && item.vb_operations == null )
                            {
                                return '<span class="badge bg-gradient-navy text-white">V.B. Finanzas <br> '+moment(item.date_vb_finances).format('DD/MM/YYYY')+'</span>';
                            } else {
                                if ( item.vb_finances == 1 && item.vb_operations == 1 )
                                {
                                    return '<span class="badge bg-gradient-orange text-white">V.B. Operaciones <br> '+moment(item.date_vb_operations).format('DD/MM/YYYY')+'</span>';
                                } else {
                                    if ( item.vb_finances == null && item.vb_operations == null )
                                    {
                                        return '<span class="badge bg-success">Elevada</span>';
                                    }
                                }
                            }
                        }
                        if (item.state === 'confirmed' && item.raise_status === 0){
                            return '<span class="badge bg-success">Confirmada</span>';
                        }
                        if (item.state === 'canceled'){
                            return '<span class="badge bg-danger">Cancelada</span>';
                        }
                    }

                }
            },
            { data: null,
                title: 'Fecha Creación',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ moment(item.created_at).format('DD-MM-YYYY') +'</p>'
                }
            },
            { data: null,
                title: 'Creador',
                wrap: true,
                "render": function (item)
                {
                    return '<p> '+ item.users[0].user.name +'</p>'
                }
            },
            { data: null,
                title: '',
                wrap: true,
                sortable:false,
                "render": function (item)
                {
                    var text = '';
                    if ( $.inArray('show_quote', $permissions) !== -1 ) {
                        text = text + '<a href="'+document.location.origin+ '/dashboard/ver/cotizacion/'+item.id+
                            '" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Ver Detalles"><i class="fa fa-eye"></i></a> ';
                    }
                    if ( $.inArray('update_quote', $permissions) !== -1 ) {
                        text = text + '<a href="'+document.location.origin+ '/dashboard/editar/planos/cotizacion/'+item.id+
                            '" class="btn bg-lime color-palette btn-sm" data-toggle="tooltip" data-placement="top" title="Editar planos"><i class="fas fa-images"></i></a> ';
                    }
                    /*if ( item.state === 'confirmed' ) {
                        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
                            text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +
                                '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a> ';
                        }
                        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
                            text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/interno/' + item.id +
                                '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir interna"><i class="fa fa-print"></i></a> ';
                        }
                    }*/

                    if ( item.state === 'created' ) {
                        //if ( $.inArray('showPrices_quote', $permissions) !== -1 ) {
                            if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
                                text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +
                                    '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a> ';
                            }
                            if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
                                text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/interno/' + item.id +
                                    '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir interna"><i class="fa fa-print"></i></a> ';
                            }
                            if ( item.send_state == 0 || item.send_state == false )
                            {
                                if ( $.inArray('send_quote', $permissions) !== -1 ) {
                                    text = text + ' <button data-send="' + item.id + '" data-name="' + item.description_quote + '" ' +
                                        ' class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Enviar"><i class="fas fa-file-import"></i></button>';
                                }
                            }

                        //}
                        if ( $.inArray('update_quote', $permissions) !== -1 ) {
                            text = text + '<a href="'+document.location.origin+ '/dashboard/editar/cotizacion/'+item.id+
                                '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pen"></i></a> ';
                        }
                        if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-status="'+ item.send_state +'" data-confirm="'+item.id+'" data-name="'+item.description_quote+'" '+
                                ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Confirmar"><i class="fa fa-check"></i></button>';
                        }
                        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-delete="'+item.id+'" data-name="'+item.description_quote+'" '+
                                ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-trash"></i></button>';
                        }
                    }

                    if ( item.state === 'confirmed' ) {
                        if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
                            text = text + ' <button data-delete="'+item.id+'" data-name="'+item.description_quote+'" '+
                                ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-trash"></i></button>';
                        }
                        if ( item.state === 'confirmed' && item.raise_status == 0 )
                        {
                            /*if ( $.inArray('adjust_quote', $permissions) !== -1 ) {
                                text = text + '<a href="'+document.location.origin+ '/dashboard/ajustar/cotizacion/'+item.id+
                                    '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Ajustar porcentajes"><i class="fas fa-percentage"></i></a> ';
                            }*/
                            if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
                                text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +
                                    '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a> ';
                            }
                            if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
                                text = text + '<a target="_blank" href="'+document.location.origin+ '/dashboard/imprimir/interno/'+item.id+
                                    '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir interna"><i class="fa fa-print"></i></a> ';
                            }
                            if ( $.inArray('adjust_quote', $permissions) !== -1 ) {
                                text = text + '<a href="'+document.location.origin+ '/dashboard/ajustar/cotizacion/'+item.id+
                                    '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Ajustar porcentajes"><i class="fas fa-percentage"></i></a> ';
                            }
                            if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                text = text + ' <button data-raise="' + item.id + '" data-code="' + item.code_customer + '" data-name="' + item.description_quote + '" ' +
                                    ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Elevar"><i class="fa fa-level-up-alt"></i></button>';
                            }
                            if ( $.inArray('destroy_quote', $permissions) !== -1 ) {
                                text = text + ' <button data-delete="'+item.id+'" data-name="'+item.description_quote+'" '+
                                    ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-trash"></i></button>';
                            }
                            /*if ( $.inArray('renew_quote', $permissions) !== -1 ) {
                                text = text + ' <button data-renew="'+item.id+'" data-name="'+item.description_quote+'" '+
                                    ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Recotizar"><i class="fas fa-sync"></i></button>';
                            }*/
                            if ( item.total_soles == 0 ) {
                                if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
                                    text = text + '<a href="'+document.location.origin+ '/dashboard/cotizar/soles/cotizacion/'+item.id+
                                        '" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Cotizar en soles"><i class="fa fa-dollar-sign"></i></a> ';
                                }
                            }
                        }

                        if ( item.state === 'confirmed' && item.raise_status === 1 ) {
                            /*if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                text = text + ' <button data-raise2="'+item.id+'" data-code="'+item.code_customer+'" data-name="'+item.description_quote+'" '+
                                    ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar código"><i class="fa fa-chart-line"></i></button>';
                            }*/
                            /*if ( $.inArray('finish_quote', $permissions) !== -1 ) {
                                text = text + ' <button data-finish="'+item.id+'" data-name="'+item.description_quote+'" '+
                                    ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fas fa-window-close"></i></button>';
                            }
                            // TODO: Boton para reemplazar materiales
                            if ( $.inArray('replacement_quote', $permissions) !== -1 ) {
                                text = text + '<a href="'+document.location.origin+ '/dashboard/reemplazar/materiales/cotizacion/'+item.id+
                                    '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Reemplazar materiales"><i class="fas fa-recycle"></i></a> ';
                            }
                            if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
                                text = text + '<a href="'+document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+item.id+
                                    '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar equipos"><i class="fas fa-times-circle"></i></i></a> ';
                            }
                            //TODO: Boton de deselevar
                            if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                text = text + ' <button data-deselevar="'+item.id+'" data-name="'+item.description_quote+'" '+
                                    ' class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Regresar a enviado"><i class="fas fa-level-down-alt"></i></button>';
                            }*/
                            if ( item.vb_finances == 1 && item.vb_operations == null )
                            {
                                if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
                                    text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +
                                        '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a> ';
                                }
                                if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
                                    text = text + '<a target="_blank" href="'+document.location.origin+ '/dashboard/imprimir/interno/'+item.id+
                                        '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir interna"><i class="fa fa-print"></i></a> ';
                                }
                                if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                    text = text + ' <button data-raise2="'+item.id+'" data-code="'+item.code_customer+'" data-name="'+item.description_quote+'" '+
                                        ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar código"><i class="fa fa-chart-line"></i></button>';
                                }
                                /*if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                    text = text + ' <button data-detraction="'+item.id+'" data-name="'+item.description_quote+'" '+
                                        ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Seleccionar detracción"><i class="fas fa-donate"></i></button>';
                                }*/
                                if ( $.inArray('finish_quote', $permissions) !== -1 ) {
                                    text = text + ' <button data-finish="'+item.id+'" data-name="'+item.description_quote+'" '+
                                        ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fas fa-window-close"></i></button>';
                                }
                                if ( $.inArray('replacement_quote', $permissions) !== -1 ) {
                                    text = text + '<a href="'+document.location.origin+ '/dashboard/reemplazar/materiales/cotizacion/'+item.id+
                                        '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Reemplazar materiales"><i class="fas fa-recycle"></i></a> ';
                                }
                                if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
                                    text = text + '<a href="'+document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+item.id+
                                        '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar equipos"><i class="fas fa-times-circle"></i></i></a> ';
                                }
                                /*return '<span class="badge bg-gradient-navy text-white">V.B. Finanzas - '+moment(item.date_vb_finances).format('DD/MM/YYYY')+'</span>';*/
                                // TODO: Boton para dar visto bueno operaciones Cambiar el permiso
                                if ( $.inArray('VBOperations_quote', $permissions) !== -1 ) {
                                    text = text + ' <button data-vb_operations="'+item.id+'" data-name="'+item.description_quote+'" '+
                                        ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Visto bueno de operaciones"><i class="fas fa-check-double"></i></button>';
                                }
                                /*if ( $.inArray('renew_quote', $permissions) !== -1 ) {
                                    text = text + ' <button data-renew="'+item.id+'" data-name="'+item.description_quote+'" '+
                                        ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Recotizar"><i class="fas fa-sync"></i></button>';
                                }*/
                            } else {
                                if ( item.vb_finances == 1 && item.vb_operations == 1 )
                                {
                                    if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
                                        text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +
                                            '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a> ';
                                    }
                                    if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
                                        text = text + '<a target="_blank" href="'+document.location.origin+ '/dashboard/imprimir/interno/'+item.id+
                                            '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir interna"><i class="fa fa-print"></i></a> ';
                                    }
                                    if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                        text = text + ' <button data-raise2="'+item.id+'" data-code="'+item.code_customer+'" data-name="'+item.description_quote+'" '+
                                            ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar código"><i class="fa fa-chart-line"></i></button>';
                                    }
                                    /*if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                        text = text + ' <button data-detraction="'+item.id+'" data-name="'+item.description_quote+'" '+
                                            ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Seleccionar detracción"><i class="fas fa-donate"></i></button>';
                                    }*/
                                    if ( $.inArray('finish_quote', $permissions) !== -1 ) {
                                        text = text + ' <button data-finish="'+item.id+'" data-name="'+item.description_quote+'" '+
                                            ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fas fa-window-close"></i></button>';
                                    }
                                    if ( $.inArray('replacement_quote', $permissions) !== -1 ) {
                                        text = text + '<a href="'+document.location.origin+ '/dashboard/reemplazar/materiales/cotizacion/'+item.id+
                                            '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Reemplazar materiales"><i class="fas fa-recycle"></i></a> ';
                                    }
                                    if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
                                        text = text + '<a href="'+document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+item.id+
                                            '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar equipos"><i class="fas fa-times-circle"></i></i></a> ';
                                    }
                                    /*return '<span class="badge bg-gradient-orange">V.B. Operaciones - '+moment(item.date_vb_operations).format('DD/MM/YYYY')+'</span>';*/
                                    // TODO: Boton para editar materiales Cambiar el permiso
                                    if ( $.inArray('update_quote', $permissions) !== -1 ) {
                                        text = text + '<a href="'+document.location.origin+ '/dashboard/modificar/lista/materiales/cotizacion/'+item.id+
                                            '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Editar lista materiales"><i class="fas fa-edit"></i></a> ';
                                    }
                                    /*if ( $.inArray('renew_quote', $permissions) !== -1 ) {
                                        text = text + ' <button data-renew="'+item.id+'" data-name="'+item.description_quote+'" '+
                                            ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Recotizar"><i class="fas fa-sync"></i></button>';
                                    }*/
                                } else {
                                    if ( item.vb_finances == null && item.vb_operations == null )
                                    {
                                        if ( $.inArray('printCustomer_quote', $permissions) !== -1 ) {
                                            text = text + '<a target="_blank" href="' + document.location.origin + '/dashboard/imprimir/cliente/' + item.id +
                                                '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir para cliente"><i class="fa fa-print"></i></a> ';
                                        }
                                        if ( $.inArray('printInternal_quote', $permissions) !== -1 ) {
                                            text = text + '<a target="_blank" href="'+document.location.origin+ '/dashboard/imprimir/interno/'+item.id+
                                                '" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top" title="Imprimir interna"><i class="fa fa-print"></i></a> ';
                                        }
                                        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                            text = text + ' <button data-raise2="'+item.id+'" data-code="'+item.code_customer+'" data-name="'+item.description_quote+'" '+
                                                ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar código"><i class="fa fa-chart-line"></i></button>';
                                        }
                                        /*if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                            text = text + ' <button data-detraction="'+item.id+'" data-name="'+item.description_quote+'" '+
                                                ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Seleccionar detracción"><i class="fas fa-donate"></i></button>';
                                        }*/
                                        if ( $.inArray('finish_quote', $permissions) !== -1 ) {
                                            text = text + ' <button data-finish="'+item.id+'" data-name="'+item.description_quote+'" '+
                                                ' class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fas fa-window-close"></i></button>';
                                        }
                                        // TODO: Boton para reemplazar materiales
                                        if ( $.inArray('replacement_quote', $permissions) !== -1 ) {
                                            text = text + '<a href="'+document.location.origin+ '/dashboard/reemplazar/materiales/cotizacion/'+item.id+
                                                '" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Reemplazar materiales"><i class="fas fa-recycle"></i></a> ';
                                        }
                                        if ( $.inArray('finishEquipment_quote', $permissions) !== -1 ) {
                                            text = text + '<a href="'+document.location.origin+ '/dashboard/finalizar/equipos/cotizacion/'+item.id+
                                                '" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar equipos"><i class="fas fa-times-circle"></i></i></a> ';
                                        }

                                        //TODO: Boton de deselevar
                                        if ( $.inArray('raise_quote', $permissions) !== -1 ) {
                                            text = text + ' <button data-deselevar="'+item.id+'" data-name="'+item.description_quote+'" '+
                                                ' class="btn btn-outline-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Regresar a enviado"><i class="fas fa-level-down-alt"></i></button>';
                                        }
                                        if ( item.total_soles == 0 ) {
                                            if ( $.inArray('confirm_quote', $permissions) !== -1 ) {
                                                text = text + '<a href="'+document.location.origin+ '/dashboard/cotizar/soles/cotizacion/'+item.id+
                                                    '" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Cotizar en soles"><i class="fa fa-dollar-sign"></i></a> ';
                                            }
                                        }


                                        // TODO: Boton para dar visto bueno finanzas Cambiar el permiso
                                        if ( $.inArray('VBFinances_quote', $permissions) !== -1 ) {
                                            text = text + ' <button data-vb_finances="'+item.id+'" data-name="'+item.description_quote+'" '+
                                                ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Visto bueno de finanzas"><i class="fas fa-check-double"></i></button>';
                                        }

                                    }
                                }
                            }
                        }



                    }

                    if ( $.inArray('renew_quote', $permissions) !== -1 ) {
                        text = text + ' <button data-renew="'+item.id+'" data-name="'+item.description_quote+'" '+
                            ' class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Recotizar"><i class="fas fa-sync"></i></button>';
                    }

                    text = text + ' <button data-decimals="'+item.id+'" data-name="'+item.description_quote+'" '+
                        ' class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Mostrar decimales"><i class="fas fa-toggle-on"></i></button>';

                    return text;
                }
            },

        ],
        "aaSorting": [],

        select: {
            style: 'single'
        },
        language: {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "infoThousands": ",",
            "loadingRecords": "Cargando...",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad",
                "collection": "Colección",
                "colvisRestore": "Restaurar visibilidad",
                "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
                "copySuccess": {
                    "1": "Copiada 1 fila al portapapeles",
                    "_": "Copiadas %d fila al portapapeles"
                },
                "copyTitle": "Copiar al portapapeles",
                "csv": "CSV",
                "excel": "Excel",
                "pageLength": {
                    "-1": "Mostrar todas las filas",
                    "1": "Mostrar 1 fila",
                    "_": "Mostrar %d filas"
                },
                "pdf": "PDF",
                "print": "Imprimir"
            },
            "autoFill": {
                "cancel": "Cancelar",
                "fill": "Rellene todas las celdas con <i>%d<\/i>",
                "fillHorizontal": "Rellenar celdas horizontalmente",
                "fillVertical": "Rellenar celdas verticalmentemente"
            },
            "decimal": ",",
            "searchBuilder": {
                "add": "Añadir condición",
                "button": {
                    "0": "Constructor de búsqueda",
                    "_": "Constructor de búsqueda (%d)"
                },
                "clearAll": "Borrar todo",
                "condition": "Condición",
                "conditions": {
                    "date": {
                        "after": "Despues",
                        "before": "Antes",
                        "between": "Entre",
                        "empty": "Vacío",
                        "equals": "Igual a",
                        "not": "No",
                        "notBetween": "No entre",
                        "notEmpty": "No Vacio"
                    },
                    "number": {
                        "between": "Entre",
                        "empty": "Vacio",
                        "equals": "Igual a",
                        "gt": "Mayor a",
                        "gte": "Mayor o igual a",
                        "lt": "Menor que",
                        "lte": "Menor o igual que",
                        "not": "No",
                        "notBetween": "No entre",
                        "notEmpty": "No vacío"
                    },
                    "string": {
                        "contains": "Contiene",
                        "empty": "Vacío",
                        "endsWith": "Termina en",
                        "equals": "Igual a",
                        "not": "No",
                        "notEmpty": "No Vacio",
                        "startsWith": "Empieza con"
                    }
                },
                "data": "Data",
                "deleteTitle": "Eliminar regla de filtrado",
                "leftTitle": "Criterios anulados",
                "logicAnd": "Y",
                "logicOr": "O",
                "rightTitle": "Criterios de sangría",
                "title": {
                    "0": "Constructor de búsqueda",
                    "_": "Constructor de búsqueda (%d)"
                },
                "value": "Valor"
            },
            "searchPanes": {
                "clearMessage": "Borrar todo",
                "collapse": {
                    "0": "Paneles de búsqueda",
                    "_": "Paneles de búsqueda (%d)"
                },
                "count": "{total}",
                "countFiltered": "{shown} ({total})",
                "emptyPanes": "Sin paneles de búsqueda",
                "loadMessage": "Cargando paneles de búsqueda",
                "title": "Filtros Activos - %d"
            },
            "select": {
                "1": "%d fila seleccionada",
                "_": "%d filas seleccionadas",
                "cells": {
                    "1": "1 celda seleccionada",
                    "_": "$d celdas seleccionadas"
                },
                "columns": {
                    "1": "1 columna seleccionada",
                    "_": "%d columnas seleccionadas"
                }
            },
            "thousands": ".",
            "datetime": {
                "previous": "Anterior",
                "next": "Proximo",
                "hours": "Horas"
            }
        }

    } );

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $formDelete = $('#formDelete');
    $formDelete.on('submit', destroySubCategory);
    $modalDelete = $('#modalDelete');
    $formDecimals = $('#formDecimals');
    $modalDecimals = $('#modalDecimals');
    $(document).on('click', '[data-delete]', cancelQuote);

    $(document).on('click', '[data-confirm]', confirmQuote);

    $(document).on('click', '[data-send]', sendQuote);

    $(document).on('click', '[data-renew]', renewQuote);

    $('#btn-export').on('click', exportQuotes);

    $(document).on('click', '[data-deselevar]', deselevarQuote);

    $(document).on('click', '[data-decimals]', showModalDecimals);

    $('#btn-changeDecimals').on('click', saveDecimals);

    $(document).on('click', '[data-raise]', raiseQuote);
    $(document).on('click', '[data-raise2]', raise2Quote);
});

var $formDelete;
var $modalDelete;
var $modalDecimals;
var $formDecimals;

var $modalDetraction;
var $formDetraction;

var $permissions;

function raiseQuote() {
    var quote_id = $(this).data('raise');
    var code = ($(this).data('code')===null) ? 'No tiene' : $(this).data('code');

    $.confirm({
        icon: 'fa fa-level-up-alt',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de elevar esta cotización a orden de ejecución?',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<strong>Código actual: </strong>' + code +
            '<br><label>Ingrese el código del cliente aquí: </label>' +
            '<input type="text" placeholder="Código" class="name form-control" required />' +
            '</div>' +
            '</form>',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    var name = this.$content.find('.name').val();
                    if(!name || name.trim()===''){
                        $.alert('Ingrese un código válido');
                        return false;
                    }
                    $.ajax({
                        url: '/dashboard/raise/quote/'+quote_id+'/code/'+name,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización elevada.");
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
                    $.alert("Cotización no elevada.");
                },
            },
        }
    });

}

function raise2Quote() {
    var quote_id = $(this).data('raise2');
    var code = ($(this).data('code')===null) ? 'No tiene' : $(this).data('code');

    $.confirm({
        icon: 'fa fa-level-up-alt',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de modificar el codigo del cliente?',
        content: '' +
            '<form action="" class="formName">' +
            '<div class="form-group">' +
            '<strong>Código actual: </strong>' + code +
            '<br><label>Ingrese el código del cliente aquí: </label>' +
            '<input type="text" placeholder="Código" class="name form-control" required />' +
            '</div>' +
            '</form>',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    var name = this.$content.find('.name').val();
                    if(!name || name.trim()===''){
                        $.alert('Ingrese un código válido');
                        return false;
                    }
                    $.ajax({
                        url: '/dashboard/raise/quote/'+quote_id+'/code/'+name,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Código modificado correctamente.");
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
                    $.alert("Cotización no elevada.");
                },
            },
        }
    });

}

function showModalDecimals() {
    $('#decimals').val('');
    $('#decimals').trigger('change');
    var quote_id = $(this).data('decimals');
    $.ajax({
        url: "/dashboard/get/decimals/quote/"+quote_id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            $formDecimals.find("[id=quote_id]").val(quote_id);
            $('#decimals').val(data.decimals);
            $('#decimals').trigger('change');

            $modalDecimals.modal('show');
        }
    });
}

function saveDecimals() {
    var button = $(this);
    button.attr("disabled", true);
    var form = $formDecimals[0];
    $.confirm({
        icon: 'fas fa-toggle-on',
        theme: 'modern',
        closeIcon: false,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de guardar la elección?',
        content: 'Mostrar decimales implica que el PDF va a mostrar los valores con decimales.<br>Ocultar decimales implica que el PDF mostrará valores sin decimales.'  ,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/change/decimals/quote',
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: new FormData(form),
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                button.attr("disabled", false);
                                $modalDecimals.modal('hide');
                            }, 2000 )
                        },
                        error: function (data) {
                            button.attr("disabled", false);
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    button.attr("disabled", false);
                    $.alert("No se guardó ninguún dato.");
                },
            },
        }
    });

}

function deselevarQuote() {
    var quote_id = $(this).data('deselevar');

    $.confirm({
        icon: 'fas fa-level-down',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de regresar a enviado esta cotización?',
        content: 'Se va a regresar el estado enviado y se inhabilitará la orden de ejecución.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/deselevar/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
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
                    $.alert("Se canceló el proceso.");
                },
            },
        }
    });

}

function exportQuotes() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");
    var typeQuote = $("input[name='typeQuote']:checked").val();

    console.log(start);
    console.log(end);
    console.log(startDate);
    console.log(endDate);

    if ( start == '' || end == '' )
    {
        console.log('Sin fechas');
        $.confirm({
            icon: 'fas fa-file-excel',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó fechas',
            content: 'Si no hay fechas se descargará todas las cotizaciones',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end,
                            type: typeQuote
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/exportar/reporte/cotizaciones/?" + $.param(query);

                        window.location = url;

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Exportación cancelada.");
                    },
                },
            },
        });
    } else {
        console.log('Con fechas');
        console.log(JSON.stringify(start));
        console.log(JSON.stringify(end));

        var query = {
            start: start,
            end: end,
            type: typeQuote
        };

        toastr.success('Descargando archivo ...', 'Éxito',
            {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "2000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });

        var url = "/dashboard/exportar/reporte/cotizaciones/?" + $.param(query);

        window.location = url;

    }

}

function renewQuote() {
    var quote_id = $(this).data('renew');
    var button = $(this);
    button.attr("disabled", true);
    $.confirm({
        icon: 'fas fa-sync',
        theme: 'modern',
        closeIcon: false,
        animation: 'zoom',
        type: 'green',
        columnClass: 'medium',
        title: '¿Está seguro de renovar esta cotización?',
        content: 'Se va a crear una nueva cotización pero con todos los mismos contenidos.',
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/dashboard/renew/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                button.attr("disabled", false);
                                location.href = data.url;
                            }, 2000 )
                        },
                        error: function (data) {
                            button.attr("disabled", false);
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                    //$.alert('Your name is ' + name);
                }
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    button.attr("disabled", false);
                    $.alert("Cotización no elevada.");
                },
            },
        }
    });

}

function cancelQuote() {
    var quote_id = $(this).data('delete');
    var description = $(this).data('name');

    $.confirm({
        icon: 'fas fa-frown',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: '¿Está seguro de eliminar esta cotización?',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/destroy/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización anulada.");
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Anulación cancelada.");
                },
            },
        },
    });

}

function confirmQuote() {
    var quote_id = $(this).data('confirm');
    var description = $(this).data('name');

    var status_send = $(this).data('status');

    if ( status_send == 0 )
    {
        toastr.error('No puede confirmar sin antes enviar.', 'Error',
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
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });
        return;
    }

    $.confirm({
        icon: 'fas fa-smile',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de confirmar esta cotización? ',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/confirm/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización confirmada con éxito.");
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Anulación cancelada.");
                },
            },
        },
    });

}

function sendQuote() {
    var quote_id = $(this).data('send');
    var description = $(this).data('name');

    $.confirm({
        icon: 'fas fa-paper-plane',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'green',
        title: '¿Está seguro de enviar esta cotización? ',
        content: description,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/send/quote/'+quote_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert("Cotización enviada con éxito.");
                            setTimeout( function () {
                                location.reload();
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Anulación cancelada.");
                },
            },
        },
    });

}

function destroySubCategory() {
    event.preventDefault();
    // Obtener la URL
    var deleteUrl = $formDelete.data('url');
    $.ajax({
        url: deleteUrl,
        method: 'POST',
        data: new FormData(this),
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
            toastr.success(data.message, 'Éxito',
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
            $modalDelete.modal('hide');
            setTimeout( function () {
                location.reload();
            }, 2000 )
        },
        error: function (data) {
            for ( var property in data.responseJSON.errors ) {
                toastr.error(data.responseJSON.errors[property], 'Error',
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
            }


        },
    });
}