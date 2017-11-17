<?php

include 'environment.php';

define('GOOGLE_API_KEY', 'AIzaSyAhILqlWgDpnH2vFACIMHSATo2-EYd-WRY');
define('GOOGLE_API_KEY_BACKUP', 'AIzaSyCYt8Z4mq08zPf4NUyWsZ4Oq-TX0QaiDkA');
define('GOOGLE_API_KEY_local', 'AIzaSyCRC4Y2HmGSjYMrADCRlTeyk4CASENWyKQ');
define('GOOGLE_API_KEY_local_2', 'AIzaSyCheqAJ6QQHEmTVlOdCofMRNulscHElXZM');

$api_for_all = '';
if (ENV === "local") {
    define('IMG_URL', 'http://localhost/HEL/backend/web/uploads/');
    define('frontend_URL', 'http://localhost/HEL/frontend/web/');
    define('MONGODB_DSN', 'mongodb://localhost:27017/health_events');
//    define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY_local.'&');
    define('GOOGLE_API_URL', 'http://maps.googleapis.com/maps/api/geocode/json?');
    $api_for_all = GOOGLE_API_KEY_local;
} else if (ENV === "dev") {
    define('IMG_URL', 'http://13.58.235.13/HEL/backend/web/uploads/');
    define('frontend_URL', 'http://13.58.235.13/HEL/frontend/web/');
    define('MONGODB_DSN', 'mongodb://localhost:27017/health_events');
//    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
    define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key=' . GOOGLE_API_KEY . '&');
    $api_for_all = GOOGLE_API_KEY;
} else if (ENV === "live") {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('frontend_URL', 'http://healtheventslive.com');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
    define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key=' . GOOGLE_API_KEY . '&');
    $api_for_all = GOOGLE_API_KEY_BACKUP;
} else {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
    define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key=' . GOOGLE_API_KEY . '&');
    $api_for_all = GOOGLE_API_KEY;
}
define('GOOGLE_API_KEY_For_All', $api_for_all);
?>