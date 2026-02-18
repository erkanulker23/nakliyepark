<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teklif extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teklifler';

    protected $fillable = ['ihale_id', 'company_id', 'amount', 'message', 'status', 'pending_amount', 'pending_message', 'reject_reason', 'accepted_at', 'rejected_at'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'pending_amount' => 'decimal:2',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    /** Kabulden sonra geri alma süresi (dakika). Bu süre içinde müşteri kabulü geri alabilir. */
    public const ACCEPT_UNDO_MINUTES = 10;

    public function canUndoAccept(): bool
    {
        if ($this->status !== 'accepted' || ! $this->accepted_at) {
            return false;
        }
        return $this->accepted_at->addMinutes(self::ACCEPT_UNDO_MINUTES)->isFuture();
    }

    public function hasPendingUpdate(): bool
    {
        return $this->pending_amount !== null;
    }

    /** Kullanıcı bu teklifin tutarını görebilir mi? (admin, ihale sahibi veya kendi firması) */
    public function canShowAmountTo(?\App\Models\User $user, \App\Models\Ihale $ihale): bool
    {
        if (! $user) {
            return false;
        }
        if ($user->isAdmin()) {
            return true;
        }
        if ($ihale->user_id && $ihale->user_id === $user->id) {
            return true;
        }
        if ($user->isNakliyeci() && $user->company && $this->company_id === $user->company->id) {
            return true;
        }
        return false;
    }

    /** Nakliyeci kendi teklifini düzenleme talebi gönderebilir mi? */
    public function canRequestUpdateBy(?\App\Models\User $user): bool
    {
        if (! $user || ! $user->isNakliyeci() || ! $user->company) {
            return false;
        }
        return $this->company_id === $user->company->id && $this->status === 'pending';
    }

    public function ihale(): BelongsTo
    {
        return $this->belongsTo(Ihale::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class, 'teklif_id')->orderBy('created_at');
    }
}
