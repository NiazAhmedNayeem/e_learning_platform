@extends('backend.layouts.master')
@section('title', 'Messenger')
@section('main-content')

    <style>
        body { background: #f8f9fa; }
        .chat-container { display: flex; height: 90vh; margin-top: 20px; }
        .user-list { width: 25%; background: #fff; border-right: 1px solid #ddd; overflow-y: auto; }
        .user-list .list-group-item { cursor: pointer; padding: 6px 10px; display: flex; justify-content: space-between; align-items: center; }
        .user-list .list-group-item:hover, .user-list .list-group-item.active { background: #9ddff6; }
        .user-list img { flex-shrink: 0; display: block; object-fit: cover; }
        .notification-badge { min-width: 20px; height: 20px; font-size: 0.65rem; background: linear-gradient(135deg,#ff6b6b,#ff0000); color: #fff; display: flex; align-items:center; justify-content:center; border-radius: 50%; box-shadow: 0 0 4px rgba(255,0,0,0.5); padding: 0 5px; }

        .chat-box { width: 75%; display: flex; flex-direction: column; background: #fff; }
        .chat-header { background: #0d6efd; color: #fff; padding: 12px; font-weight: bold; display: flex; align-items: center; gap:10px; }
        .messages { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; }
        .message { padding: 10px 15px; border-radius: 20px; max-width: 65%; font-size: 14px; line-height: 1.4; word-wrap: break-word; }
        .sent { background: #0d6efd; color: #fff; align-self: flex-end; border-bottom-right-radius: 0; }
        .received { background: #e9ecef; color: #000; align-self: flex-start; border-bottom-left-radius: 0; }
        .chat-input { display: flex; padding: 12px; background: #fff; border-top: 1px solid #ddd; }
        .chat-input input { flex: 1; border-radius: 20px; padding: 10px 15px; border: 1px solid #ccc; }
        .chat-input button { border-radius: 50%; width: 45px; height: 45px; margin-left: 10px; display: flex; justify-content: center; align-items: center; }
    </style>

    <div class="card-body chat-container shadow rounded d-flex ">
        <!-- Sidebar user list -->
        <div class="user-list p-3 ">
        <h5 class="d-flex justify-content-between align-items-center">
            Peoples
        </h5>
        <input type="text" id="user-search" class="form-control form-control-sm mb-2" placeholder="Search by name or role...">
        <ul class="list-group"></ul>
    </div>

    

    <!-- Chat window -->
    <div class="chat-box flex-grow-1 d-flex flex-column">
        <div class="chat-header p-2 border-bottom fw-semibold" id="chat-with">
            Select a user to chat
        </div>
        <div class="messages flex-grow-1 p-3 overflow-auto" id="chat-box"></div>
            <div class="chat-input d-flex p-2 border-top">
                <input type="text" id="message" class="form-control me-2" placeholder="Type a message...">
                <button class="btn btn-primary" id="send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let receiver_id = null;

    let allUsers = []; // for all user cache

    // Refresh sidebar
    function refreshSidebar(selected_id = null){
        let searchKeyword = $("#user-search").val().toLowerCase().trim();

        $.get("{{ route('messages.sidebar') }}", function(users){
            allUsers = users; // cache

            // if search input have any, use filter 
            if(searchKeyword.length > 0){
                users = allUsers.filter(function(user){
                    return user.name.toLowerCase().includes(searchKeyword) ||
                        user.role.toLowerCase().includes(searchKeyword) ||
                        (user.is_super == 1 && "super admin".includes(searchKeyword));
                });
            }

            renderUsers(users, selected_id);
        });
    }

    function renderUsers(users, selected_id = null){
        $(".user-list ul").html('');

        // Sort users: last message descending
        users.sort(function(a, b){
            let timeA = a.last_message_time ? new Date(a.last_message_time) : new Date(0);
            let timeB = b.last_message_time ? new Date(b.last_message_time) : new Date(0);
            return timeB - timeA; // latest first
        });

        users.forEach(function(user){
            let activeClass = (user.id == selected_id) ? 'active' : '';
            let html = `
                <li class="list-group-item d-flex justify-content-between align-items-center py-2 ${activeClass}">
                    <div class="d-flex align-items-center flex-grow-1">
                        <a href="#" class="text-decoration-none user-item d-flex align-items-center flex-grow-1" data-id="${user.id}">
                            <img src="${user.image_show}" alt="${user.name}" class="rounded-circle" width="50" height="50">
                            <div class="ms-2 d-flex flex-column justify-content-center flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <span class="user-name fw-semibold">${user.name}</span>
                                        <small class="text-muted" style="font-size:10px">
                                            ${user.is_super == 1 ? 'Super Admin' : user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                                        </small>
                                    </div>
                                    ${user.unread_count > 0 ? `<span class="notification-badge">${user.unread_count}</span>` : ''}
                                </div>

                                ${user.displayText 
                                    ? `<span class="small text-muted text-truncate" style="max-width:140px;">
                                            <i class="fas fa-message me-1"></i>${user.displayText}
                                    </span>
                                    <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i> ${user.timeText}
                                    </small>` 
                                    : ''}
                            </div>
                        </a>
                    </div>
                </li>
            `;
            $(".user-list ul").append(html);
        });
    }

    // Search filter
    $(document).on("keyup", "#user-search", function(){
        let keyword = $(this).val().toLowerCase();
        let filtered = allUsers.filter(function(user){
            return user.name.toLowerCase().includes(keyword) || 
                user.role.toLowerCase().includes(keyword) ||
                (user.is_super == 1 && "super admin".includes(keyword));
        });
        renderUsers(filtered, receiver_id);
    }); 


    // Initial sidebar load
    refreshSidebar();
    setInterval(function(){ refreshSidebar(receiver_id); }, 2000);



    // Select user from sidebar
    $(document).on('click', '.user-item', function(e){
        e.preventDefault();
        receiver_id = $(this).data('id');
        let username = $(this).find('.user-name').text();
        let userImage = $(this).find('img').attr('src');

        // Update header
        $("#chat-with").html(`<img src="${userImage}" class="rounded-circle" width="40" height="40" style="object-fit: cover;"><span class="ms-2">${username}</span>`);

        loadMessages();
    });


    // Auto select user from query string
    @if(request()->get('user'))
        $(document).ready(function(){
            receiver_id = {{ request()->get('user') }};

            // Delay until sidebar loads
            function selectUserFromSidebar(){
                let target = $(".user-item[data-id='{{ request()->get('user') }}']");
                if(target.length){
                    let username = target.find('.user-name').text();
                    let userImage = target.find('img').attr('src');

                    // Update chat header
                    $("#chat-with").html(`
                        <img src="${userImage}" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        <span class="ms-2">${username}</span>
                    `);

                    loadMessages();

                    // Highlight active user
                    $(".user-list .list-group-item").removeClass('active');
                    target.closest('.list-group-item').addClass('active');
                } else {
                    // If not yet loaded, try again after 200ms
                    setTimeout(selectUserFromSidebar, 200);
                }
            }

            selectUserFromSidebar();
        });
    @endif                  


    // Load messages
    function loadMessages(){
        if(!receiver_id) return;

        // First check scrollbar status
        let chatBox = $("#chat-box");
        let isAtBottom = chatBox.scrollTop() + chatBox.innerHeight() >= chatBox[0].scrollHeight - 50;

        $.get("{{ url('/messages/fetch') }}/"+receiver_id, function(data){
            chatBox.html('');
            data.forEach(function(msg){
                let alignment = msg.sender_id == {{ auth()->id() }} ? 'sent' : 'received';
                chatBox.append(`<div class="message ${alignment}">${msg.message}</div>`);
            });

            // do scroll, when user is bottom
            if(isAtBottom){
                chatBox.scrollTop(chatBox[0].scrollHeight);
            }

            // Mark messages as read
            if(receiver_id){
                $.post("{{ route('messages.markRead') }}", {_token:"{{ csrf_token() }}", sender_id: receiver_id});
            }
        });
    }



    // Send message
    $("#send").click(function(){
        let message = $("#message").val();
        if(!message || !receiver_id) return;

        $.post("{{ route('messages.send') }}",{
            _token:"{{ csrf_token() }}",
            receiver_id: receiver_id,
            message: message
        }, function(data){
            $("#message").val('');
            loadMessages();
            refreshSidebar(receiver_id);
        });
    });

    // Refresh messages every 3 seconds
    setInterval(loadMessages,3000);




    // Press Enter to send message
    $("#message").keypress(function(e){
        if(e.which == 13){ // 13 = Enter key
            e.preventDefault(); // Prevent new line
            let message = $("#message").val().trim(); // Remove spaces
            if(message) { // Check if not empty
                $("#send").click(); // Trigger send button
            }
        }
    });


</script>

@endsection
