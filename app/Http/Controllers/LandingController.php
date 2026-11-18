<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Doctor;
use App\Models\Poli;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $polis = Poli::withCount('doctors')->with('latestArticle')->orderBy('id')->get();

        return view('landing.index', [
            'polis'       => $polis,
            'doctorCount' => Doctor::count(),
            'articles'    => $polis->pluck('latestArticle')->filter()->values(),
            'clinic'      => config('sehatyuk.clinic'),
        ]);
    }

    public function archive(Request $request)
    {
        $q = Article::with('poli')->latest('published_at')->latest('id');
        if ($request->filled('poli')) {
            $q->where('poli_id', (int) $request->query('poli'));
        }

        return view('landing.arsip', [
            'articles' => $q->simplePaginate(12)->withQueryString(),
            'polis'    => Poli::orderBy('id')->get(),
            'active'   => (int) $request->query('poli'),
        ]);
    }

    public function show(Article $article)
    {
        $article->load('poli');
        $related = Article::with('poli')->where('poli_id', $article->poli_id)
            ->where('id', '!=', $article->id)->latest('published_at')->take(3)->get();

        return view('landing.artikel', compact('article', 'related'));
    }
}
