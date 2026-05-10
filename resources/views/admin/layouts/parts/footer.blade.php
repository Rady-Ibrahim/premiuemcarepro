
<script src="{{ asset('admin-asset/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin-asset/js/all.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('admin-asset/js/main.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
const Toast = Swal.mixin({
  toast: true,
  position: 'bottom',
  showConfirmButton: false,
  showCloseButton: true,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

window.addEventListener('alert', ({
  detail: {
    type,
    message
  }
}) => {
  Toast.fire({
    icon: type,
    title: message
  })
})
</script>

@stack('js')
