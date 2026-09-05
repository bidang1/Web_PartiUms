<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('order')->orderBy('id', 'desc')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Menambahkan FAQ baru: ' . $faq->question,
            'entity_type' => 'Faq',
            'entity_id' => $faq->id,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Mengubah FAQ: ' . $faq->question,
            'entity_type' => 'Faq',
            'entity_id' => $faq->id,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Menghapus FAQ: ' . $faq->question,
            'entity_type' => 'Faq',
            'entity_id' => $faq->id,
        ]);

        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dihapus.');
    }
}
