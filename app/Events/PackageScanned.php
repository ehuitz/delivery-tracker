<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Package;
use App\Models\User;
use App\Notifications\PackageScannedNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PackageScanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Package $package;

    /**
     * Create a new event instance.
     */
    public function __construct(Package $package)
    {
        $this->package = $package;

        $defaultUser = User::find(1); 

        if ($defaultUser) {
            $defaultUser->notify(new PackageScannedNotification($package));
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('packages'),
            new PrivateChannel('package.' . $this->package->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->package->id,
            'tracking_number' => $this->package->tracking_number,
            'status' => $this->package->status->value,
            'status_color' => $this->package->status->color(),
            'last_scanned_details' => $this->package->last_scan_details,
            'scan_history' => $this->package->scans()
                ->with('terminal')
                ->orderBy('scanned_at', 'asc')
                ->get()
                ->map(fn ($scan) => [
                    'terminal' => $scan->terminal->formatted_name,
                    'scanned_at' => $scan->scanned_at->format('Y-m-d H:i'),
                    'latitude' => $scan->terminal->latitude,
                    'longitude' => $scan->terminal->longitude,
                ]),
        ];
    }

    public function broadcastAs(): string
    {
        return 'package.scanned';
    }
}
