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
     * Generate a full SEO-optimized blog article with metadata.
     */
    public function generate(string $title, ?string $imageDescription = null): ?array
    {
        $prompt = $this->buildPrompt($title, $imageDescription);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($this->baseUrl.'/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getSystemPrompt(),
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.3,
                'top_p' => 0.8,
                'max_tokens' => 4096,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $rawContent = $data['choices'][0]['message']['content'] ?? null;

                if ($rawContent) {
                    return $this->parseGeneratedContent($rawContent);
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

    private function getSystemPrompt(): string
    {
        return <<<'SYSTEM'
Kamu adalah penulis konten SEO profesional berbahasa Indonesia yang ahli dalam pembuatan artikel blog berkualitas tinggi untuk website undangan digital ExoInvite.

Keahlianmu:
- Menulis artikel SEO-friendly yang mendalam dan komprehensif
- Struktur heading hierarchy yang benar (H2, H3) untuk crawlability
- Natural keyword placement (keyword density 1-2%)
- Menulis dengan gaya conversational tapi tetap autoritatif
- Membuat konten yang menjawab search intent pengguna

Panduan penulisan:
1. JANGAN mulai dengan judul (judul ditampilkan terpisah oleh sistem)
2. Gunakan format Markdown: ## untuk H2, ### untuk H3, **bold**, *italic*
3. Setiap paragraf 2-4 kalimat, mudah di-scan
4. Sertakan bullet points/numbered list untuk informasi penting
5. Tulis minimal 800-1200 kata
6. Gunakan transition words antar paragraf
7. Sertakan data/statistik bila relevan (gunakan angka yang masuk akal)
8. Buat internal linking suggestions dengan format [teks link](/path)
SYSTEM;
    }

    private function buildPrompt(string $title, ?string $imageDescription): string
    {
        $prompt = <<<PROMPT
Buatkan artikel blog SEO-optimized dengan judul: "{$title}"

PROMPT;

        if ($imageDescription) {
            $prompt .= "Deskripsi gambar yang menyertai artikel: {$imageDescription}\n\n";
        }

        $prompt .= <<<'PROMPT'
FORMAT OUTPUT (WAJIB IKUTI PERSIS):
Tulis output dengan format berikut, pisahkan setiap bagian dengan delimiter yang tepat:

---META_START---
meta_description: [Tulis meta description 140-155 karakter yang compelling, mengandung keyword utama, dan memicu klik. Gunakan power words.]
focus_keyword: [Satu keyword utama yang paling relevan, 2-4 kata]
meta_keywords: [5-8 keyword relevan dipisahkan koma, termasuk LSI keywords]
---META_END---

---CONTENT_START---
[Tulis artikel lengkap di sini dengan struktur:]

## [H2 Introduction - gunakan keyword utama secara natural]

Paragraf pembuka yang menarik, langsung address masalah/kebutuhan pembaca. Gunakan hook yang kuat: pertanyaan, fakta menarik, atau situasi relatable. Masukkan keyword utama di 100 kata pertama.

## [H2 yang mengandung keyword/variasi keyword]

Pembahasan mendalam dengan sub-section jika perlu:

### [H3 sub-topik]
Detail penjelasan...

### [H3 sub-topik]
Detail penjelasan...

## [H2 - Manfaat/Keuntungan/Tips (sesuai konteks)]

- **Point 1**: Penjelasan detail
- **Point 2**: Penjelasan detail
- **Point 3**: Penjelasan detail

## [H2 - FAQ atau Pertanyaan Umum]

### Pertanyaan 1?
Jawaban informatif...

### Pertanyaan 2?
Jawaban informatif...

### Pertanyaan 3?
Jawaban informatif...

## Kesimpulan

Ringkasan engaging yang memperkuat pesan utama. Sertakan CTA (call-to-action) yang relevan.

---CONTENT_END---

KETENTUAN PENTING:
- Bahasa Indonesia yang natural, tidak kaku/robotic
- Keyword utama muncul di: paragraf pertama, minimal 2 heading H2, dan kesimpulan
- Gunakan variasi keyword (LSI) secara natural di seluruh artikel
- Sertakan section FAQ dengan 3-5 pertanyaan relevan (ini sangat penting untuk featured snippets Google)
- Setiap H2 section minimal 150 kata
- Total artikel minimal 800 kata
- Tulis seolah kamu expert di topik ini
- Gunakan numbered list atau bullet points minimal 2x dalam artikel
- Sisipkan internal link relevan ke /blog atau fitur ExoInvite seperti /i/demo
PROMPT;

        return $prompt;
    }

    /**
     * Parse the structured AI output into separate fields.
     */
    private function parseGeneratedContent(string $rawContent): array
    {
        $result = [
            'content' => '',
            'excerpt' => '',
            'meta_description' => '',
            'focus_keyword' => '',
            'meta_keywords' => '',
        ];

        // Extract META block
        if (preg_match('/---META_START---\s*(.*?)\s*---META_END---/s', $rawContent, $metaMatch)) {
            $metaBlock = $metaMatch[1];

            if (preg_match('/meta_description:\s*(.+)/i', $metaBlock, $m)) {
                $result['meta_description'] = $this->cleanMetaValue($m[1], 160);
            }
            if (preg_match('/focus_keyword:\s*(.+)/i', $metaBlock, $m)) {
                $result['focus_keyword'] = $this->cleanMetaValue($m[1], 100);
            }
            if (preg_match('/meta_keywords:\s*(.+)/i', $metaBlock, $m)) {
                $result['meta_keywords'] = $this->cleanMetaValue($m[1], 255);
            }
        }

        // Extract CONTENT block
        if (preg_match('/---CONTENT_START---\s*(.*?)\s*---CONTENT_END---/s', $rawContent, $contentMatch)) {
            $result['content'] = trim($contentMatch[1]);
        } else {
            // Fallback: remove meta block and use remaining content
            $content = preg_replace('/---META_START---.*?---META_END---/s', '', $rawContent);
            $result['content'] = trim($content);
        }

        // Generate excerpt from content
        $result['excerpt'] = $this->extractExcerpt($result['content']);

        // Fallback meta_description from excerpt
        if (empty($result['meta_description'])) {
            $result['meta_description'] = \Illuminate\Support\Str::limit($result['excerpt'], 155);
        }

        return $result;
    }

    private function cleanMetaValue(string $value, int $maxLength): string
    {
        $value = trim($value, " \t\n\r\0\x0B[]\"'");

        return \Illuminate\Support\Str::limit($value, $maxLength, '');
    }

    private function extractExcerpt(string $content, int $length = 200): string
    {
        $plainText = strip_tags(str_replace(["\n", "\r", '#', '*', '-'], ' ', $content));
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        return str(substr($plainText, 0, $length))->trim()->toString();
    }
}
