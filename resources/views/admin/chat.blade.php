<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chat Assistance | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page bg-admin-dark text-admin-cream font-body min-h-screen">

  @include('partials._admin-header')

  <div class="px-5 py-5 md:px-10">
    <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden flex flex-col md:flex-row h-[75vh]">
      
      <!-- Conversation List -->
      <div class="w-full md:w-1/3 border-b md:border-b-0 md:border-r border-admin-gold/20 flex flex-col">
        <div class="p-4 border-b border-admin-gold/20 flex justify-between items-center bg-admin-secondary">
          <h2 class="text-[14px] font-bold text-admin-gold tracking-[1px] uppercase">Conversations</h2>
        </div>
        
        <div id="conversationList" class="flex-1 overflow-y-auto">
          @foreach($conversations as $conv)
            <div class="p-4 border-b border-admin-gold/10 cursor-pointer hover:bg-admin-gold/5 transition-colors chat-item-link" 
                 onclick="openChat({{ $conv->id }}, '{{ $conv->user->name ?? 'Guest' }}', this)">
              <div class="flex justify-between items-start mb-1">
                <span class="font-bold text-admin-cream">{{ $conv->user->name ?? 'Guest' }}</span>
                <span class="text-[10px] text-admin-cream-dim uppercase">{{ $conv->last_message_at->diffForHumans() }}</span>
              </div>
              <p class="text-[13px] text-admin-cream-dim truncate">{{ $conv->messages->first()->body ?? 'No messages' }}</p>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Chat Window -->
      <div class="w-full md:w-2/3 flex flex-col bg-admin-dark/30">
        <div id="chatHeader" class="p-4 border-b border-admin-gold/20 bg-admin-secondary hidden">
          <h3 id="chatTitle" class="text-[14px] font-bold text-admin-gold tracking-[1px] uppercase">Chatting with: ...</h3>
        </div>

        <div id="messagesContainer" class="flex-1 overflow-y-auto p-5 flex flex-col gap-4">
          <div id="emptyChat" class="h-full flex items-center justify-center text-admin-cream-dim text-[15px]">
            <p>Select a conversation to start messaging</p>
          </div>
          <!-- Messages load here -->
        </div>

        <div id="inputArea" class="p-4 border-top border-admin-gold/20 bg-admin-secondary hidden">
          <div class="flex gap-3">
            <input type="text" id="adminReplyInput" 
                   placeholder="Type your reply..." 
                   onkeydown="if(event.key==='Enter') sendReply()"
                   class="flex-1 bg-admin-dark border border-admin-gold/30 text-admin-cream px-4 py-2.5 rounded-md text-[14px] outline-none focus:border-admin-gold transition-colors placeholder:text-admin-cream-dim">
            <button onclick="sendReply()" 
                    class="bg-admin-gold text-admin-dark px-6 py-2 rounded-md font-bold text-[13px] transition-all hover:bg-admin-gold/90">
              SEND
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    let currentConversationId = null;
    let lastMessageId = 0;

    function openChat(id, name, element) {
      currentConversationId = id;
      
      // UI Updates
      $('#chatHeader').removeClass('hidden');
      $('#inputArea').removeClass('hidden');
      $('#emptyChat').addClass('hidden');
      $('#chatTitle').text('Chatting with: ' + name);
      
      $('.chat-item-link').removeClass('bg-admin-gold/10 border-admin-gold/40');
      $(element).addClass('bg-admin-gold/10 border-admin-gold/40');
      
      loadMessages(id);
    }

    function loadMessages(id) {
      $.get(`{{ route('admin.chat.index') }}/${id}`, function(data) {
        let html = '';
        data.messages.forEach(msg => {
          const isAdmin = msg.role === 'admin';
          html += `
            <div class="flex ${isAdmin ? 'justify-end' : 'justify-start'}">
              <div class="max-w-[80%] p-3.5 rounded-lg text-[14px] shadow-sm ${isAdmin ? 'bg-admin-gold text-admin-dark rounded-tr-none' : 'bg-admin-secondary border border-admin-gold/20 text-admin-cream rounded-tl-none'}">
                ${msg.body}
                <div class="text-[10px] mt-1.5 opacity-60 ${isAdmin ? 'text-admin-dark text-right' : 'text-admin-cream-dim'}">
                  ${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                </div>
              </div>
            </div>
          `;
          lastMessageId = msg.id;
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
        <div class="flex ${isAdmin ? 'justify-end' : 'justify-start'}">
          <div class="max-w-[80%] p-3.5 rounded-lg text-[14px] shadow-sm ${isAdmin ? 'bg-admin-gold text-admin-dark rounded-tr-none' : 'bg-admin-secondary border border-admin-gold/20 text-admin-cream rounded-tl-none'}">
            ${msg.body}
            <div class="text-[10px] mt-1.5 opacity-60 ${isAdmin ? 'text-admin-dark text-right' : 'text-admin-cream-dim'}">
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

    function pollConversations() {
      $.get('{{ route("admin.chat.json") }}', function(res) {
        let html = '';
        res.conversations.forEach(conv => {
          const isActive = currentConversationId == conv.id;
          const name = conv.user ? conv.user.name : 'Guest';
          const lastMsg = conv.messages.length > 0 ? conv.messages[0].body : 'No messages';
          
          html += `
            <div class="p-4 border-b border-admin-gold/10 cursor-pointer hover:bg-admin-gold/5 transition-colors chat-item-link ${isActive ? 'bg-admin-gold/10 border-admin-gold/40' : ''}" 
                 onclick="openChat(${conv.id}, '${name}', this)">
              <div class="flex justify-between items-start mb-1">
                <span class="font-bold text-admin-cream">${name}</span>
                <span class="text-[10px] text-admin-cream-dim uppercase italic">Updated</span>
              </div>
              <p class="text-[13px] text-admin-cream-dim truncate">${lastMsg}</p>
            </div>
          `;
        });
        $('#conversationList').html(html);
      });
    }

    setInterval(() => {
      pollConversations();
      if (currentConversationId) {
        loadMessages(currentConversationId);
      }
    }, 3000);
  </script>
</body>
</html>
