<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\InertiaController;
use App\Models\Terminal;
use Inertia\Inertia;
use Inertia\Response;

class TerminalController extends InertiaController
{
    public function index(): Response
    {
        return Inertia::render('Terminals/Index', [
            'terminals' => Terminal::all()->map(function ($terminal) {
                return [
                    'id' => $terminal->id,
                    'name' => $terminal->name,
                    'city' => $terminal->city,
                    'latitude' => $terminal->formatted_latitude,
                    'longitude' => $terminal->formatted_longitude,
                ];
            }),
        ]);
    }
}
