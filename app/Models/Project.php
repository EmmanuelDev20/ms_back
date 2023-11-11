<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Project extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $translatedAttributes = ['name', 'subtitle', 'description', 'work_made'];

    public function images() {
        return $this->hasMany(Images::class)->orderBy('position', 'asc');
    }
}
