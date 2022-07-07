<?php

namespace Pezhvak\LaravelTranslator\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslationString extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'key',
        'value',
        'locale',
        'needs_translation',
    ];
}
