<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Illuminate\Http\Request;
use Google\Client;

class WebServiceController extends Controller
{

    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive.file',
        'https://www.googleapis.com/auth/drive',

    ];


    public function connect($name, Client $client)
    {

        if ($name == 'google-drive') {
            // $client = new Client();
            // $config = config('services.google');
            // $client->setClientId($config['id']);
            // $client->setClientSecret($config['secret']);
            // $client->setRedirectUri($config['redirect_url']);
            $client->setScopes(self::DRIVE_SCOPES);

            $url =   $client->createAuthUrl();
            return response(['url' => $url]);
        }
    }

    public function callback(Request $request, Client $client)
    {

        //we wrap client inside app because to simulate the real test of laravel so like the app launched by boot container 
        // $client = app(Client::class);
        // $config = config('services.google');
        // $client->setClientId($config['id']);
        // $client->setClientSecret($config['secret']);
        // $client->setRedirectUri($config['redirect_url']);

        $access_token = $client->fetchAccessTokenWithAuthCode($request->code);

        $service =    WebService::create([
            'user_id' => auth()->id(),
            'token' => json_encode([
                'access_token' => $access_token,

            ]),  'name' => 'google-drive'
        ]);
        // dd(['hello' => "world"]);
        return   $service;
    }
}
