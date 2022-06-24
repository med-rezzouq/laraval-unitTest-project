<?php

use Google\Client;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::get('/drive', function () {
    $client = new Client();
    $client->setClientId('1082134821049-6k5d3k5goca9i47kl9cfgg8bnu8erjip.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://localhost:8000/google-drive/callback');
    $client->setScopes([
        'https://www.googleapis.com/auth/drive.file',

    ]);

    $url =   $client->createAuthUrl();
    return redirect($url);
});


Route::get('/google-drive/callback', function () {

    return request('code');
    // $client = new Client();
    // $client->setClientId('1082134821049-6k5d3k5goca9i47kl9cfgg8bnu8erjip.apps.googleusercontent.com');
    // $client->setClientSecret('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');
    // $client->setRedirectUri('http://localhost:8000/google-drive/callback');
    // $code = request('code');
    // $access_token = $client->fetchAccessTokenWithAuthCode($code);
    // return   $access_token;
});


Route::get('upload', function () {
    $client = new Client();
    $access_token = 'ya29.a0ARrdaM8DohzhLxCRfVYZxtQXEXPs5UYlUz08nhowT-icP-nxH9ci3veBDfTd2puQNQUfUBVDE8Z0uf2ZC9uAIAEP3yHrHkpbmMRzs1dQ20s-bljfgRC-fStLvuuRY2EPrm8woHnReanOLH-CQtx0uIZ991jY';

    $client->setAccessToken($access_token);
    $service = new Google\Service\Drive($client);
    $file = new Google\Service\Drive\DriveFile();

    DEFINE("TESTFILE", 'testfile-small.txt');
    if (!file_exists(TESTFILE)) {
        $fh = fopen(TESTFILE, 'w');
        fseek($fh, 1024 * 1024);
        fwrite($fh, "!", 1);
        fclose($fh);
    }

    $file->setName("Hello World!");
    $result2 = $service->files->create(
        $file,
        [
            'data' => file_get_contents(TESTFILE),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
        ]
    );
});
