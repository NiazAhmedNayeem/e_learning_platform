<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('public/backend/js/scripts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('public/backend/assets/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('public/backend/assets/demo/chart-bar-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="{{ asset('public/backend/js/datatables-simple-demo.js') }}"></script>

<!-- jQuery (only once!) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- summernote --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // toastr messages
        @if(session('success'))
            toastr.success("{{ session('success') }}", 'Success', { timeOut: 5000 });
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}", 'Error', { timeOut: 5000 });
        @endif

        @if(session('warning'))
            toastr.warning("{{ session('warning') }}", 'Warning', { timeOut: 5000 });
        @endif

        @if(session('info'))
            toastr.info("{{ session('info') }}", 'Info', { timeOut: 5000 });
        @endif

        // summernote init
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200,
                tabsize: 2
            });
        });

        // select2 init
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function(){

    $('.deleteBtn').click(function(e){
        e.preventDefault();
        let form = $(this).closest('.deleteForm');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

});
</script>

<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
</script>
