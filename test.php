<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
$req = new \Illuminate\Http\Request();
$req->attributes->set('mobileUser', $user);
try {
    $controller = new \App\Http\Controllers\Api\MobileInvitationController();
    $res = app()->call([$controller, 'index'], ['request' => $req]);
    echo json_encode($res->getData());
} catch (\Throwable $e) {
    echo $e->getMessage();
}
