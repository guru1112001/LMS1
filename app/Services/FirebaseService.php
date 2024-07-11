<?php

namespace App\Services;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)
        ->withServiceAccount(base_path(config('services.firebase.credentials')));
        $this->messaging = $firebase->createMessaging();
    }

    
    
    public function sendNotification($deviceToken, $title, $body)
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        $this->messaging->send($message);
    }
}

//  use Google\Auth\CredentialsLoader; 
//  use GuzzleHttp\Client;
//  use Illuminate\Support\Facades\Log;
 
//  class FirebaseService
//  {
//      private $client;    
//      private $projectId;
 
//      public function __construct()
//      {
//          $this->projectId = 'lmsnotification-741ae';    
//          $this->client = new Client();
//      }
 
//      public function sendNotification($deviceToken, $title, $body)
//      {
//          $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";    
         
//          $message = [
//              'message' => [
//                  'token' => $deviceToken,    
//                  'notification' => [
//                      'title' => $title,    
//                      'body' => $body,
//                  ],
                
//              ],
//          ];
 
//          Log::info("Sending notification to token: $deviceToken with title: $title and body: $body");
//          Log::info("Message being sent: " . json_encode($message, JSON_PRETTY_PRINT));
 
//          $accessToken = $this->getAccessToken();
 
//          if ($accessToken) {
//              try {
//                  $response = $this->client->post($url, [
//                      'headers' => [
//                          'Authorization' => 'Bearer ' . $accessToken,    
//                          'Content-Type' => 'application/json',
//                      ],
//                      'json' => $message,
//                  ]);
 
//                  Log::info("Notification response status: " . $response->getStatusCode());
//                  Log::info("Notification response body: " . $response->getBody());
 
//                  return $response->getStatusCode();
//              } catch (\Exception $e) {
//                  Log::error("Failed to send notification: " . $e->getMessage());    
//                  return null;
//              }
//          } else {
//              Log::error("Failed to obtain access token.");    
//              return null;
//          }
//      }
 
//      private function getAccessToken()
//      {
//          $credentialsPath = 'C:\xampp\htdocs\content-management-system\firebase_credentials.json';    
//          Log::info("Using service account file at {$credentialsPath}");
//          $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
 
//          if (file_exists($credentialsPath)) {
//              $credentialsContent = json_decode(file_get_contents($credentialsPath), true);    
//              if (isset($credentialsContent['type']) && $credentialsContent['type'] === 'service_account') {
//                  $credentials = CredentialsLoader::makeCredentials($scopes, $credentialsContent);    
//                  $credentials->fetchAuthToken();
 
//                  return $credentials->getLastReceivedToken()['access_token'];
//              } else {
//                  Log::error("The service account file is missing the 'type' field or 'type' is not 'service_account'.");    
//              }
//          } else {
//              Log::error("Service account file not found at {$credentialsPath}");    
//          }
 
//          return null;
//      }
//  }

