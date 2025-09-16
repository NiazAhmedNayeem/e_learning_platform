@extends('backend.layouts.master')
@section('title', 'Admin | Orders')
@section('main-content')

<div class="container mt-4">
    <h2>All Orders List</h2>

    <div class="d-flex justify-content-between mb-3">
        <!-- Search (left) -->
        <div class="w-25">
            <input type="text" id="searchBox" class="form-control" placeholder="Search by order number...">
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL</th>
                <th>Order ID</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Number</th>
                <th>Transaction ID</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="orderTable">

        </tbody>
    </table>

    {{-- Pagination Links --}}
    <nav>
        <ul class="pagination" id="paginationLinks"></ul>
    </nav>
</div>


<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-shopping-cart me-2"></i> Order Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderModalBody">
                <!-- AJAX দিয়ে load হবে -->
                <div class="text-center p-5">Loading...</div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        function loadOrders(page = 1, search = ''){
            let html = '';
            $.get("{{ url('/admin/orders-data') }}?page=" + page + "&search=" + search, function(res){
                if(res.data.length > 0){
                    $.each(res.data, function(index, order){
                        html += `
                        <tr>
                            <td>${res.from + index}</td>
                            <td>${order.unique_order_id}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${order.user?.image_show}" 
                                        class="rounded-circle shadow-sm me-2" 
                                        alt="User Image" width="40" height="40">
                                    <span>${order.user?.name ? order.user.name.split(' ')[0] : 'Unknown'}</span>
                                </div>
                            </td>
                            <td>${order.amount}</td>
                            <td>${order.payment_method}</td>
                            <td>${order.number}</td>
                            <td>${order.transaction_id}</td>
                            <td>${order.created_at}</td>
                            <td>
                                ${
                                    order.status === 'pending' 
                                    ? '<span class="badge bg-warning">Pending</span>'
                                    : order.status === 'approved'
                                    ? '<span class="badge bg-success">Approved</span>'
                                    : order.status === 'rejected'
                                    ? '<span class="badge bg-danger">Rejected</span>'
                                    : '<span class="badge bg-secondary">Unknown</span>'
                                }
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm viewOrder" data-id="${order.id}">
                                    View
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                }else{
                    html = `<tr><td colspan="10" class="text-center">No categories found</td></tr>`;
                }
                $('#orderTable').html(html);

                // pagination links making
                let links = '';
                $.each(res.links, function(index, link){
                    let active = link.active ? 'active' : '';
                    links += `<li class="page-item ${active}">
                        <a class="page-link" href="#" data-page="${link.url ? link.url.split('page=')[1] : ''}">
                            ${link.label}
                        </a>
                    </li>`;
                });
                $('#paginationLinks').html(links);
            });
        }
        loadOrders();

        // Pagination new data load 
        $(document).on("click", "#paginationLinks a", function(e){
            e.preventDefault();
            let page = $(this).data("page");
            if(page) loadOrders(page);
        });

        //live search
        let typingTimer;
        $('#searchBox').on('keyup', function(){
            clearTimeout(typingTimer);
            let value = $(this).val();
            typingTimer = setTimeout(() => {
                loadOrders(1, value);
            }, 300);
        });


        // View Order Click
        $(document).on("click", ".viewOrder", function(){
            let id = $(this).data("id");

            
            $("#orderModalBody").html('<div class="text-center p-5">Loading...</div>');

            // AJAX request
            $.get("{{ url('/admin/order-details') }}/" + id, function(res){
                
                $("#orderModalBody").html(res.html); 
                $("#orderModal").modal("show");
            });
        });


        $(document).on('click', '.updateStatus', function(){
            let orderId = $(this).data('id');
            let status = $(this).data('status');

            $.ajax({
                url: "{{ url('/admin/order/status') }}/" + orderId,
                method: 'POST',
                data: {status: status},
                success: function(res){
                    if(res.status === 'success'){

                        //Table row badge update
                        $('#orderTable').find(`tr td button[data-id='${orderId}']`)
                            .closest('tr')
                            .find('td:nth-child(9) .badge')
                            .removeClass('bg-success bg-warning bg-danger bg-secondary text-dark')
                            .addClass(
                                status === 'approved' ? 'bg-success' :
                                status === 'pending' ? 'bg-warning text-dark' :
                                status === 'rejected' ? 'bg-danger' : 'bg-secondary'
                            )
                            .text(status.charAt(0).toUpperCase() + status.slice(1));

                        // Modal badge update
                        $('#orderModalBody').find('.badge')
                            .removeClass('bg-success bg-warning bg-danger bg-secondary text-dark')
                            .addClass(
                                status === 'approved' ? 'bg-success' :
                                status === 'pending' ? 'bg-warning text-dark' :
                                status === 'rejected' ? 'bg-danger' : 'bg-secondary'
                            )
                            .text(status.charAt(0).toUpperCase() + status.slice(1));


                        // Modal hide
                        $('#orderModal').modal('hide');

                        toastr.success(res.message, 'Success', {timeOut: 3000});
                    }
                },
                error: function(err){
                    toastr.error('Something went wrong!');
                }
            });
        });



    });
</script>
@endsection