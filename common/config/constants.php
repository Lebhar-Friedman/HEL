<?php

include 'environment.php';

if (ENV === "local") {
    define('IMG_URL', 'http://localhost/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://localhost:27017/health_events');
} else if (ENV === "dev") {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
} else if (ENV === "live") {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
} else {
    define('IMG_URL', 'http://13.59.81.62/HEL/backend/web/uploads/');
    define('MONGODB_DSN', 'mongodb://jemiuser:jemi#user@localhost:27017/health_events');
}
?>