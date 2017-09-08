<?php

$host = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING);
if (strstr($host, "localhost")) {
    define("ENV", "local");
} else if (strstr($host, '13.59.81.62')) {
    define("ENV", "dev");
}else if (strstr($host, 'naseersays.com') || strstr($host, 'healtheventslive.com') || strstr($host, 'maavan.org')) {
    define("ENV", "live");
} else {
    define("ENV", "prod");
}