<?php
/**
 * Global Configuration Settings
 * This file defines site-wide constants and variables.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 

|--------------------------------------------------------------------------
| SITE IDENTITY
|--------------------------------------------------------------------------
*/
$site_name     = "Aushadhi";
$site_email    = "hello@apothecary.com";
$site_location = "Kathmandu, Nepal";

/* 

|--------------------------------------------------------------------------
| GLOBAL PATHS (Optional but helpful)
|--------------------------------------------------------------------------
*/
// You can define base URLs here if you move to a live server later
// define('BASE_URL', '/aushadhi-platform/');

?>
