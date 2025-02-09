<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PackageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

class Package extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'tracking_number',
        'origin_terminal_id',
        'destination_terminal_id',
        'status'
    ];

    protected $casts = [
        'status' => PackageStatus::class,
    ];

    /**
     * Get the terminal where the package originates from.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function originTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'origin_terminal_id');
    }

    /**
     * Get the terminal where the package is being delivered.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destinationTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'destination_terminal_id');
    }

    /**
     * Get all scans associated with the package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scans(): HasMany
    {
        return $this->hasMany(PackageScan::class);
    }

    /**
     * Get the most recent scan for the package.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastScannedAt(): HasOne
    {
        return $this->hasOne(PackageScan::class)->latestOfMany();
    }

    /**
     * Get the last scanned terminal and time as a single formatted string.
     *
     * @return string
     */
    public function getLastScanDetailsAttribute(): string
    {
        if ($this->lastScannedAt && $this->lastScannedAt->scanned_at) {
            return $this->lastScannedAt->terminal?->formatted_name . ' - ' . $this->lastScannedAt->scanned_at->format('Y-m-d H:i');
        }

        return 'Booked';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'tracking_number' => $this->tracking_number,
            'origin_terminal' => $this->originTerminal->formatted_name,
            'destination_terminal' => $this->destinationTerminal->formatted_name,
            'status' => $this->status->value,
        ];
    }

}
