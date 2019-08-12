<?php

function calendarize ($title, $desc, $ev_date) {

    
    
    /************************************************
    Make an API request authenticated with a service
    account.
    ************************************************/
    /*
    set_include_path( 'google-api-php-client/src/');

    require_once 'Google/Client.php';
    require_once 'Google/Service/Calendar.php';
    */
    require __DIR__ . '/vendor/autoload.php';
    session_start();
    //obviously, insert your own credentials from the service account in the Google Developer's console
    $client = new Google_Client();
    $client_id = '1055116865873-5h7pna131atpb11hhpqi2bvg7ne4gem1.apps.googleusercontent.com';
    $service_account_name = 'kyyti-robotti@pargas-taxi.iam.gserviceaccount.com';
    $application_creds = './creds.json';
    $credentials_file = file_exists($application_creds) ? $application_creds : false;
define("APP_NAME","Google Calendar API PHP");
    $client->setAuthConfig($credentials_file);
    $client->setApplicationName('APP_NAME');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $cal_id = 'managepartaxi@gmail.com';
  
    /*
    if (!strlen($service_account_name) || !strlen($key_file_location))
        echo missingServiceAccountDetailsWarning();

    $client = new Google_Client();
    $client->setApplicationName("Whatever the name of your app is");

    if (isset($_SESSION['service_token'])) {
        $client->setAccessToken($_SESSION['service_token']);
    }

    $key = file_get_contents($key_file_location);
    $cred = new Google_Auth_AssertionCredentials(
        $service_account_name, 
        array('https://www.googleapis.com/auth/calendar'), 
        $key
    );
    $client->setAssertionCredentials($cred);
    if($client->getAuth()->isAccessTokenExpired()) {
        $client->getAuth()->refreshTokenWithAssertion($cred);
    }
    $_SESSION['service_token'] = $client->getAccessToken();
    */
    $calendarService = new Google_Service_Calendar($client);
    /*$calendarList = $calendarService->calendarList;

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
    $optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);
$results = $calendarService->events->listEvents($cal_id, $optParams);

if (count($results->getItems()) == 0) {
  print "No upcoming events found.\n";
} else {
  echo "Upcoming events:";
  echo "<hr>";
  echo "<table>";
  foreach ($results->getItems() as $event) {
    $start = $event->start->dateTime;
    if (empty($start)) {
      $start = $event->start->date;
    }
    echo "<tr>";
    echo"<td>".$event->getSummary()."</td>";
    echo"<td>".$start."</td>";
    echo "</tr>";
  }
  echo "</table>";
}  
}

calendarize($_POST['1'], $_POST['2'], $_POST['3']);

?>