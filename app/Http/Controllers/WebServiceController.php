<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\WebService;
use App\Services\GoogleDrive;
use App\Services\Zipper;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

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
            'token' => $access_token, 'name' => 'google-drive'
        ]);
        // dd(['hello' => "world"]);
        return   $service;
    }

    public function store(WebService $web_service, GoogleDrive $drive)
    {

        //we need to fetch last 7 days of tasks
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();
        // dd($tasks);
        //create a json file with this data
        $jsonFileName = 'task_dump.json';
        Storage::put("/public/temp/$jsonFileName", TaskResource::collection($tasks)->toJson());

        $zipFileName = Zipper::createZipof($jsonFileName);
        //create a zip file with this json file

        //send this zip to drive


        $access_token = $web_service->token['access_token'];

        $drive->uploadFile($zipFileName, $access_token);
        // Storage::deleteDirectory('public/temp');
        return response('uploaded', Response::HTTP_CREATED);
    }
}
