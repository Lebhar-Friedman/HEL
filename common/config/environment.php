<?php
$host = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING);
if (strstr($host, "localhost")) {
    define("ENV", "local");
}else{
    define("ENV", "prod");
}