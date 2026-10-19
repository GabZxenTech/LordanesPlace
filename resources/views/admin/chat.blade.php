<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chat Assistance | LorDane's Place</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Conversations with messages the admin hasn't read yet */
    .chat-unread-dot { display: none; width: 9px; height: 9px; border-radius: 50%; background: #e74c3c; flex-shrink: 0; }
    .chat-item.is-unread { background: #fbf3df; }
    .chat-item.is-unread .chat-unread-dot { display: inline-block; }
    .chat-item.is-unread p { color: #2c1a0e !important; font-weight: 600; }

    /* Select mode for deleting conversations */
    .chat-row { display: flex; align-items: flex-start; gap: 12px; }
    .chat-row-body { flex: 1; min-width: 0; }
    .chat-check { display: none; width: 17px; height: 17px; margin: 2px 0 0; accent-color: #2c1a0e; cursor: pointer; flex-shrink: 0; }
    .select-mode .chat-check { display: block; }
    .chat-item.is-selected { background: #f5edd8 !important; }
    .chat-tool-btn { background: none; border: none; padding: 4px; cursor: pointer; color: #2c1a0e; display: inline-flex; align-items: center; border-radius: 4px; }
    .chat-tool-btn:hover { background: rgba(44,26,14,0.08); }
    #listTitleBar { display: flex; align-items: center; justify-content: space-between; }
    #selectBar { display: none; align-items: center; justify-content: space-between; gap: 8px; }
    .select-mode #selectBar { display: flex; }
    .select-mode #listTitleBar { display: none; }
    #selectBar label { font-size: 12px; font-weight: 600; color: #2c1a0e; display: flex; align-items: center; gap: 6px; cursor: pointer; }
    .sel-btn { border: none; border-radius: 5px; padding: 6px 10px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; cursor: pointer; font-family: 'Jost', sans-serif; }
    .sel-btn-danger { background: #b03a2e; color: #fff; }
    .sel-btn-danger:disabled { background: #d9b3ad; cursor: not-allowed; }
    .sel-btn-ghost { background: transparent; color: #8a6a40; }
    .sel-btn-ghost:hover { color: #2c1a0e; }

    #deleteChatModal { position: fixed; inset: 0; background: rgba(44,26,14,0.45); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 16px; }
    #deleteChatModal.open { display: flex; }
    .dcm-box { background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 12px; max-width: 400px; width: 100%; padding: 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); text-align: center; }
    .dcm-icon { width: 52px; height: 52px; border-radius: 50%; background: #fbe9e6; color: #b03a2e; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px; }
    .dcm-box h3 { font-family: 'Cormorant Garamond', serif; font-size: 24px; color: #2c1a0e; margin: 0 0 8px; }
    .dcm-box p { font-size: 14px; color: #6b5233; line-height: 1.55; margin: 0 0 22px; }
    .dcm-actions { display: flex; gap: 10px; }
    .dcm-actions button { flex: 1; padding: 11px; border-radius: 6px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px; cursor: pointer; font-family: 'Jost', sans-serif; }
    .dcm-keep { background: transparent; border: 1px solid #d4c4a0; color: #2c1a0e; }
    .dcm-delete { background: #b03a2e; border: 1px solid #b03a2e; color: #fff; }
    .dcm-delete:disabled { opacity: 0.6; cursor: wait; }

    #chatToast { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); background: #2c1a0e; color: #f5f0e8; padding: 10px 18px; border-radius: 6px; font-size: 13px; display: none; z-index: 1001; }
  </style>
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
      <div id="conversationPane" style="width: 320px; border-right: 1px solid #d4c4a0; display: flex; flex-direction: column; background: #fdfaf5;">
        <div style="padding: 0 24px; height: 58px; display: flex; flex-direction: column; justify-content: center; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <div id="listTitleBar">
            <h2 style="font-size: 11px; letter-spacing: 2px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">Conversations</h2>
            <button type="button" id="selectModeBtn" class="chat-tool-btn" onclick="enterSelectMode()" title="Delete conversations" aria-label="Delete conversations" @if($conversations->isEmpty()) style="display:none" @endif>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </div>
          <div id="selectBar">
            <label><input type="checkbox" id="selectAll" class="chat-check" style="display:block; margin:0;" onchange="toggleSelectAll(this.checked)"> Select all</label>
            <div style="display: flex; gap: 4px;">
              <button type="button" class="sel-btn sel-btn-ghost" onclick="exitSelectMode()">Cancel</button>
              <button type="button" id="deleteSelectedBtn" class="sel-btn sel-btn-danger" onclick="openDeleteModal()" disabled>Delete (0)</button>
            </div>
          </div>
        </div>
        <div id="conversationList" style="flex: 1; overflow-y: auto;">
          @foreach($conversations as $conv)
            @include('admin._chat-item', ['conv' => $conv])
          @endforeach
          <p id="noConversations" style="padding: 24px; font-size: 13px; color: #8a6a40; text-align: center; margin: 0; {{ $conversations->isEmpty() ? '' : 'display:none;' }}">No conversations yet.</p>
          @if($conversations->hasMorePages())
            <button type="button" id="loadMoreChats" data-next="2" onclick="loadMoreConversations()"
              style="display: block; width: calc(100% - 32px); margin: 14px 16px; padding: 10px; background: transparent; border: 1px solid #d4c4a0; border-radius: 6px; color: #8a6a40; font: 600 13px 'Jost', sans-serif; cursor: pointer;">Load more</button>
          @endif
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

  {{-- Delete conversations confirmation --}}
  <div id="deleteChatModal" role="dialog" aria-modal="true" aria-labelledby="dcmTitle" onclick="if(event.target===this) closeDeleteModal()">
    <div class="dcm-box">
      <div class="dcm-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
      </div>
      <h3 id="dcmTitle">Delete conversations?</h3>
      <p id="dcmText">All messages in the selected conversations will be permanently deleted. This cannot be undone.</p>
      <div class="dcm-actions">
        <button type="button" class="dcm-keep" onclick="closeDeleteModal()">Keep</button>
        <button type="button" class="dcm-delete" id="dcmConfirm" onclick="deleteSelected()">Delete</button>
      </div>
    </div>
  </div>
  <div id="chatToast" role="status"></div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    let currentConversationId = null;

    // Message bodies come from guests/customers — never insert them as HTML.
    function escapeHtml(str) {
      return $('<div>').text(String(str ?? '')).html();
    }

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
        // Opening a conversation marks it read: clear its dot and update the Chat badge.
        $(`.chat-item[data-id="${id}"]`).removeClass('is-unread');
        if (typeof data.unread_chats === 'number' && typeof setAdminChatBadge === 'function') setAdminChatBadge(data.unread_chats);
        let html = '';
        data.messages.forEach(msg => {
          const isAdmin = msg.role === 'admin';
          html += `
            <div style="display: flex; justify-content: ${isAdmin ? 'flex-end' : 'flex-start'};">
              <div style="max-width: 75%; padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; ${isAdmin ? 'background: #2c1a0e; color: #f5f0e8; border-bottom-right-radius: 2px;' : 'background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; border-bottom-left-radius: 2px;'}">
                ${escapeHtml(msg.body)}
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
            ${escapeHtml(msg.body)}
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

    // Loads the next 20 conversations into the list.
    function loadMoreConversations() {
      const btn = document.getElementById('loadMoreChats');
      btn.disabled = true;
      btn.textContent = 'Loading...';
      $.get('{{ route("admin.chat.json") }}', { page: btn.dataset.next }, function (res) {
        btn.insertAdjacentHTML('beforebegin', res.html);
        if (res.has_more) {
          btn.dataset.next = parseInt(btn.dataset.next, 10) + 1;
          btn.disabled = false;
          btn.textContent = 'Load more';
        } else {
          btn.remove();
        }
        updateSelection();
      }).fail(function () {
        btn.disabled = false;
        btn.textContent = 'Load more';
        uiToast('Could not load more conversations.');
      });
    }

    // ---- Select mode: pick conversations and delete them permanently ----
    function onChatItemClick(id, name, element) {
      if ($('#conversationPane').hasClass('select-mode')) {
        const box = $(element).find('.chat-item-check');
        box.prop('checked', !box.prop('checked'));
        updateSelection();
        return;
      }
      openChat(id, name, element);
    }

    function enterSelectMode() {
      $('#conversationPane').addClass('select-mode');
      updateSelection();
    }

    function exitSelectMode() {
      $('#conversationPane').removeClass('select-mode');
      $('.chat-item-check, #selectAll').prop('checked', false);
      updateSelection();
    }

    function selectedIds() {
      return $('.chat-item-check:checked').map((_, el) => parseInt(el.value, 10)).get();
    }

    function updateSelection() {
      const total = $('.chat-item-check').length;
      const count = selectedIds().length;
      $('.chat-item').each(function () {
        $(this).toggleClass('is-selected', $(this).find('.chat-item-check').prop('checked'));
      });
      $('#selectAll').prop('checked', total > 0 && count === total);
      $('#deleteSelectedBtn').text(`Delete (${count})`).prop('disabled', count === 0);
    }

    function toggleSelectAll(checked) {
      $('.chat-item-check').prop('checked', checked);
      updateSelection();
    }

    function openDeleteModal() {
      const count = selectedIds().length;
      if (!count) return;
      const noun = count === 1 ? 'conversation' : 'conversations';
      $('#dcmTitle').text(`Delete ${count} ${noun}?`);
      $('#dcmText').text(`All messages in the selected ${noun} will be permanently deleted. This cannot be undone.`);
      $('#dcmConfirm').prop('disabled', false).text('Delete');
      $('#deleteChatModal').addClass('open');
    }

    function closeDeleteModal() {
      $('#deleteChatModal').removeClass('open');
    }

    function showToast(text) {
      $('#chatToast').text(text).stop(true, true).fadeIn(150).delay(2500).fadeOut(300);
    }

    function deleteSelected() {
      const ids = selectedIds();
      if (!ids.length) return;
      $('#dcmConfirm').prop('disabled', true).text('Deleting...');

      $.post('{{ route("admin.chat.delete") }}', { _token: '{{ csrf_token() }}', ids: ids })
        .done(function (res) {
          ids.forEach(id => $(`.chat-item[data-id="${id}"]`).remove());
          if (ids.includes(currentConversationId)) {
            currentConversationId = null;
            $('#chatHeader, #inputArea').hide();
            $('#messagesContainer').html('<div id="emptyChat" style="height: 100%; display: flex; align-items: center; justify-content: center; color: #8a6a40; font-size: 15px;"><p>Select a guest to start messaging</p></div>');
          }
          if (typeof res.unread_chats === 'number' && typeof setAdminChatBadge === 'function') setAdminChatBadge(res.unread_chats);
          closeDeleteModal();
          exitSelectMode();
          if (!$('.chat-item').length) {
            $('#noConversations').show();
            $('#selectModeBtn').hide();
          }
          const n = res.deleted ?? ids.length;
          showToast(`${n} ${n === 1 ? 'conversation' : 'conversations'} deleted.`);
        })
        .fail(function () {
          $('#dcmConfirm').prop('disabled', false).text('Delete');
          showToast('Could not delete. Please try again.');
        });
    }

    $(document).on('keydown', function (e) {
      if (e.key === 'Escape') closeDeleteModal();
    });

    setInterval(() => {
      if (currentConversationId && !document.hidden) {
        loadMessages(currentConversationId);
      }
    }, 5000);
  </script>
</body>
</html>
