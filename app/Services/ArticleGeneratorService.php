<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArticleGeneratorService
{
    private string $baseUrl;
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->baseUrl = config('services.nvidia.base_url', 'https://integrate.api.nvidia.com/v1');
        $this->apiKey = config('services.nvidia.api_key');
        $this->model = config('services.nvidia.model', 'meta/llama-3.3-70b-instruct');
    }

    /**
     * Generate a blog article based on an image title/caption.
     */
    public function generate(string $title, ?string $imageDescription = null): ?array
    {
        $prompt = $this->buildPrompt($title, $imageDescription);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah penulis artikel blog profesional berbahasa Indonesia. Tulis artikel yang menarik, informatif, dan SEO-friendly. Gunakan format Markdown untuk struktur artikel.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.2,
                'top_p' => 0.7,
                'max_tokens' => 2048,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    return [
                        'content' => $content,
                        'excerpt' => $this->extractExcerpt($content),
                    ];
                }
            }

            Log::error('Article generation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Article generation exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function buildPrompt(string $title, ?string $imageDescription): string
    {
        $prompt = "Buatkan artikel blog lengkap dengan judul: \"{$title}\"\n\n";

        if ($imageDescription) {
            $prompt .= "Deskripsi gambar yang menyertai artikel: {$imageDescription}\n\n";
        }

        $prompt .= "Ketentuan:\n";
        $prompt .= "- Tulis dalam Bahasa Indonesia\n";
        $prompt .= "- Panjang artikel minimal 500 kata\n";
        $prompt .= "- Gunakan format Markdown (heading, paragraf, bold, italic)\n";
        $prompt .= "- Sertakan introduction yang menarik\n";
        $prompt .= "- Bagi menjadi beberapa sub-heading yang relevan\n";
        $prompt .= "- Akhiri dengan kesimpulan\n";
        $prompt .= "- Jangan menyertakan judul utama di awal (judul akan ditampilkan terpisah)\n";
        $prompt .= "- Tulis secara natural dan engaging\n";

        return $prompt;
    }

    private function extractExcerpt(string $content, int $length = 200): string
    {
        $plainText = strip_tags(str_replace(["\n", "\r", '#', '*', '-'], ' ', $content));
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        return str(substr($plainText, 0, $length))->trim()->toString();
    }
}
