<?php

require_once('Token.php');

$token = new Token();

//Set the user id;
$token->id = 100;
// Set the timestamp of the Token to check later if it's still valid
$token->timestamp = time();

// Sign and encrypt the token;
$token->encrypt();

//Output the Token encrypted message
$encrypted_message = $token->getEncryptedMessage();
echo $encrypted_message."\r\n";

// Create a new Token Object with the encrypted message
$new_token = new Token($encrypted_message);

if ($new_token->decrypt()->isValid()) {
    echo $new_token->id;
}
else
    throw new Exception('Token not valid');
?>
