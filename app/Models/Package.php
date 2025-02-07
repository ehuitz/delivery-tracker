<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PackageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    use HasFactory;

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

}
