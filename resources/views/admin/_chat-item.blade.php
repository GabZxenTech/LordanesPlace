{{-- One conversation in the admin chat list --}}
@php $isUnread = in_array($conv->id, $unreadIds ?? [], true); @endphp
<div class="chat-item {{ $isUnread ? 'is-unread' : '' }}" data-id="{{ $conv->id }}" data-name="{{ $conv->display_name }}" onclick="onChatItemClick({{ $conv->id }}, this.dataset.name, this)"
     style="padding: 16px 24px; border-bottom: 1px solid #e8dcc8; cursor: pointer; transition: background 0.2s;">
  <div class="chat-row">
    <input type="checkbox" class="chat-check chat-item-check" value="{{ $conv->id }}" aria-label="Select conversation" onclick="event.stopPropagation(); updateSelection();">
    <div class="chat-row-body">
      <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 4px;">
        <span style="font-size: 15px; font-weight: 600; color: #2c1a0e; display: flex; align-items: center; gap: 8px;">
          <span class="chat-unread-dot" title="New message" aria-label="New message"></span>{{ $conv->display_name }}
        </span>
        <span style="font-size: 10px; color: #8a6a40; text-transform: uppercase;">{{ $conv->last_message_at->diffForHumans() }}</span>
      </div>
      <p style="font-size: 13px; color: #8a6a40; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $conv->messages->first()->body ?? 'No messages' }}</p>
    </div>
  </div>
</div>
