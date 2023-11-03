
function destroyCategoryEquipment(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el registro de forma permanente. ¿Deseas continuar?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {

            axios.delete(`/categoryEquipment/${id}`)
                .then(response => {
                    if (response.status === 200) {
                        Swal.fire(
                            'Eliminado',
                            'El registro ha sido eliminado.',
                            'success'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al eliminar el registro.',
                        'error'
                    );
                });
        }
    });
}


function restoreCategoryEquipment(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción restaurará el registro eliminado. ¿Deseas continuar?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {

            axios.get(`/categoryEquipment/restore/${id}`)
                .then(response => {
                    if (response.status === 200) {
                        Swal.fire(
                            'Restaurado',
                            'El registro ha sido restaurado.',
                            'success'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al restaurar el registro.',
                        'error'
                    );
                });
        }
    });
}
