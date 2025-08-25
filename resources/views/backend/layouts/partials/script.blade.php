
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('public/backend/js/scripts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('public/backend/assets/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('public/backend/assets/demo/chart-bar-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="{{ asset('public/backend/js/datatables-simple-demo.js') }}"></script>
 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
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
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        //for global use without class or id
        // $(document).ready(function() {
            
        //     $('textarea').summernote({
        //         height: 200,    
        //         toolbar: [
        //             ['style', ['bold', 'italic', 'underline', 'clear']],
        //             ['font', ['fontsize', 'color']],
        //             ['para', ['ul', 'ol', 'paragraph']],
        //             ['insert', ['link', 'picture', 'video']],
        //             ['view', ['fullscreen', 'codeview']]
        //         ]
        //     });
        // });

        //for class use 
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 200
            });
        });
    </script>