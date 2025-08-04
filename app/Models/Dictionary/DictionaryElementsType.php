<?php

namespace App\Models\Dictionary;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dictionary\DictionaryElement;

class DictionaryElementsType extends Model
{
    protected $fillable = ['name', 'description'];

    public function dictionaryElements()
    {
        return $this->hasMany(DictionaryElement::class);
    }
}
