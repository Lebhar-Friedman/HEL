<?php

include 'environment.php';

define('GOOGLE_API_KEY', 'AIzaSyAhILqlWgDpnH2vFACIMHSATo2-EYd-WRY');

if (ENV === "local") {
    define('IMG_URL', 'http://localhost/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://localhost:27017/health_events');
    define('GOOGLE_API_URL', 'http://maps.googleapis.com/maps/api/geocode/json?');
} else if (ENV === "dev") {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
     define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY.'&');
} else if (ENV === "live") {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
     define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY.'&');
} else {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
    define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY.'&');
}
?>