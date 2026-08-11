<?php

$password = "Admin_Mahatar578960";

$hash = password_hash($password, PASSWORD_DEFAULT);

echo $hash;

?>