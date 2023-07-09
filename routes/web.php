<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use OpenTelemetry\API\Globals;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $tracer = Globals::tracerProvider()->getTracer("test");

    $span = $tracer
        ->spanBuilder('manual-span')
        ->startSpan();
    $result = random_int(1, 6);
    $span
        ->addEvent('rolled dice', ['result' => $result])
        ->end();

    $span = $tracer->spanBuilder("test-span")->startSpan();
    $result = Http::get("https://google.co.jp");
    $span->addEvent("Access Google", ['result' => $result->status()])->end();

    $user = User::firstOrNew(
        ['name' => "Test"],
        ["email" => 'sample@example.com', "password" => "password"]
    );

    Storage::disk('local')->put('example.txt', 'Content');
    $txt = Storage::disk('local')->get('example.txt');

    Cache::set("hoge", "hoge");
    $cache = Cache::get("hoge");

    return view('welcome', ['result' => $result, 'user' => $user, 'txt' => $txt, 'cache' => $cache]);
});

Route::get('/exception', function () {

    $result = Http::get("https://google.co.jpa");

    return view('welcome');
});
