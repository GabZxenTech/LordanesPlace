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
</style>

<!-- CHAT BUBBLE -->
<div class="chat-bubble">
  <button class="chat-toggle" onclick="toggleChat()" id="chatToggleBtn">💬</button>

  <div class="chat-window" id="chatWindow">
    <div class="chat-header">
      <div class="chat-avatar">🏡</div>
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
      <button class="quick-btn" onclick="handleQuickReply('pricing')">💰 Pricing</button>
      <button class="quick-btn" onclick="handleQuickReply('booking')">📋 Booking</button>
      <button class="quick-btn" onclick="handleQuickReply('location')">📍 Location</button>
      <button class="quick-btn" onclick="handleQuickReply('amenities')">🏠 Amenities</button>
    </div>

    <div class="chat-input-row" id="chatInputRow">
      <input type="text" class="chat-input" id="chatInput" placeholder="Type your message..." onkeydown="if(event.key==='Enter') sendMessage()" />
      <button class="chat-send" onclick="sendMessage()">➤</button>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let chatOpen = false;
  let greeted = false;
  let isAdminOnline = true;
  let lastMessageId = 0;

  const quickResponses = {
    pricing: "Our packages start at ₱5,000 for basic events and ₱15,000 for overnight stays. Contact us for detailed package inclusion!",
    booking: "You can book by filling out our booking form here: <a href='/booking' style='color: #D4A017;'>Click Here to Book</a>",
    location: "We are located at Pulong Buhangin, Santa Maria, Bulacan, Philippines. Near the municipal hall.",
    amenities: "We offer: Olympic-size swimming pool, Air-conditioned Function Hall (200 pax), Outdoor Garden, Full Kitchen, and Free Parking."
  };

  function toggleChat() {
    chatOpen = !chatOpen;
    document.getElementById('chatWindow').classList.toggle('open', chatOpen);
    document.getElementById('chatToggleBtn').textContent = chatOpen ? '✕' : '💬';

    if (chatOpen && !greeted) {
      greeted = true;
      setTimeout(() => {
        addMessage('bot', "👋 Hi! Welcome to **Lordane's Place**! I'm your assistant. How can I help you today?");
        loadHistory();
      }, 400);
    }

    if (chatOpen) {
      setTimeout(() => document.getElementById('chatInput').focus(), 300);
    }
  }

  function addMessage(type, text) {
    const messages = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = `msg ${type}`;
    div.innerHTML = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\n/g, '<br>');
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
    return div;
  }

  function handleQuickReply(key) {
    const labels = { pricing: "💰 Pricing", booking: "📋 Booking", location: "📍 Location", amenities: "🏠 Amenities" };
    addMessage('user', labels[key]);
    
    setTimeout(() => {
      addMessage('bot', quickResponses[key]);
    }, 500);
  }

  function loadHistory() {
    $.get('{{ route("chat.messages") }}', function(res) {
      if (res.messages.length > 0) {
        document.getElementById('chatMessages').innerHTML = ''; // Clear and reload
        res.messages.forEach(msg => {
          addMessage(msg.role === 'admin' ? 'bot' : 'user', msg.body);
          lastMessageId = msg.id;
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
        lastMessageId = res.message.id;
      } else {
        console.error("Chat send failed:", res.error);
        alert("Chat Send Error: " + res.error);
      }
    }).fail(function(xhr) {
      console.error("Chat POST failed:", xhr.responseText);
      alert("Network/Server Error: " + xhr.status + " " + xhr.statusText);
    });
  }

  // Polling for new messages
  setInterval(() => {
    if (chatOpen) {
      $.get('{{ route("chat.messages") }}', { last_id: lastMessageId }, function(res) {
        res.messages.forEach(msg => {
          if (msg.role === 'admin') {
            addMessage('bot', msg.body);
          }
          lastMessageId = msg.id;
        });
      });
    }
  }, 3000);
</script>