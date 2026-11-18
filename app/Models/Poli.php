<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function latestArticle()
    {
        return $this->hasOne(Article::class)->latestOfMany();
    }

    public function toApi(): array
    {
        return [
            'id' => $this->id, 'code' => $this->code, 'name' => $this->name,
            'description' => $this->description,
            'doctors' => $this->doctors->map->toApi()->values(),
        ];
    }
}
