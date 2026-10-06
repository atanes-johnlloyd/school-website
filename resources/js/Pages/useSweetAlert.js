import Swal from 'sweetalert2'

export const showError = (message) => Swal.fire({
  title: 'Something went wrong',
  text: message,
  icon: 'error',
  confirmButtonColor: '#b91c1c',
})

export const confirmAction = (message, options = {}) => Swal.fire({
  title: options.title || 'Please confirm',
  text: message,
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: options.confirmButtonColor || '#005506',
  cancelButtonColor: '#64748b',
  confirmButtonText: options.confirmButtonText || 'Confirm',
  cancelButtonText: options.cancelButtonText || 'Cancel',
  reverseButtons: true,
  focusCancel: true,
}).then(result => result.isConfirmed)
