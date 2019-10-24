<?php

    function check_username($data){
        $form_errors = array();
        $key = 'username';
        if(array_key_exists($key, $data)){
            if($_POST[$key] != null){
                if(strlen(trim($_POST[$key])) < 8){
                    $form_errors[] = $key . " must be atleast 8 characters long";
                }
            }
        }
        return $form_errors;
    }

    function check_email($data){
        $form_errors = array();
        $key = 'email';
        if(array_key_exists($key, $data)){
            if($_POST[$key] != null){
                if(strlen(trim($_POST[$key])) < 8){
                    $form_errors[] = $key . " does not meet length requirement";
                }
                $key = filter_var($key, FILTER_SANITIZE_EMAIL);
                if(filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false){
                    $form_errors[] = $key . " is not a valid email address";
                }
            }
        }
        return $form_errors;
    }

    function cheack_password($data){
        $form_errors = array();
        $key = 'password';
        $key_repassword = 'repassword';
        if(array_key_exists($key, $data) && array_key_exists($key_repassword, $data)){
            if($_POST[$key] != null && $_POST[$key_repassword] != null){
                $password = $_POST[$key];
                $repassword = $_POST[$key_repassword];
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number    = preg_match('@[0-9]@', $password);

                if(!$uppercase) {
                    $form_errors[] = $key . " needs to include a uppercase letter";
                }
                if(!$lowercase) {
                    $form_errors[] = $key . " needs to include a lowercase letter";
                }
                if(!$number) {
                    $form_errors[] = $key . " needs to include a number";
                }
                if(strlen(trim($_POST[$key])) < 8) {
                    $form_errors[] = $key . " needs be atleast 8 characters long";
                }
                if(strcmp($password, $repassword) != 0){
                    $form_errors[] = $key . " passwords dont match";
                }
            }else{
                $form_errors[] = " key is equal null";
            }
        }else{
            $form_errors[] = " key is equal null";
        }
        return $form_errors;
    }

    function show_errors($form_errors_array){
        $errors = "<p><ul style='color:red;'>";

        foreach($form_errors_array as $the_error){
            $errors .= "<li> {$the_error} </li>";
        }
        $errors .= "</ul></p>";
        return $errors;
    }
?>