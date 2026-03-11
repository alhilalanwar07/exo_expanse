<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Articles: " . \App\Models\Article::count() . "\n";
foreach (\App\Models\Article::all() as $article) {
    echo "  #{$article->id}: [{$article->status}] {$article->title}\n";
}
echo "\nQueued jobs: " . \Illuminate\Support\Facades\DB::table('jobs')->count() . "\n";
echo "Failed jobs: " . \Illuminate\Support\Facades\DB::table('failed_jobs')->count() . "\n";
