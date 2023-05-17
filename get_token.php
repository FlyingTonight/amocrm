<?php
function generateToken($length = 32) {
    $bytes = random_bytes($length);
    return bin2hex($bytes);
}

// Generate a 32-character token
$token = generateToken(16);
echo $token;
?>