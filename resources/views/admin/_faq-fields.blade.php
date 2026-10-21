{{-- Fields shared by the add and edit FAQ forms --}}
<div>
  <label>Category</label>
  <select name="category" class="fq-input" required>
    @foreach(\App\Models\Faq::CATEGORIES as $cat)
      <option value="{{ $cat }}" @selected(($faq->category ?? old('category')) === $cat)>{{ $cat }}</option>
    @endforeach
  </select>
</div>
<div>
  <label>Order</label>
  <input type="number" name="sort_order" class="fq-input" min="0" value="{{ $faq->sort_order ?? old('sort_order', 0) }}">
  <p class="fq-hint">Lower numbers show first.</p>
</div>
<div class="full">
  <label>Question</label>
  <input type="text" name="question" class="fq-input" maxlength="255" required value="{{ $faq->question ?? old('question') }}">
</div>
<div class="full">
  <label>Answer</label>
  <textarea name="answer" class="fq-input" maxlength="3000" required>{{ $faq->answer ?? old('answer') }}</textarea>
  <p class="fq-hint">Line breaks are kept. Wrap words in **double asterisks** to make them bold.</p>
</div>
<div>
  <label>Chat button label <span style="font-weight: 400; text-transform: none; letter-spacing: 0;">(optional)</span></label>
  <input type="text" name="chat_label" class="fq-input" maxlength="30" placeholder="e.g. Pricing" value="{{ $faq->chat_label ?? old('chat_label') }}">
  <p class="fq-hint">If set, this answer gets a quick button in the chat.</p>
</div>
<div style="align-self: center;">
  <label class="fq-check"><input type="checkbox" name="is_published" value="1" @checked($faq ? $faq->is_published : true)> Show on the FAQ page and chat</label>
</div>
