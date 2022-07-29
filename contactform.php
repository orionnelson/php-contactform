\<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
#Load a Template from template file
$replace = array('{name}', '{email}', '{message}','{subject}');
$contents = file_get_contents('allenmailer.html');
$errors = [];
$errorMessage = '';
if (!empty($_POST)) {
    $name =  htmlspecialchars(stripslashes(trim($_POST['name'])));
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $message = htmlspecialchars(stripslashes(trim($_POST['message'])));
    $subject = htmlspecialchars(stripslashes(trim($_POST['subject'])));
    if (empty($name)) {
        $errors[] = 'Name is empty';
    }

    if (empty($email)) {
        $errors[] = 'Email is empty';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }

    if (empty($message)) {
        $errors[] = 'Message is empty';
    }
    if (empty($subject)) {
        $errors[] = 'Subject is empty';
    }


    if (empty($errors)) {
        $toEmail = 'contact@allenfort.ca';
        $emailSubject = 'Contact form Message submitted by ' . $name . ' - ' . $subject;
        $headers = ['From' => $email, 'Reply-To' => $email, 'Content-type' => 'text/html; charset=iso-8859-1'];
	$with = array("{$name}","{$email}","{$message}","{$subject}");
	$body = str_replace($replace, $with, $contents);

        #$bodyParagraphs = ["Name: {$name}", "Email: {$email}", "Message: {$message}"];
        #$body = join("<br>", $bodyParagraphs);
	#echo $body;

        if (mail($toEmail, $emailSubject, $body, $headers)) {
            header('Location: thanks.html');
	    http_response_code(200);
        } else {
	    header('Location: thanks.html');
            $errorMessage = 'Oops, something went wrong. Please try again later';
	    #echo "<script type='text/javascript'>alert('$errorMessage');</script>";
	    http_response_code(500);

        }
    } else {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
	#echo $errorMessage;
    }
}

?>
