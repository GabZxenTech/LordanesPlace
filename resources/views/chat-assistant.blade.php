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
  }

  .chat-header-info p {
    font-size: 11px;
    color: #4ade80;
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

  .msg.typing {
    background: #3D2010;
    color: #c8b89a;
    align-self: flex-start;
    border: 1px solid rgba(212, 160, 23, 0.15);
    font-style: italic;
    font-size: 12px;
  }

  /* QUICK REPLIES */
  .quick-replies {
    padding: 8px 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
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
    font-family: 'Lato', sans-serif;
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
</style>

<!-- CHAT BUBBLE -->
<div class="chat-bubble">
  <button class="chat-toggle" onclick="toggleChat()" id="chatToggleBtn">💬</button>

  <div class="chat-window" id="chatWindow">
    <div class="chat-header">
      <div class="chat-avatar">🏡</div>
      <div class="chat-header-info">
        <h4>PlanVista Assistant</h4>
        <p>● Online — here to help!</p>
      </div>
      <button class="chat-close" onclick="toggleChat()">×</button>
    </div>

    <div class="chat-messages" id="chatMessages">
      <!-- Messages will appear here -->
    </div>

    <div class="quick-replies" id="quickReplies">
      <button class="quick-btn" onclick="sendQuick('Pricing & Packages')">💰 Pricing</button>
      <button class="quick-btn" onclick="sendQuick('How to Book')">📅 Booking</button>
      <button class="quick-btn" onclick="sendQuick('Location & Directions')">📍 Location</button>
      <button class="quick-btn" onclick="sendQuick('Amenities & Facilities')">🏊 Amenities</button>
    </div>

    <div class="chat-input-row">
      <input type="text" class="chat-input" id="chatInput" placeholder="Type your message..." onkeydown="if(event.key==='Enter') sendMessage()" />
      <button class="chat-send" onclick="sendMessage()">➤</button>
    </div>
  </div>
</div>

<script>
  const SYSTEM_PROMPT = `You are a helpful and friendly customer service assistant for LorDane's Place (also called PlanVista), an event venue and resort in the Philippines.

You only answer questions about LorDane's Place. If asked something unrelated, politely redirect to venue-related topics.

Here is what you know about LorDane's Place:

PRICING & PACKAGES:
- Basic Package: ₱5,000 (up to 50 guests, 4 hours)
- Standard Package: ₱10,000 (up to 100 guests, 6 hours, includes setup)
- Premium Package: ₱20,000 (up to 200 guests, full day, includes catering coordination)
- Pool Use: ₱500 per person
- Overnight Stay: ₱15,000 (up to 20 guests)

AVAILABILITY & BOOKING:
- Open daily from 8AM to 10PM
- Bookings must be made at least 3 days in advance
- 50% downpayment required to confirm booking
- Cancellation must be 48 hours before event for refund
- Contact us via this chat or call to check availability

LOCATION & DIRECTIONS:
- Located in San Jose del Monte, Bulacan, Philippines
- From Manila: Take NLEX, exit Bocaue, then follow signs to San Jose del Monte (approximately 1.5 hours)
- From MRT: Take bus to Fairview, then jeepney to San Jose del Monte
- Landmark: Near the municipal hall of San Jose del Monte

AMENITIES & FACILITIES:
- Olympic-size swimming pool
- Function hall (air-conditioned, capacity 200 pax)
- Outdoor garden area
- Full kitchen and catering area
- Sound system and lighting
- Free parking (50 cars)
- Cottages and rest areas
- 360° Virtual Tour available on the website

Always be warm, helpful, and professional. Use simple English or mix with Filipino if needed. Keep responses concise but complete. End with an offer to help further.`;

  let chatOpen = false;
  let conversationHistory = [];
  let greeted = false;

  function toggleChat() {
    chatOpen = !chatOpen;
    document.getElementById('chatWindow').classList.toggle('open', chatOpen);
    document.getElementById('chatToggleBtn').textContent = chatOpen ? '✕' : '💬';

    if (chatOpen && !greeted) {
      greeted = true;
      setTimeout(() => {
        addMessage('bot', "👋 Hi! Welcome to **LorDane's Place**! I'm your virtual assistant. How can I help you today?\n\nYou can ask me about our pricing, bookings, location, or amenities!");
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

  function showTyping() {
    return addMessage('typing', '● typing...');
  }

  function sendQuick(text) {
    document.getElementById('chatInput').value = text;
    sendMessage();
  }

  async function sendMessage() {
    const input = document.getElementById('chatInput');
    const text = input.value.trim();
    if (!text) return;

    input.value = '';
    addMessage('user', text);

    conversationHistory.push({ role: 'user', content: text });

    const typingEl = showTyping();

    try {
      const response = await fetch('https://api.anthropic.com/v1/messages', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          model: 'claude-sonnet-4-20250514',
          max_tokens: 1000,
          system: SYSTEM_PROMPT,
          messages: conversationHistory
        })
      });

      const data = await response.json();
      const reply = data.content?.[0]?.text || "Sorry, I couldn't get a response. Please try again!";

      typingEl.remove();
      addMessage('bot', reply);
      conversationHistory.push({ role: 'assistant', content: reply });

    } catch (err) {
      typingEl.remove();
      addMessage('bot', "Sorry, I'm having trouble connecting right now. Please try again or contact us directly!");
    }
  }
</script>