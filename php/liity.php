<?php 
// Use utf-8 for response
header('Content-Type: text/html; charset=utf-8');

// Variable intialization
$email_from = "mahjong@nettisivut.fi";
$email_to = "mahjong-hallitus@list.ayy.fi";
$nameErr = $emailErr = $hometownErr = $schoolErr = "";
$name = $email = $hometown = $school = "";
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Form validation
  if (empty($_POST["name"])) {
    $response["nameErr"] = "Nimi on pakollinen kenttä";
  } else {
    $name = test_input($_POST["name"]);
  }
  
  if (empty($_POST["email"])) {
    $response["emailErr"] = "Sähköposti on pakollinen kenttä";
  } else {
    $email = test_input($_POST["email"]);
  }
  
  if (empty($_POST["hometown"])) {
    $response["hometownErr"] = "Kotikaupunki on pakollinen kenttä";
  } else {
    $hometown = test_input($_POST["hometown"]);
  }
  
  if (empty($_POST["school"])) {
    $response["schoolErr"] = "Korkeakoulu/Muu on pakollinen kenttä";
  } else {
    $school = test_input($_POST["school"]);
  }
  
  // Error handling
  if (count($response)) {
    // If errors exist, set succes to false and end the script execution
    $response["success"] = false;
    echo json_encode($response);
    die();
  }

  // Set the emails headers
  $headers = 'From: '.$email_from."\r\n".
  "Content-Type: text/plain; charset=UTF-8\r\n".
  'X-Mailer: PHP/'.phpversion();
  
  // Set the emails message content
  $mail_message = 
  "Nimi: ".$name."\r\n".
  "Sähköposti: ".$email."\r\n".
  "Kotikaupunki: ".$hometown."\r\n".
  "Korkeakoulu/Muu: ".$school."\r\n\r\n";
  
  $date = date('d.m.y H:i:s')."\r\n";
  
  // Send the mail
  mail($email_to, "Liittymisilmoitus", $mail_message, $headers);  
  
  // Send reponse object with success set to true
  $response["success"] = true;
  echo json_encode($response);
}

// Function to format data neatly
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>