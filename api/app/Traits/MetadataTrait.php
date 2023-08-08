<?php

namespace App\Traits;

use Illuminate\Support\Facades\{
    Http,
    Storage,
    Schema
};
use App\Models\Metadata;

trait MetadataTrait
{
    public $default_hidden = [
        'updated_at',
        'deleted_at'
    ];

    public function metadata ()
    {
        $model = $this->modelName;

        return $this->hasOne(Metadata::class, 'parent_id', 'id')
        ->where('model', $model)
        ->whereNull('deleted_at');
    }
}
