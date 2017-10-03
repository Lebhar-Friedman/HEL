<?php

include 'environment.php';

define('GOOGLE_API_KEY', 'AIzaSyAhILqlWgDpnH2vFACIMHSATo2-EYd-WRY');
define('GOOGLE_API_KEY_BACKUP', 'AIzaSyCYt8Z4mq08zPf4NUyWsZ4Oq-TX0QaiDkA');
define('GOOGLE_API_KEY_local', 'AIzaSyCRC4Y2HmGSjYMrADCRlTeyk4CASENWyKQ');
define('GOOGLE_API_KEY_local_2', 'AIzaSyCheqAJ6QQHEmTVlOdCofMRNulscHElXZM');


if (ENV === "local") {
    define('IMG_URL', 'http://localhost/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://localhost:27017/health_events');
    define('GOOGLE_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json?key='.GOOGLE_API_KEY.'&');
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