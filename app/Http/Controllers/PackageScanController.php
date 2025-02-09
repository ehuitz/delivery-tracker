<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use App\Events\PackageScanned;
use App\Models\PackageScan;
use App\Models\Package;

class PackageScanController extends Controller
{
    /**
     * Store a package scan from the Inertia UI request.
     */
    public function store(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'terminal_id' => 'required|exists:terminals,id',
            'scanned_at' => 'required|date',
        ]);

        $package->scans()->create($validated);

        event(new PackageScanned($package->fresh()));

        return redirect()->back()->with([
            'flash.banner' => 'Scan added successfully!',
            'flash.bannerStyle' => 'success',
        ]);
    }

    /**
     * Store a package scan via API request.
     */
    public function storeApi(Request $request, Package $package): JsonResponse
    {
        $validated = $request->validate([
            'terminal_id' => 'required|exists:terminals,id',
            'scanned_at' => 'required|date',
        ]);

        $scan = $package->scans()->create($validated);

        event(new PackageScanned($package->fresh()));

        return response()->json([
            'message' => 'Scan added successfully',
            'scan' => $scan
        ], 201);
    }
}
