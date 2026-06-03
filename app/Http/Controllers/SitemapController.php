<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $articles = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->select(['slug', 'updated_at', 'image'])
            ->get();

        $content = view('sitemap', [
            'articles' => $articles,
        ])->render();

        return response($content)
            ->header('Content-Type', 'application/xml');
    }
}
