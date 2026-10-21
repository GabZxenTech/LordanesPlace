<style>
  /* CHAT BUBBLE */
  .chat-bubble {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9999;
  }

  .chat-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--gold-bright, #D4A017);
    border: none;
    cursor: pointer;
    font-size: 26px;
    box-shadow: 0 4px 20px rgba(212, 160, 23, 0.4);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .chat-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 28px rgba(212, 160, 23, 0.6);
  }

  .chat-window {
    display: none;
    position: absolute;
    bottom: 75px;
    right: 0;
    width: 340px;
    height: 480px;
    background: #2C1A0E;
    border: 1px solid rgba(212, 160, 23, 0.3);
    border-radius: 16px;
    overflow: hidden;
    flex-direction: column;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
  }

  .chat-window.open {
    display: flex;
  }

  .chat-header {
    background: #3D2010;
    padding: 16px 18px;
    border-bottom: 1px solid rgba(212, 160, 23, 0.2);
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .chat-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #D4A017;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }

  .chat-header-info h4 {
    font-size: 14px;
    font-weight: 700;
    color: #F5E6C8;
    margin: 0;
  }

  .chat-header-info p {
    font-size: 11px;
    color: #4ade80;
    margin: 0;
  }

  .chat-header-info p.offline {
    color: #f87171;
  }

  .chat-close {
    margin-left: auto;
    background: none;
    border: none;
    color: #c8b89a;
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
  }

  .chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    scrollbar-width: thin;
    scrollbar-color: #D4A017 #2C1A0E;
  }

  .chat-messages::-webkit-scrollbar { width: 4px; }
  .chat-messages::-webkit-scrollbar-thumb { background: #D4A017; border-radius: 2px; }

  .msg {
    max-width: 80%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.5;
  }

  .msg.bot {
    background: #3D2010;
    color: #F5E6C8;
    border-bottom-left-radius: 4px;
    align-self: flex-start;
    border: 1px solid rgba(212, 160, 23, 0.15);
  }

  .msg.user {
    background: #D4A017;
    color: #2C1A0E;
    border-bottom-right-radius: 4px;
    align-self: flex-end;
    font-weight: 600;
  }

  /* QUICK REPLIES */
  .quick-replies {
    padding: 8px 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    background: #2C1A0E;
  }

  .quick-btn {
    background: transparent;
    border: 1px solid rgba(212, 160, 23, 0.4);
    color: #D4A017;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
  }

  .quick-btn:hover {
    background: #D4A017;
    color: #2C1A0E;
  }

  /* INPUT */
  .chat-input-row {
    padding: 12px 16px;
    border-top: 1px solid rgba(212, 160, 23, 0.2);
    display: flex;
    gap: 8px;
    background: #3D2010;
  }

  .chat-input {
    flex: 1;
    background: #2C1A0E;
    border: 1px solid rgba(212, 160, 23, 0.3);
    color: #F5E6C8;
    padding: 10px 14px;
    border-radius: 20px;
    font-size: 13px;
    outline: none;
    font-family: 'Jost', sans-serif;
  }

  .chat-input::placeholder { color: #c8b89a; }
  .chat-input:focus { border-color: #D4A017; }

  .chat-send {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #D4A017;
    border: none;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    flex-shrink: 0;
  }

  .chat-send:hover { background: #F0D060; }
  .d-none { display: none !important; }

  /* Unread admin reply while the chat is closed */
  .chat-toggle { position: relative; }
  .chat-unread-dot { position: absolute; top: 2px; right: 2px; width: 14px; height: 14px; border-radius: 50%; background: #e53935; border: 2px solid #fff; display: none; }
  .chat-toggle.has-unread .chat-unread-dot { display: block; }
</style>

<!-- CHAT BUBBLE -->
@php
  // Only visitors who already chatted need to check for replies.
  $chatConversation = auth()->check()
      ? \App\Models\Conversation::where('user_id', auth()->id())->first()
      : \App\Models\Conversation::where('session_id', session()->getId())->first();
  $chatHasUnread = $chatConversation ? $chatConversation->hasUnreadForCustomer() : false;
  // FAQ answers that have a chat button.
  try { $chatFaqs = \App\Models\Faq::published()->whereNotNull('chat_label')->values(); } catch (\Throwable $e) { $chatFaqs = collect(); }
@endphp
<div class="chat-bubble">
  <button class="chat-toggle" onclick="toggleChat()" id="chatToggleBtn" aria-label="Open chat">
    <span class="chat-unread-dot" aria-hidden="true"></span>
    <svg id="chatIconOpen" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    <svg id="chatIconClose" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </button>

  <div class="chat-window" id="chatWindow">
    <div class="chat-header">
      <div class="chat-avatar"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2C1A0E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
      <div class="chat-header-info">
        <h4>Lordane's Place Chat Assistance</h4>
        <p id="adminStatusText">● Online — here to help!</p>
      </div>
      <button class="chat-close" onclick="toggleChat()">×</button>
    </div>

    <div class="chat-messages" id="chatMessages">
      <!-- Messages will appear here -->
    </div>

    <div class="quick-replies" id="quickReplies">
      @foreach($chatFaqs as $i => $chatFaq)
        <button class="quick-btn" onclick="handleQuickReply({{ $i }})"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px;margin-right:3px;"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>{{ $chatFaq->chat_label }}</button>
      @endforeach
      <a class="quick-btn" href="{{ route('faq') }}" style="text-decoration: none;">All FAQs →</a>
    </div>

    <div class="chat-input-row" id="chatInputRow">
      <input type="text" class="chat-input" id="chatInput" placeholder="Type your message..." onkeydown="if(event.key==='Enter') sendMessage()" />
      <button class="chat-send" onclick="sendMessage()"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></button>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let chatOpen = false;
  let greeted = false;
  let isAdminOnline = true;
  let lastMessageId = 0;
  let chatActive = @json((bool) $chatConversation);

  // Quick reply answers from the FAQs (admin-edited text, so it is escaped).
  const chatFaqs = @json($chatFaqs->map(fn ($f) => ['id' => $f->id, 'label' => $f->chat_label, 'answer' => $f->answer])->values());
  const faqUrl = @json(route('faq'));

  function toggleChat() {
    chatOpen = !chatOpen;
    if (chatOpen) {
      setChatUnread(false);
      if (greeted) pollMessages(); // re-opened: fetch (and mark read) right away
      restartMessagePolling();
    }
    document.getElementById('chatWindow').classList.toggle('open', chatOpen);
    document.getElementById('chatIconOpen').style.display = chatOpen ? 'none' : '';
    document.getElementById('chatIconClose').style.display = chatOpen ? '' : 'none';

    if (chatOpen && !greeted) {
      greeted = true;
      setTimeout(() => {
        addMessage('bot', "Hi! Welcome to **Lordane's Place**! I'm your assistant. How can I help you today?", true);
        if (chatActive) loadHistory();
      }, 400);
    }

    if (chatOpen) {
      setTimeout(() => document.getElementById('chatInput').focus(), 300);
    }
  }

  function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = String(str ?? '');
    return div.innerHTML;
  }

  // Only the built-in bot replies are trusted HTML; user and admin text is escaped.
  function addMessage(type, text, trusted = false) {
    const messages = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = `msg ${type}`;
    const safe = trusted ? text : escapeHtml(text);
    div.innerHTML = safe.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
    return div;
  }

  function handleQuickReply(i) {
    const faq = chatFaqs[i];
    if (!faq) return;
    addMessage('user', faq.label);

    setTimeout(() => {
      const answer = escapeHtml(faq.answer) + "<br><br><a href='" + faqUrl + "#faq-" + faq.id + "' style='color: #D4A017;'>See more in our FAQs</a>";
      addMessage('bot', answer, true);
    }, 500);
  }

  // Messages already shown, so the same message is never added twice.
  const shownMessageIds = new Set();

  function rememberMessage(msg) {
    shownMessageIds.add(msg.id);
    lastMessageId = Math.max(lastMessageId, msg.id);
  }

  function loadHistory() {
    $.get('{{ route("chat.messages") }}', function(res) {
      if (res.messages.length > 0) {
        document.getElementById('chatMessages').innerHTML = ''; // Clear and reload
        shownMessageIds.clear();
        res.messages.forEach(msg => {
          addMessage(msg.role === 'admin' ? 'bot' : 'user', msg.body);
          rememberMessage(msg);
        });
      }
    });
  }

  async function sendMessage() {
    const input = document.getElementById('chatInput');
    const text = input.value.trim();
    if (!text) return;

    input.value = '';
    addMessage('user', text);

    $.post('{{ route("chat.send") }}', {
      _token: '{{ csrf_token() }}',
      message: text
    }, function(res) {
      if (res.success) {
        rememberMessage(res.message);
        chatActive = true;
        restartMessagePolling();
      } else {
        console.error("Chat send failed:", res.error);
        uiToast(res.error || "Message could not be sent. Please try again.");
      }
    }).fail(function(xhr) {
      console.error("Chat POST failed:", xhr.responseText);
      uiToast(xhr.status === 429 ? "You are sending messages too fast. Please wait a moment." : "Message could not be sent. Please check your connection and try again.");
    });
  }

  // Red dot on the chat bubble when the admin replied and the chat is closed.
  function setChatUnread(on) {
    document.getElementById('chatToggleBtn').classList.toggle('has-unread', !!on);
  }

  // Checks for an admin reply while the chat is closed (every 30s, only if this visitor has chatted).
  function checkChatUnread() {
    if (!chatActive || chatOpen || document.hidden) return;
    $.get('{{ route("chat.unread") }}', function(res) {
      if (!chatOpen) setChatUnread(res.unread);
    });
  }

  setChatUnread(@json($chatHasUnread));
  setInterval(checkChatUnread, 30000);

  // New messages while the chat is open: every 5s, slowing to 15s when nothing new arrives.
  let messageDelay = 5000;
  let messageTimer = null;

  function pollMessages() {
    if (!chatOpen || !chatActive) return;
    $.get('{{ route("chat.messages") }}', { last_id: lastMessageId }, function(res) {
      let gotNew = false;
      res.messages.forEach(msg => {
        if (shownMessageIds.has(msg.id)) return;
        if (msg.role === 'admin') {
          addMessage('bot', msg.body);
          gotNew = true;
        }
        rememberMessage(msg);
      });
      messageDelay = gotNew ? 5000 : Math.min(messageDelay * 1.5, 15000);
    });
  }

  function scheduleMessagePoll() {
    clearTimeout(messageTimer);
    messageTimer = setTimeout(function () {
      if (!document.hidden) pollMessages();
      scheduleMessagePoll();
    }, messageDelay);
  }

  function restartMessagePolling() {
    messageDelay = 5000;
    scheduleMessagePoll();
  }

  scheduleMessagePoll();

  // Catch up right away when the visitor comes back to the tab.
  document.addEventListener('visibilitychange', function () {
    if (document.hidden) return;
    if (chatOpen) { restartMessagePolling(); pollMessages(); } else { checkChatUnread(); }
  });
</script>
@include('partials._ui-feedback')
