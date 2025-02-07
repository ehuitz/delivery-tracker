<?php

declare(strict_types=1);

namespace App\Traits\Enums;

trait Transformable
{
    /**
     * Convert enum values to an array.
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Convert enum values to lowercase.
     */
    public static function toLowerArray(): array
    {
        return array_map('strtolower', self::toArray());
    }

    /**
     * Convert enum values to uppercase.
     */
    public static function toUpperArray(): array
    {
        return array_map('strtoupper', self::toArray());
    }

    /**
     * Convert status to uppercase.
     *
     * @return string
     */
    public function toUpper(): string
    {
        return strtoupper($this->value);
    }
}