<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Article;

class Type extends Model
{
    public function articleTypes() {
        return $this->hasMany(Article::class, 'type_id', 'id');
    }
}
