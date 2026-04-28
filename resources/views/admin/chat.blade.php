<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chat Assistance | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; display: flex; flex-direction: column;">
    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Chat Assistance</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Support Guest Inquiries</p>
    </div>

    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; display: flex; flex: 1; min-height: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
      
      {{-- CONVERSATION LIST --}}
      <div style="width: 320px; border-right: 1px solid #d4c4a0; display: flex; flex-direction: column; background: #fdfaf5;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 11px; letter-spacing: 2px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">Conversations</h2>
        </div>
        <div id="conversationList" style="flex: 1; overflow-y: auto;">
          @foreach($conversations as $conv)
            <div class="chat-item" onclick="openChat({{ $conv->id }}, '{{ $conv->user->name ?? 'Guest' }}', this)" 
                 style="padding: 16px 24px; border-bottom: 1px solid #e8dcc8; cursor: pointer; transition: background 0.2s;">
              <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
                <span style="font-size: 15px; font-weight: 600; color: #2c1a0e;">{{ $conv->user->name ?? 'Guest' }}</span>
                <span style="font-size: 10px; color: #8a6a40; text-transform: uppercase;">{{ $conv->last_message_at->diffForHumans() }}</span>
              </div>
              <p style="font-size: 13px; color: #8a6a40; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $conv->messages->first()->body ?? 'No messages' }}</p>
            </div>
          @endforeach
        </div>
      </div>

      {{-- CHAT AREA --}}
      <div style="flex: 1; display: flex; flex-direction: column; background: white;">
        <div id="chatHeader" style="padding: 18px 28px; border-bottom: 1px solid #d4c4a0; background: #fff9ef; display: none;">
          <h3 id="chatTitle" style="font-size: 14px; font-weight: 700; color: #c9a84c; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Chatting with: ...</h3>
        </div>

        <div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 28px; display: flex; flex-direction: column; gap: 16px; background: #fdfbf7;">
          <div id="emptyChat" style="height: 100%; display: flex; align-items: center; justify-content: center; color: #8a6a40; font-size: 15px;">
            <p>Select a guest to start messaging</p>
          </div>
          {{-- Messages load here --}}
        </div>

        <div id="inputArea" style="padding: 24px; border-top: 1px solid #d4c4a0; background: #fff9ef; display: none;">
          <div style="display: flex; gap: 12px;">
            <input type="text" id="adminReplyInput" placeholder="Type your reply..." 
                   onkeydown="if(event.key==='Enter') sendReply()"
                   style="flex: 1; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 18px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif;"
                   onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'">
            <button onclick="sendReply()" 
                    style="background: #2c1a0e; color: #c9a84c; padding: 0 24px; border: none; border-radius: 6px; font-weight: 700; font-size: 13px; letter-spacing: 1px; cursor: pointer; transition: opacity 0.3s;"
                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
              SEND
            </button>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    let currentConversationId = null;

    function openChat(id, name, element) {
      currentConversationId = id;
      
      $('#chatHeader').show();
      $('#inputArea').show();
      $('#emptyChat').hide();
      $('#chatTitle').text('Chatting with: ' + name);
      
      $('.chat-item').css('background', 'transparent');
      $(element).css('background', '#f5edd8');
      
      loadMessages(id);
    }

    function loadMessages(id) {
      $.get(`{{ route('admin.chat.index') }}/${id}`, function(data) {
        let html = '';
        data.messages.forEach(msg => {
          const isAdmin = msg.role === 'admin';
          html += `
            <div style="display: flex; justify-content: ${isAdmin ? 'flex-end' : 'flex-start'};">
              <div style="max-width: 75%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; ${isAdmin ? 'background: #2c1a0e; color: #f5f0e8; border-bottom-right-radius: 2px;' : 'background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; border-bottom-left-radius: 2px;'}">
                ${msg.body}
                <div style="font-size: 10px; margin-top: 6px; opacity: 0.6; text-align: ${isAdmin ? 'right' : 'left'};">
                  ${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                </div>
              </div>
            </div>
          `;
        });
        $('#messagesContainer').html(html);
        scrollToBottom();
      });
    }

    function sendReply() {
      const text = $('#adminReplyInput').val().trim();
      if (!text || !currentConversationId) return;
      $('#adminReplyInput').val('');

      $.post('{{ route("admin.chat.reply") }}', {
        _token: '{{ csrf_token() }}',
        conversation_id: currentConversationId,
        message: text
      }, function(res) {
        if (res.success) {
          appendMessage(res.message);
        }
      });
    }

    function appendMessage(msg) {
      const isAdmin = msg.role === 'admin';
      const html = `
        <div style="display: flex; justify-content: ${isAdmin ? 'flex-end' : 'flex-start'};">
          <div style="max-width: 75%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; ${isAdmin ? 'background: #2c1a0e; color: #f5f0e8; border-bottom-right-radius: 2px;' : 'background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; border-bottom-left-radius: 2px;'}">
            ${msg.body}
            <div style="font-size: 10px; margin-top: 6px; opacity: 0.6; text-align: ${isAdmin ? 'right' : 'left'};">
              ${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
            </div>
          </div>
        </div>
      `;
      $('#messagesContainer').append(html);
      scrollToBottom();
    }

    function scrollToBottom() {
      const container = document.getElementById('messagesContainer');
      container.scrollTop = container.scrollHeight;
    }

    setInterval(() => {
      if (currentConversationId) {
        loadMessages(currentConversationId);
      }
    }, 5000);
  </script>
</body>
</html>
