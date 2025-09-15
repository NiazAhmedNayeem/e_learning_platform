@extends('backend.layouts.master')
@section('title', 'Notifications')

@section('main-content')
<div class="container mt-4">
    <h4>Your Notifications</h4>
    <ul class="list-group mt-3" id="notification-list">
        {{-- Notifications will be loaded here via AJAX --}}
    </ul>

    <div class="mt-3" id="pagination-links"></div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    fetchNotifications();

    function fetchNotifications(page = 1) {
        $.ajax({
            url: "{{ route('profile.notifications.fetch') }}",
            data: { page: page },
            success: function(res) {
                $('#notification-list').html(res.html);

                // Pagination is already inside partial, no duplicate append
                // Just ensure links are bound
            }
        });
    }

    // Pagination click
    $(document).on('click', '#pagination-links a', function(e){
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchNotifications(page);
    });

    // Mark as read
    $(document).on('click', '.mark-as-read', function(e){
        e.preventDefault();
        let url = $(this).attr('href');
        let item = $(this);

        $.get(url, function(res){
            if(res.success){
                item.removeClass('list-group-item-warning')
                    .addClass('list-group-item-secondary')
                    .removeClass('mark-as-read');

                toastr.success(res.message, 'Success', {timeOut: 3000});
            }
        });
    });
});

</script>
@endsection
