<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'key',
        'value',
        'tipe',
        'grup',
        'label',
        'keterangan',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $row = static::where('key', $key)->first();

        if (! $row) {
            return $default;
        }

        return match ($row->tipe) {
            'integer' => (int) $row->value,
            'decimal' => (float) $row->value,
            'boolean' => in_array(strtolower((string) $row->value), ['1', 'true', 'ya', 'yes'], true),
            default   => $row->value,
        };
    }
}
