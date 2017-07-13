<?php

include 'environment.php';

if (ENV === "local") {
    define('IMG_URL', 'http://localhost/HEL/backend/web/uploads/');
}else{
    define('IMG_URL','will be defined for production environment');
}
?>