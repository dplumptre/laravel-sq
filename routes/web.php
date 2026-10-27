<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/boom', function () {
    throw new RuntimeException('Deliberate synchronous failure from /boom');
});

// PHP handles a request synchronously, so there is no direct equivalent of the
// Node version. The nearest useful case is a failure raised deep inside a
// callback chain, which produces a taller stack trace for Sentry to report.
Route::get('/boom-async', function () {
    return Collection::make([1, 2, 3])
        ->map(function (int $value) {
            if ($value === 3) {
                throw new RuntimeException('Deliberate deferred failure from /boom-async');
            }

            return $value;
        })
        ->sum();
});

// Temporary: proves errors report from a plain HTTP request alone.
Route::get('/boom-proof', function () {
    throw new RuntimeException('PROOF-165110-no-artisan-was-run');
});
