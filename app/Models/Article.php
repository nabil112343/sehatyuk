<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['poli_id', 'title', 'slug', 'summary', 'body', 'category', 'tags', 'week_key', 'source', 'published_at'];

    protected $casts = ['published_at' => 'datetime'];

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function getTagListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->tags))));
    }

    /** Body teks sederhana -> HTML aman: "## " subjudul, "- " poin, sisanya paragraf. */
    public function bodyHtml(): string
    {
        $out = '';
        $list = [];
        $flush = function () use (&$out, &$list) {
            if ($list) { $out .= '<ul>' . implode('', $list) . '</ul>'; $list = []; }
        };
        foreach (preg_split('/\R/', trim(str_replace('**', '', $this->body))) as $line) {
            $line = trim($line);
            if ($line === '') { $flush(); continue; }
            if (str_starts_with($line, '- ')) { $list[] = '<li>' . e(substr($line, 2)) . '</li>'; continue; }
            $flush();
            $out .= str_starts_with($line, '## ') ? '<h3>' . e(substr($line, 3)) . '</h3>' : '<p>' . e($line) . '</p>';
        }
        $flush();
        return $out;
    }
}
