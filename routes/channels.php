<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('packages', function ($user) {
    return true;
});

Broadcast::channel('package.{packageId}', function ($user, $packageId) {
    return true;
});
