<?php

include 'environment.php';

if (ENV === "local") {
    define('IMG_URL', 'http://localhost/HEL/backend/web/uploads/');
} else if (ENV === "dev") {
    define('IMG_URL', 'http://13.59.81.62/HEL/frontend/web/uploads/');
} else {
    define('IMG_URL', 'Will be updated soon');
}
?>