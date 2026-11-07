<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Config extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $translatedAttributes = ['home_description', 'about_us_description'];

    /**
     * The site has a single configuration row. Create it (empty) if it doesn't exist yet.
     */
    public static function current(): self
    {
        return static::with('translations')->first() ?? static::create()->load('translations');
    }

}
