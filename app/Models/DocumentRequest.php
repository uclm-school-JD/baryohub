<?php

namespace App\Models;

use App\Enums\DocumentRequestStatus;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DocumentRequest extends Model
{
    protected $fillable = ['type', 'purpose'];

    protected $attributes = ['status' => DocumentRequestStatus::Pending->value];

    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'status' => DocumentRequestStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DocumentRequest $documentRequest): void {
            $documentRequest->reference = 'BRH-'.Str::upper((string) Str::ulid());
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
