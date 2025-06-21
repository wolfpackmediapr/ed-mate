<div class="container-fluid mt-4">
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Users List -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Conversations</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" id="usersList">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['id'] != $current_user): ?>
                                <a href="#" class="list-group-item list-group-item-action user-item" 
                                   data-user-id="<?= $user['id'] ?>"
                                   data-user-name="<?= htmlspecialchars($user['name'] ?? $user['email']) ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="<?= base_url('assets/images/default-avatar.svg') ?>" 
                                                 class="rounded-circle" 
                                                 width="40" height="40" 
                                                 alt="User Avatar">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0"><?= htmlspecialchars($user['name'] ?? $user['email']) ?></h6>
                                            <small class="text-muted">Click to start chat</small>
                                        </div>
                                    </div>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white" id="chatHeader">
                    <h5 class="mb-0">Select a conversation to start chatting</h5>
                </div>
                <div class="card-body">
                    <div class="chat-messages" id="chatMessages" style="height: 400px; overflow-y: auto;">
                        <!-- Messages will be loaded here -->
                    </div>
                    <div class="chat-input mt-3">
                        <form id="messageForm" class="d-flex">
                            <input type="text" class="form-control me-2" id="messageInput" 
                                   placeholder="Type your message..." disabled>
                            <button type="submit" class="btn btn-primary" disabled>Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-messages {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
}

.message {
    max-width: 70%;
    padding: 0.75rem;
    border-radius: 1rem;
    margin-bottom: 0.5rem;
}

.message.sent {
    align-self: flex-end;
    background-color: #007bff;
    color: white;
}

.message.received {
    align-self: flex-start;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.message-time {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    opacity: 0.8;
}

.user-item.active {
    background-color: #f8f9fa;
}

.user-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentChatUser = null;
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const chatHeader = document.getElementById('chatHeader');
    const userItems = document.querySelectorAll('.user-item');

    // Handle user selection
    userItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            
            // Update active state
            userItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Update chat header
            chatHeader.innerHTML = `<h5 class="mb-0">Chat with ${userName}</h5>`;
            
            // Enable message input
            messageInput.disabled = false;
            messageForm.querySelector('button').disabled = false;
            
            // Set current chat user
            currentChatUser = userId;
            
            // Load messages
            loadMessages(userId);
        });
    });

    // Handle message submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!currentChatUser || !messageInput.value.trim()) return;

        const message = {
            receiver_id: currentChatUser,
            content: messageInput.value.trim()
        };

        // Send message
        fetch('<?= base_url('api/messages/send') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= $this->security->get_csrf_hash() ?>'
            },
            body: JSON.stringify(message)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages(currentChatUser);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Load messages for a user
    function loadMessages(userId) {
        fetch(`<?= base_url('api/messages/') ?>${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMessages(data.data);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Display messages in the chat
    function displayMessages(messages) {
        chatMessages.innerHTML = '';
        messages.forEach(message => {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${message.sender_id == <?= $current_user ?> ? 'sent' : 'received'}`;
            
            const content = document.createElement('div');
            content.className = 'message-content';
            content.textContent = message.content;
            
            const time = document.createElement('div');
            time.className = 'message-time';
            time.textContent = new Date(message.created_at).toLocaleTimeString();
            
            messageDiv.appendChild(content);
            messageDiv.appendChild(time);
            chatMessages.appendChild(messageDiv);
        });
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Poll for new messages every 5 seconds
    setInterval(() => {
        if (currentChatUser) {
            loadMessages(currentChatUser);
        }
    }, 5000);
});
</script> 