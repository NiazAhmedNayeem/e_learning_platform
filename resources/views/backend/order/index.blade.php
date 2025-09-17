@extends('backend.layouts.master')
@section('title', 'Admin | Orders')
@section('main-content')

<div class="container mt-4">
    <h2>All Orders List</h2>

    <div class="d-flex justify-content-between mb-3">
        <!-- Filter Buttons (left) -->
       <div class="btn-group">
            <button type="button" class="btn btn-info filterBtn me-2" data-filter="reset">
                Reset
            </button>
            <button type="button" class="btn btn-info filterBtn me-2" data-filter="all">
                All (<span class="filter-count" data-filter="all">0</span>)
            </button>
            <button type="button" class="btn btn-success filterBtn me-2" data-filter="approved">
                Approved (<span class="filter-count" data-filter="approved">0</span>)
            </button>
            <button type="button" class="btn btn-warning filterBtn me-2" data-filter="pending">
                Pending (<span class="filter-count" data-filter="pending">0</span>)
            </button>
            <button type="button" class="btn btn-danger filterBtn" data-filter="rejected">
                Rejected (<span class="filter-count" data-filter="rejected">0</span>)
            </button>

        </div>

        <div class="d-flex align-items-center">
            <input type="date" id="fromDate" class="form-control me-2" style="max-width: 180px;">
            <input type="date" id="toDate" class="form-control me-2" style="max-width: 180px;">
            <input type="text" id="searchBox" class="form-control" placeholder="Search by order number...">
        </div>

        <!-- Search Bar (right) -->
        {{-- <div class="w-25">
            <input type="text" id="searchBox" class="form-control" placeholder="Search by order number...">
        </div> --}}
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
                <!-- load with ajax -->
                <div class="text-center p-5">Loading...</div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        ///when status update
        function updateFilterCounts(counts){
            $('.filterBtn[data-filter="all"]').text(`All (${counts.all})`);
            $('.filterBtn[data-filter="approved"]').text(`Approved (${counts.approved})`);
            $('.filterBtn[data-filter="pending"]').text(`Pending (${counts.pending})`);
            $('.filterBtn[data-filter="rejected"]').text(`Rejected (${counts.rejected})`);
        }

        let currentSearch = '';
        let currentFilter = 'all';
        let currentFrom = '';
        let currentTo = '';

        ///Data table
        function loadOrders(page = 1, search = '', filter = 'all', from = '', to = ''){
            let html = '';
            $.get("{{ url('/admin/orders-data') }}", {page, search, filter, from, to}, function(res){
                currentSearch = search;
                currentFilter = filter;
                currentFrom = from;
                currentTo = to;

                updateFilterCounts(res.counts);

                let html = '';
                if(res.data && res.data.length > 0){
                    $.each(res.data, function(index, order){
                        html += `<tr>
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
                            <td>${dayjs(order.created_at).tz('Asia/Dhaka').format('DD MMM YYYY - hh:mm A')}</td>
                            <td>
                                ${order.status === 'pending' ? '<span class="badge bg-warning">Pending</span>'
                                : order.status === 'approved' ? '<span class="badge bg-success">Approved</span>'
                                : order.status === 'rejected' ? '<span class="badge bg-danger">Rejected</span>'
                                : '<span class="badge bg-secondary">Unknown</span>'}
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm viewOrder" data-id="${order.id}">View</button>
                            </td>
                        </tr>`;
                    });
                }else{
                    html = `<tr><td colspan="10" class="text-center">No orders found</td></tr>`;
                }
                $('#orderTable').html(html);


                 // ✅ এখানে call করবে pagination render
                renderPagination(res.current_page, res.last_page);

                // যদি filter counts থাকে
                if(res.counts){
                    updateFilterCounts(res.counts);
                }

                // Pagination buttons
                // let pag = '';
                // for(let i=1;i<=res.last_page;i++){
                //     let active = i === res.current_page ? 'active' : '';
                //     pag += `<li class="page-item ${active}">
                //                 <a class="page-link" href="#" data-page="${i}">${i}</a>
                //             </li>`;
                // }
                // $('#paginationLinks').html(pag);

                

            });
        }
        
        loadOrders();

        // Pagination new data load 
        $(document).on("click", "#paginationLinks a", function(e){
            e.preventDefault();
            let page = $(this).data("page");
            if(page) loadOrders(page, currentSearch, currentFilter, currentFrom, currentTo);
        });

        function renderPagination(current, last) {
            let pag = '';

            // Previous button
            if(current > 1){
                pag += `<li class="page-item">
                            <a class="page-link" href="#" data-page="${current - 1}">« Previous</a>
                        </li>`;
            } else {
                pag += `<li class="page-item disabled">
                            <span class="page-link">« Previous</span>
                        </li>`;
            }

            let start = Math.max(1, current - 2);
            let end = Math.min(last, current + 2);

            // First page
            if(start > 1){
                pag += `<li class="page-item">
                            <a class="page-link" href="#" data-page="1">1</a>
                        </li>`;
                if(start > 2){
                    pag += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            // Pages around current
            for(let i = start; i <= end; i++){
                let active = i === current ? 'active' : '';
                pag += `<li class="page-item ${active}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
            }

            // Last page
            if(end < last){
                if(end < last - 1){
                    pag += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                pag += `<li class="page-item">
                            <a class="page-link" href="#" data-page="${last}">${last}</a>
                        </li>`;
            }

            // Next button
            if(current < last){
                pag += `<li class="page-item">
                            <a class="page-link" href="#" data-page="${current + 1}">Next »</a>
                        </li>`;
            } else {
                pag += `<li class="page-item disabled">
                            <span class="page-link">Next »</span>
                        </li>`;
            }

            $('#paginationLinks').html(pag);
        }

        // Date change
        $('#fromDate, #toDate').on('change', function(){
            let from = $('#fromDate').val();
            let to = $('#toDate').val();
            loadOrders(1, currentSearch, currentFilter, from, to);
        });


        //live search
        let typingTimer;
        $('#searchBox').on('keyup', function(){
            clearTimeout(typingTimer);
            let value = $(this).val();
            typingTimer = setTimeout(() => {
                loadOrders(1, value, currentFilter);
            }, 300);
        });


        $(document).on('click', '.filterBtn', function(){
            let filter = $(this).data('filter');

            if(filter === 'reset'){
                // Reset all
                $('#searchBox').val('');
                $('#fromDate').val('');
                $('#toDate').val('');
                currentSearch = '';
                currentFrom = '';
                currentTo = '';
                loadOrders(1, '', 'all');
                $('.filterBtn').removeClass('active');
                $('.filterBtn[data-filter="all"]').addClass('active');
            } else {
                loadOrders(1, currentSearch, filter, currentFrom, currentTo);
                $('.filterBtn').removeClass('active');
                $(this).addClass('active');
            }
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

        ///status update
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

                        //when state change the filter button count update
                        if(res.counts){
                            updateFilterCounts(res.counts);
                        }

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