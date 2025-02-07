<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\Enums\Transformable;

enum PackageStatus: string
{
    use Transformable;

    case PENDING = 'Pending';
    case IN_TRANSIT = 'In Transit';
    case DELIVERED = 'Delivered';
    case FAILED = 'Failed';

    /**
     * Get the corresponding color for each status.
     *
     * @return string Tailwind color class
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'text-slate-600',
            self::IN_TRANSIT => 'text-indigo-600',
            self::DELIVERED => 'text-green-600',
            self::FAILED => 'text-red-600',
        };
    }
}