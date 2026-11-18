<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Poli;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateArticles extends Command
{
    protected $signature = 'articles:generate {--force : Buat lagi walau minggu ini sudah ada} {--poli= : Kode poli saja, mis. GG}';
    protected $description = 'Buat artikel kesehatan mingguan per poli memakai Gemini AI';

    public function handle(): int
    {
        if (!config('sehatyuk.gemini.key')) {
            $this->error('GEMINI_API_KEY belum diisi di .env');
            return self::FAILURE;
        }

        $week = now()->format('o-\WW');
        $polis = Poli::query()
            ->when($this->option('poli'), fn ($q, $c) => $q->where('code', strtoupper($c)))
            ->orderBy('id')->get();

        $made = 0;
        $called = false;
        foreach ($polis as $poli) {
            $exists = Article::where('poli_id', $poli->id)->where('week_key', $week)->where('source', 'gemini')->exists();
            if ($exists && !$this->option('force')) {
                $this->line("- {$poli->name}: sudah ada untuk {$week}, dilewati");
                continue;
            }
            if ($called) sleep(6); // jaga batas request per menit pada free tier
            $called = true;

            try {
                $d = $this->generate($poli);
                Article::create([
                    'poli_id'      => $poli->id,
                    'title'        => Str::limit($d['title'], 120, ''),
                    'slug'         => Str::slug(Str::limit($d['title'], 70, '')) . '-' . Str::lower(Str::random(4)),
                    'summary'      => Str::limit($d['summary'], 200, '...'),
                    'body'         => $d['body'],
                    'category'     => in_array($d['category'] ?? '', ['Pencegahan', 'Tips Sehat', 'Edukasi']) ? $d['category'] : 'Edukasi',
                    'tags'         => implode(',', array_slice((array) ($d['tags'] ?? []), 0, 3)),
                    'week_key'     => $week,
                    'source'       => 'gemini',
                    'published_at' => now(),
                ]);
                $made++;
                $this->info("OK  {$poli->name}: {$d['title']}");
            } catch (\Throwable $e) {
                Log::warning("articles:generate {$poli->code}: " . $e->getMessage());
                $this->warn("GAGAL {$poli->name}: " . $e->getMessage());
            }
        }

        $this->info("Selesai: {$made} artikel baru ({$week}).");
        return self::SUCCESS;
    }

    private function generate(Poli $poli): array
    {
        $recent = Article::where('poli_id', $poli->id)->latest('id')->take(8)->pluck('title')->implode('; ') ?: '-';
        $prompt = <<<TXT
Kamu penulis konten edukasi kesehatan untuk klinik di Indonesia. Tulis SATU artikel pencegahan atau tips praktis untuk {$poli->name} ({$poli->description}).
Aturan:
- Bahasa Indonesia yang ramah dan mudah dipahami orang awam.
- Pilih topik spesifik yang umum dialami masyarakat (contoh untuk poli gigi: gigi berlubang dan cara mencegahnya).
- Jangan mendiagnosis, jangan menyebut dosis obat, jangan menjanjikan kesembuhan.
- Sebutkan kapan sebaiknya periksa ke dokter.
- Hindari topik dan judul yang mirip dengan artikel sebelumnya: {$recent}
- Isi 300-400 kata. Pisahkan paragraf dengan baris kosong. Subjudul diawali "## ". Poin tips diawali "- ". Tanpa format markdown lain (tanpa tanda bintang).
Balas HANYA JSON dengan kunci: title (maks 90 karakter), summary (maks 160 karakter), category ("Pencegahan", "Tips Sehat", atau "Edukasi"), tags (array 2-3 kata tanpa #), body.
TXT;

        $model = config('sehatyuk.gemini.model');
      $res = Http::timeout(90)->retry(4, 15000, throw: false)
            ->withHeaders(['x-goog-api-key' => config('sehatyuk.gemini.key')])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'contents'         => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['responseMimeType' => 'application/json', 'temperature' => 0.7],
            ]);

        if ($res->failed()) {
            throw new \RuntimeException('HTTP ' . $res->status() . ' - ' . ($res->json('error.message') ?? 'permintaan ke Gemini gagal'));
        }

        $text = trim((string) $res->json('candidates.0.content.parts.0.text'));
        $text = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $text);
        $d = json_decode($text, true);

        if (!is_array($d) || empty($d['title']) || empty($d['summary']) || empty($d['body'])) {
            throw new \RuntimeException('Format jawaban Gemini tidak sesuai.');
        }
        return $d;
    }
}
