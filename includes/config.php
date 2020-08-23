<?php

session_start();

require_once('classes/user.php');
require_once('classes/phpmailer/mail.php');


//set timezone
date_default_timezone_set('Europe/London');

//database credentials
define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBNAME', 'job');
define('DIR', 'localhost/dev/reg/');
define('SITEEMAIL', 'noreply@domain.com');
define('MIN_PASSWORD_LENGTH', 4);

function get_db()
{
    $db = null;
    try {

        //create PDO connection
        $db = new PDO("mysql:host=" . DBHOST . ";charset=utf8mb4;dbname=" . DBNAME, DBUSER, DBPASS);
        //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);//Suggested to uncomment on production websites
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Suggested to comment on production websites
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        //show error
        echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        exit;
    }

    return $db;
}

$db = get_db();
$user = get_user($db);
