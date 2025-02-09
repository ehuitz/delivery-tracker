<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\InertiaController;
use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PackageController extends InertiaController
{
    public function index(Request $request): Response
    {
        $search = $request->query('search');

        $packagesQuery = Package::search($search)
            ->query(fn ($query) => $query->with(['originTerminal', 'destinationTerminal', 'lastScannedAt.terminal']));

        $packages = $packagesQuery->paginate(10)->through(fn ($package) => [
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
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function show(Package $package): Response
    {
        return Inertia::render('Packages/Show', [
            'package' => [
                'id' => $package->id,
                'tracking_number' => $package->tracking_number,
                'origin_terminal' => $package->originTerminal->formatted_name,
                'destination_terminal' => $package->destinationTerminal->formatted_name,
                'status' => $package->status->value,
                'status_color' => $package->status->color(),
                'last_scanned_details' => $package->last_scan_details,
                'scan_history' => $package->scans()->with('terminal')->orderBy('scanned_at', 'asc')->get()
                    ->map(fn ($scan) => [
                        'terminal' => $scan->terminal->formatted_name,
                        'scanned_at' => $scan->scanned_at->format('Y-m-d H:i'),
                    ]),
            ],
            'terminals' => \App\Models\Terminal::select('id', 'name', 'city')->get(),
        ]);
    }
    
}
