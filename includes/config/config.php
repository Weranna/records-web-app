<?php
ini_set("session.use_only_cookies", 1);
ini_set("session.use_strict_mode", 1);

session_set_cookie_params([
    'lifetime' => 0,
    'domain' => 'localhost', //! domena
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();

$regeneration_interval = 60 * 30;

if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } else {
        if (time() - $_SESSION['last_regeneration'] >= $regeneration_interval) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
} else {

    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}
