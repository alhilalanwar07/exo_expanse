<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BackgroundMusic;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $musics = BackgroundMusic::where('is_active', true)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('artist', 'like', "%{$search}%");
                });
            })
            ->orderBy('title', 'asc')
            ->paginate(15);
            
        $musics->getCollection()->transform(function ($music) {
            $music->file_url = asset($music->file_path);
            return $music;
        });

        return response()->json($musics);
    }
}
