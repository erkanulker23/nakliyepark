<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContactMessage extends Model
{
    protected $table = 'site_contact_messages';

    protected $fillable = ['name', 'email', 'subject', 'message'];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
