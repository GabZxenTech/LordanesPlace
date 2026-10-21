<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
    // Admin FAQ list with the add form.
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.faqs', compact('faqs'));
    }

    // Add an FAQ.
    public function store(Request $request)
    {
        Faq::create($this->validated($request));

        return back()->with('success', 'FAQ added.');
    }

    // Edit an FAQ.
    public function update(Request $request, Faq $faq)
    {
        $faq->update($this->validated($request));

        return back()->with('success', 'FAQ updated.');
    }

    // Delete an FAQ.
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category'   => ['required', Rule::in(Faq::CATEGORIES)],
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string|max:3000',
            'chat_label' => 'nullable|string|max:30',
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['chat_label'] = trim((string) ($data['chat_label'] ?? '')) ?: null;
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
