<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $item = self::query()->where('key', $key)->first();
        return $item ? $item->value : $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) || is_object($value) ? json_encode($value) : (string) $value, 'group' => $group]
        );
    }

    public static function getMany(array $keys): array
    {
        $items = self::query()->whereIn('key', $keys)->get()->keyBy('key');
        $result = [];
        foreach ($keys as $k) {
            $result[$k] = $items->get($k)?->value ?? null;
        }
        return $result;
    }
}
