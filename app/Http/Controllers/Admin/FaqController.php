<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('id')->paginate(20);
        return view('admin.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        Faq::create($data);
        return redirect()->route('admin.faq.index')->with('success', 'SSS eklendi.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $faq->update($data);
        return redirect()->route('admin.faq.index')->with('success', 'SSS güncellendi.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faq.index')->with('success', 'SSS silindi.');
    }
}
