<?php

namespace Modules\SharedCore\Models;

use Illuminate\Database\Eloquent\Model;

class ProvincialBranch extends Model
{
    protected $primaryKey = 'code';
    protected $keyType    = 'string';
    public    $incrementing = false;

    protected $fillable = ['name', 'code', 'region'];
}
