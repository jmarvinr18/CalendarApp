import Swal from 'sweetalert2'

export function completedNotification (message, status) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,

        })
        Toast.fire({
            icon: status,
            title: message
        })
}