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
        $packages = Package::with(['originTerminal', 'destinationTerminal', 'lastScannedAt.terminal'])
            ->paginate(10)
            ->through(fn ($package) => [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'origin_terminal' => $package->originTerminal->formatted_name,
                'destination_terminal' => $package->destinationTerminal->formatted_name,
                'status' => $package->status->value,
                'status_color' => $package->status->color(),
                'last_scanned_details' => $package->last_scan_details,
            ]);

        return Inertia::render('Packages/Index', [
            'packages' => $packages,
        ]);
    }
    
}
