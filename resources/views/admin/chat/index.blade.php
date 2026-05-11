@extends('admin.layouts.admin')

@section('content')

<div class="main-side">
    <div class="main-title">
        <div class="large">
            الشات
        </div>
    </div>

    <div class="chat-container">
        <div class="row">
            <!-- Conversations List -->
            <div class="col-md-4 col-lg-3">
                <div class="chat-sidebar">
                    <div class="chat-header">
                        <h5>المحادثات</h5>
                        <button class="btn btn-primary btn-sm" onclick="openNewChatModal()">
                            <i class="fa-solid fa-plus"></i> محادثة جديدة
                        </button>
                    </div>
                    <div class="conversations-list" id="conversationsList">
                        <div class="text-center p-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-md-8 col-lg-9">
                <div class="chat-area" id="chatArea">
                    <div class="chat-placeholder">
                        <i class="fa-solid fa-comments fa-4x text-muted"></i>
                        <p class="mt-3">اختر محادثة للبدء</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">محادثة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">اختر مستخدم</label>
                    <select class="form-select" id="userSelect">
                        <option value="">-- اختر مستخدم --</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="createConversation()">إنشاء</button>
            </div>
        </div>
    </div>
</div>

<style>
.chat-container {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    min-height: 600px;
}

.chat-sidebar {
    border-right: 1px solid #dee2e6;
    height: 600px;
    display: flex;
    flex-direction: column;
}

.chat-header {
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
}

.conversation-item {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
}

.conversation-item:hover {
    background: #f8f9fa;
}

.conversation-item.active {
    background: #e9ecef;
}

.conversation-item .user-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.conversation-item .last-message {
    color: #6c757d;
    font-size: 0.9em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-item .message-time {
    font-size: 0.8em;
    color: #adb5bd;
}

.unread-badge {
    background: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 8px;
    font-size: 0.8em;
}

.chat-area {
    background: #f8f9fa;
    border-radius: 10px;
    height: 600px;
    display: flex;
    flex-direction: column;
}

.chat-header-area {
    padding: 15px;
    background: white;
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
}

.message {
    margin-bottom: 15px;
    max-width: 70%;
    align-self: flex-start;
}

.message.sent {
    align-self: flex-start;
}

.message.received {
    align-self: flex-end;
}

.message-content {
    padding: 10px 14px;
    border-radius: 8px;
    word-wrap: break-word;
    display: inline-block;
    max-width: 100%;
}

.message.sent .message-content {
    background: #007bff;
    color: white;
    border-radius: 8px 8px 0 8px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.message.received .message-content {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px 8px 8px 0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.message-time {
    font-size: 0.75em;
    color: #6c757d;
    margin-top: 5px;
}

.message.sent .message-time {
    text-align: right;
}

.chat-input-area {
    padding: 15px;
    background: white;
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 10px 10px;
}

.chat-placeholder {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;
    color: #6c757d;
}
</style>

<script>
let currentConversationId = null;
let currentUserId = null;
let lastMessageId = null;

// Load conversations on page load
document.addEventListener('DOMContentLoaded', function() {
    loadConversations();
    loadUsers();
});

function loadConversations() {
    console.log('Loading conversations...');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token meta tag not found');
        return;
    }

    fetch('/admin/chat/api/conversations', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data);
        if(data.success) {
            console.log('Conversations data:', data.data);
            displayConversations(data.data);
        } else {
            console.error('API returned success: false');
        }
    })
    .catch(error => {
        console.error('Error loading conversations:', error);
    });
}

function displayConversations(conversations) {
    const container = document.getElementById('conversationsList');
    container.innerHTML = '';

    conversations.forEach(conv => {
        const otherUser = conv.user1_id === {{ auth()->id() }} ? conv.user2 : conv.user1;
        const unreadCount = conv.unread_count || 0;
        const lastMessageText = conv.last_message && conv.last_message.content ? conv.last_message.content : (conv.last_message && conv.last_message.file_name ? conv.last_message.file_name : 'لا توجد رسائل');

        // Profile image
        let profileImage = '';
        if (otherUser.image) {
            profileImage = `<img src="/uploads/customers/${otherUser.image}" alt="${otherUser.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">`;
        } else {
            profileImage = `<div style="width: 40px; height: 40px; border-radius: 50%; background: #007bff; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">${otherUser.name ? otherUser.name.charAt(0).toUpperCase() : 'U'}</div>`;
        }

        const item = document.createElement('div');
        item.className = 'conversation-item';
        item.onclick = function() { loadConversation(conv.id, this); };
        item.innerHTML = `
            <div class="d-flex align-items-center gap-3">
                <div>${profileImage}</div>
                <div class="flex-grow-1">
                    <div class="user-name">${otherUser.name || 'مستخدم'}</div>
                    <div class="last-message">${lastMessageText}</div>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <span class="message-time">${formatTime(conv.updated_at)}</span>
                    ${unreadCount > 0 ? `<span class="unread-badge">${unreadCount}</span>` : ''}
                </div>
            </div>
        `;
        container.appendChild(item);
    });
}

function loadConversation(conversationId, element) {
    currentConversationId = conversationId;

    // Highlight active conversation
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    if(element) {
        element.classList.add('active');
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token meta tag not found');
        return;
    }

    fetch(`/admin/chat/api/conversations/${conversationId}/messages`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            displayChat(data.data);
            // Store last message ID for smart polling
            const messages = data.data.messages || [];
            if (messages.length > 0) {
                lastMessageId = messages[messages.length - 1].id;
            }
        }
    })
    .catch(error => console.error('Error loading conversation:', error));
}

function displayChat(data) {
    console.log('displayChat data:', data);
    const chatArea = document.getElementById('chatArea');
    const otherUser = data.conversation.user1_id === {{ auth()->id() }} ? data.conversation.user2 : data.conversation.user1;

    // Handle messages from Laravel Collection
    let messagesArray = [];
    if (data.messages && data.messages.data) {
        messagesArray = data.messages.data;
    } else if (Array.isArray(data.messages)) {
        messagesArray = data.messages;
    } else if (data.messages) {
        // Convert Laravel Collection object to array
        messagesArray = Object.values(data.messages);
    }

    console.log('messagesArray:', messagesArray);

    chatArea.innerHTML = `
        <div class="chat-header-area">
            <h6>${otherUser.name || 'مستخدم'}</h6>
        </div>
        <div class="chat-messages" id="chatMessages">
            ${messagesArray.map(msg => createMessageHTML(msg)).join('')}
        </div>
        <div class="chat-input-area">
            <div class="input-group">
                <input type="text" class="form-control" id="messageInput" placeholder="اكتب رسالة..." onkeypress="handleKeyPress(event)">
                <button class="btn btn-primary" onclick="sendMessage()">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
                <input type="file" id="fileInput" class="d-none" multiple onchange="handleFileSelect(event)">
                <button class="btn btn-secondary" onclick="document.getElementById('fileInput').click()">
                    <i class="fa-solid fa-paperclip"></i>
                </button>
            </div>
        </div>
    `;

    // Scroll to bottom
    const messagesContainer = document.getElementById('chatMessages');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Mark as read
    markAsRead();
}

function createMessageHTML(message) {
    console.log('createMessageHTML message:', message);
    const isSent = message.sender_id === {{ auth()->id()}};

    let messageContent = '';
    if (message.type === 'image') {
        const imagePath = '/admin/chat/api/chat/file/' + message.file_path;
        messageContent = `
            <div class="message-image">
                <img src="${imagePath}" alt="${message.file_name || 'صورة'}"
                     onclick="window.open('${imagePath}', '_blank')"
                     style="max-width: 200px; max-height: 200px; border-radius: 8px; cursor: pointer;">
            </div>
        `;
    } else if (message.type === 'file') {
        const filePath = '/admin/chat/api/chat/file/' + message.file_path;
        messageContent = `
            <div class="message-file">
                <a href="${filePath}" target="_blank" download="${message.file_name}"
                   style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: inherit;">
                    <i class="fa-solid fa-file"></i>
                    <span>${message.file_name || 'ملف'}</span>
                </a>
            </div>
        `;
    } else if (message.type === 'text') {
        messageContent = message.content || '';
    } else {
        messageContent = message.content || '';
    }

    // Read receipt checkmarks
    let readReceipt = '';
    if (isSent) {
        if (message.is_read) {
            readReceipt = '<i class="fa-solid fa-check-double" style="color: #4FC3F7; font-size: 12px; margin-right: 4px;"></i>';
        } else {
            readReceipt = '<i class="fa-solid fa-check" style="color: #9E9E9E; font-size: 12px; margin-right: 4px;"></i>';
        }
    }

    return `
        <div class="message ${isSent ? 'sent' : 'received'}">
            <div class="message-content">
                ${messageContent}
            </div>
            <div class="message-time">
                ${formatTime(message.created_at)}
                ${readReceipt}
            </div>
        </div>
    `;
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const content = input.value.trim();
    const fileInput = document.getElementById('fileInput');
    const files = fileInput.files;
    const filePreview = document.getElementById('filePreview');

    if(!content && (!files || files.length === 0)) return;
    if(!currentConversationId) return;

    if (files && files.length > 0) {
        // Send multiple files
        for (let i = 0; i < files.length; i++) {
            const formData = new FormData();
            formData.append('conversation_id', currentConversationId);
            formData.append('file', files[i]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch('/admin/chat/api/upload', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    appendMessage(data.data);
                    if (i === files.length - 1) {
                        input.value = '';
                        fileInput.value = '';
                        if(filePreview) filePreview.remove();
                        loadConversation(currentConversationId);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    } else {
        // Send text message
        const formData = new FormData();
        formData.append('conversation_id', currentConversationId);
        formData.append('content', content);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch('/admin/chat/api/messages', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('sendMessage response:', data);
            if(data.success) {
                input.value = '';
                appendMessage(data.data);
            } else {
                console.error('Send message failed:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function sendFile() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if(!file || !currentConversationId) return;

    const formData = new FormData();
    formData.append('conversation_id', currentConversationId);
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('/admin/chat/api/upload', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            fileInput.value = '';
            appendMessage(data.data);
        }
    })
    .catch(error => console.error('Error:', error));
}

function handleFileSelect(event) {
    const files = event.target.files;
    if(!files || files.length === 0) return;

    // Show file preview in input area
    const inputArea = document.querySelector('.chat-input-area');
    let existingPreview = document.getElementById('filePreview');

    if (existingPreview) {
        existingPreview.remove();
    }

    const preview = document.createElement('div');
    preview.id = 'filePreview';
    preview.style.cssText = 'margin-top: 10px; padding: 10px; background: #f0f0f0; border-radius: 8px; display: flex; flex-wrap: wrap; gap: 10px;';

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileItem = document.createElement('div');
        fileItem.style.cssText = 'display: flex; align-items: center; gap: 8px; background: white; padding: 8px; border-radius: 6px;';

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.cssText = 'max-width: 50px; max-height: 50px; border-radius: 6px;';
            fileItem.appendChild(img);
        } else {
            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-file';
            icon.style.cssText = 'font-size: 24px; color: #007bff;';
            fileItem.appendChild(icon);
        }

        const fileName = document.createElement('span');
        fileName.textContent = file.name;
        fileName.style.cssText = 'max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.9em;';
        fileItem.appendChild(fileName);

        const removeBtn = document.createElement('button');
        removeBtn.innerHTML = '<i class="fa-solid fa-times"></i>';
        removeBtn.className = 'btn btn-sm btn-danger';
        removeBtn.style.cssText = 'padding: 2px 6px; font-size: 0.8em;';
        removeBtn.onclick = function(e) {
            e.stopPropagation();
            fileItem.remove();
            if (preview.children.length === 0) {
                preview.remove();
                document.getElementById('fileInput').value = '';
            }
        };
        fileItem.appendChild(removeBtn);

        preview.appendChild(fileItem);
    }

    const clearAllBtn = document.createElement('button');
    clearAllBtn.innerHTML = 'إلغاء الكل';
    clearAllBtn.className = 'btn btn-sm btn-secondary';
    clearAllBtn.style.cssText = 'margin-left: auto;';
    clearAllBtn.onclick = function() {
        preview.remove();
        document.getElementById('fileInput').value = '';
    };
    preview.appendChild(clearAllBtn);

    inputArea.appendChild(preview);
}

function appendMessage(message) {
    const container = document.getElementById('chatMessages');
    container.innerHTML += createMessageHTML(message);
    container.scrollTop = container.scrollHeight;
    // Update last message ID
    lastMessageId = message.id;
}

function pollNewMessages() {
    if (!currentConversationId || !lastMessageId) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return;

    fetch(`/admin/chat/api/conversations/${currentConversationId}/messages?after=${lastMessageId}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const messages = data.data.messages || [];
            messages.forEach(msg => {
                appendMessage(msg);
            });
        }
    })
    .catch(error => console.error('Error polling new messages:', error));
}

function markAsRead() {
    if(!currentConversationId) return;

    fetch(`/admin/chat/api/conversations/${currentConversationId}/read`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({})
    }).catch(error => console.log('markAsRead error (non-critical):', error));
}

function handleKeyPress(event) {
    if(event.key === 'Enter') {
        sendMessage();
    }
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
}

function openNewChatModal() {
    const modal = new bootstrap.Modal(document.getElementById('newChatModal'));
    modal.show();
}

function loadUsers() {
    fetch('/admin/users/list', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const select = document.getElementById('userSelect');
            data.data.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = user.name || 'مستخدم';
                select.appendChild(option);
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

function createConversation() {
    const userId = document.getElementById('userSelect').value;

    if(!userId) {
        alert('اختر مستخدم');
        return;
    }

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('/admin/chat/api/conversations', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('newChatModal'));
            modal.hide();
            loadConversations();
            loadConversation(data.data.id);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Smart polling for new messages (every 5 seconds)
// This doesn't refresh the entire conversation, only fetches new messages
setInterval(() => {
    pollNewMessages();
    loadConversations(); // Update conversation list
}, 5000);
</script>

@endsection
