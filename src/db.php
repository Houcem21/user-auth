<?php

$user = "auth_user";
$pass = "auth_pass";

try {
    $dbh = new PDO('pgsql:host=db;port=5432;dbname=auth_db;user=auth_user;password=auth_pass', $user, $pass);
    $sth = $dbh->query('SELECT * FROM users');
    $users = $sth->fetchAll();
    var_dump($users);
} catch (PDOException $e) {
    echo $e;
}


// and now we're done; close it
// $sth = null;
// $dbh = null;


?>