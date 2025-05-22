// toast.js
function showPopupNotification(title, message, icon = 'info') {
    Swal.fire({
      title: title,
      html: message,
      icon: icon,
      confirmButtonText: 'Đóng',
      allowOutsideClick: false
    });
  }
  