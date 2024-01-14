<?php 
// Use utf-8 for response
header('Content-Type: text/html; charset=utf-8');

// Variable intialization
$email_from = "palaute@mahjong.ayy.fi";
$email_to = "mahjong-hallitus@list.ayy.fi";
$subjectErr = $messageErr = "";
$subject = $message = "";
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Form validation
  if (empty($_POST["subject"])) {
    $response["subjectErr"] = "Aihe on pakollinen kenttä";
  } else {
    $subject = test_input($_POST["subject"]);
  }
  
  if (empty($_POST["message"])) {
    $response["messageErr"] = "Palaute on pakollinen kenttä";
  } else {
    $message = test_input($_POST["message"]);
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
  "Aihe: ".$subject."\r\n".
  "Palaute: ".$message."\r\n";
  
  $date = date('d.m.y H:i:s')."\r\n";
  
  // Send the mail
  mail($email_to, "Palautelaatikko", $mail_message, $headers);  
  
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