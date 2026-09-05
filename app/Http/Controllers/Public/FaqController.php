<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('category');

        return view('public.faq', compact('faqs'));
    }
}
