<?php

require 'vendor/autoload.php';

use Edudip\Next\ApiClient\AbstractRequest;
use Edudip\Next\ApiClient\EdudipNext;
use Edudip\Next\ApiClient\Webinar;
use Edudip\Next\ApiClient\WebinarDate;
use Edudip\Next\ApiClient\Participant;

// Set your API key
EdudipNext::setApiKey('Ve9dDJWfZzacOWlix314pa5U96PpBIuaAC3QPVgCJSVf7204EXQvrb6mXF8b');

// Lists all existing webinars:
$allWebinars = Webinar::all();

foreach ($allWebinars as $webinar) {
    echo $webinar->getTitle(), PHP_EOL;

    foreach ($webinar->getDates() as $webinarDate) {
        echo ' Date: ', $webinarDate->getDate()->format('H:i:s m/d/Y'), PHP_EOL;
        echo ' Duration: ', $webinarDate->getDuration(), ' mins.', PHP_EOL;
    }
}

// Gets detailed data for a single webinar
if (count($allWebinars) !== 0) {
    $singleWebinarDetails = Webinar::getById($allWebinars[0]->getId());
    $creator = $singleWebinarDetails->getUser();

    echo sprintf(
        'Webinar has been created by %s %s',
        $creator['firstname'],
        $creator['lastname']
    ), PHP_EOL;
}

// Creates a new Webinar:

// Create the dates for the webinar 
$webinarDates = [
    new WebinarDate(
        new DateTime('2020-01-01 12:00:00'), // When does the webinar start
        30 // How long does the webinar in minutes
    ),
    new WebinarDate(
        new DateTime('2020-02-01 12:00:00'),
        30
    ),
];

// Creates a webinar
$webinar = Webinar::create(
    'Name/Title of the webinar' . rand(1, 20),
    $webinarDates,
    10, // Max participants
    true // Record webinar
);

// Registers a participant
$participant = new Participant(
    'john.doe@example.com',
    'John',
    'Doe'
);

$registeredDates = $webinar->registerParticipant($participant);
var_dump($registeredDates);die;
foreach ($registeredDates as $registeredDate) {
    echo 'User is registered for webinar date ', $registeredDate['date'], ' room link: ', $registeredDate['room_link'], PHP_EOL;
}

