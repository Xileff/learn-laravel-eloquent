<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Voucher extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'vouchers';
    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function uniqueIds(): array
    {
        return [$this->primaryKey, 'voucher_code']; // menerapkan uuid, bukan unique constraint
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('is_active', true);
    }

    public function scopeNonActive(Builder $builder)
    {
        $builder->where('is_active', false);
    }
}
