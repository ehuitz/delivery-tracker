<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\InertiaController;
use App\Models\Package;
use Inertia\Inertia;
use Inertia\Response;

class PackageController extends InertiaController
{
    public function index(): Response
    {
        $packages = Package::with(['originTerminal', 'destinationTerminal'])
            ->paginate(10) // Paginate with 10 items per page
            ->through(fn ($package) => [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'origin_terminal' => $package->originTerminal->formatted_name,
                'destination_terminal' => $package->destinationTerminal->formatted_name,
                'status' => $package->status->value,
                'status_color' => $package->status->color(),
            ]);

        return Inertia::render('Packages/Index', [
            'packages' => $packages,
        ]);
    }
    
}
