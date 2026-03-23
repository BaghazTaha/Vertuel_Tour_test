<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::create('/api/trainer/1', 'GET');
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');

    $user = App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
    }

    $response = $kernel->handle($request);
    echo "STATUS: " . $response->getStatusCode() . "\n";
} catch (\Throwable $e) {
    file_put_contents('err.txt', $e->getMessage() . "\n" . $e->getFile() . "\n" . $e->getTraceAsString());
}
