<?php

namespace app\models;

use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Yii;
use yii\base\Model;

require __DIR__ . '/../vendor/autoload.php';

class Calendar extends Model
{
    public $name;
    public $date;

    public function create($model)
    {
        $client = $this->auth();
        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event([
            'summary' => $this->name,
            'location' => '800 Howard St., San Francisco, CA 94103',
            'description' => 'A chance to hear more about Google\'s developer products.',
            'start' => [
                'date' => $this->date,
            ],
            'end' => [
                'date' => $this->date,
            ],
        ]);

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);

        $model->evento_id = $event->id;
        $model->update();
    }

    public function delete($id)
    {
        $client = $this->auth();
        $service = new Google_Service_Calendar($client);
        $service->events->delete('primary', $id);
    }

    public function auth()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig('../web/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');

        $tokenPath = '../web/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
}