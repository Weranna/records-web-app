<?php
declare(strict_types=1);

function isInputEmpty($login,$password) {

$errors = [];

if (empty($login)) $errors[] = 'Login jest wymagany.';
if (empty($password)) $errors[] = 'Hasło jest wymagane.';

return $errors;

}

function isLoginInvalid($result) { 
    if(!$result)  {
        return true;
    }
    else  {
        return false;
    }
}

function isPasswordInvalid($password, $hashedPwd) { 
    if(!password_verify($password,$hashedPwd)) {
        return true;
    }  else { return false; }
}