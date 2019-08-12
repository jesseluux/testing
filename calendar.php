<?php

function calendarize ($title, $desc, $ev_date, $cal_id) {

    session_start();

    /************************************************
    Make an API request authenticated with a service
    account.
    ************************************************/
    require_once 'vendor/autoload.php';

    //obviously, insert your own credentials from the service account in the Google Developer's console
    $client_id = '1055116865873-5h7pna131atpb11hhpqi2bvg7ne4gem1.apps.googleusercontent.com';
    $service_account_name = 'kyyti-robotti@pargas-taxi.iam.gserviceaccount.com';
    $key_file_location = 'creds.json';

    if (!strlen($service_account_name) || !strlen($key_file_location))
        echo missingServiceAccountDetailsWarning();

    $client = new Google_Client();
    $client->setApplicationName("Whatever the name of your app is");

    if (isset($_SESSION['service_token'])) {
        $client->setAccessToken($_SESSION['service_token']);
    }

    $key = file_get_contents($key_file_location);
    $client->setAuthConfig($key_file_location);
    
    if($client->isAccessTokenExpired()) {
        $client->refreshTokenWithAssertion();
    }
    $_SESSION['service_token'] = $client->getAccessToken();

    $calendarService = new Google_Service_Calendar($client);

    $calendarId = 'primary';
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);
$results = $calendarService->events->listEvents($calendarId, $optParams);
$events = $results->getItems();

if (empty($events)) {
    print "No upcoming events found.\n";
} else {
    print "Upcoming events:\n";
    foreach ($events as $event) {
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        printf("%s (%s)\n", $event->getSummary(), $start);
    }
}
    /*
    $calendarList = $calendarService->calendarList;

    //Set the Event data
    $event = new Google_Service_Calendar_Event();
    $event->setSummary($title);
    $event->setDescription($desc);

    $start = new Google_Service_Calendar_EventDateTime();
    $start->setDateTime($ev_date);
    $event->setStart($start);

    $end = new Google_Service_Calendar_EventDateTime();
    $end->setDateTime($ev_date);
    $event->setEnd($end);

    $createdEvent = $calendarService->events->insert($cal_id, $event);

    echo $createdEvent->getId();*/
} 
calendarize($_POST['1'], $_POST['2'], $_POST['3'], 'managepartaxi@gmail.com');
?>