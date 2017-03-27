<?php
    /**
     *StatsForDownloadCounter
     *
     *PHP Version 7.0
     *
     *@category Lib
     *@package  StatsForDownloadCounter
     *@author   Rafael Moreira <i@rafaelmoreira.net>
     *@license  BSD 3-clause
     *@link     http://github.com/rafamoreira/download-counter
     */

$secret = file_get_contents("../secret.txt");

list($user, $pass) = array_map('trim', explode(':', $secret));
$loginSuccessful = false;

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {

    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    if ($username == $user && $password == $pass) {
        $loginSuccessful = true;
    }
}

if (!$loginSuccessful) {

    header('WWW-Authenticate: Basic realm="Stats Page"');
    header('HTTP/1.0 401 Unauthorized');

    print "Login failed!\n";

} else {
    print "<table border=1><thead>";
    print "<tr><td>id</td><td>File</td><td>Datetime</td><td>Origem</td></tr>";
    print "</thead>";

    try
    {
        $db = new PDO('sqlite:../main.db');
        $result = $db->query("SELCT * FROM downloads");
        foreach ($result as $row) {
            print "<tr>";
            print "<td>" . $row['id'] . "</td>";
            print "<td>" . $row['file'] . "</td>";
            print "<td>" . $row['date'] . "</td>";
            if ($row['reference'] == 1) {
                print "<td>Download</td>";
            } else if ($row['reference'] == 2) {
                print "<td>Player</td>";
            } else {
                print "<td>iTunes / Feed</td>";
            }
        }
        $db = null;
    }
    catch(PDOException $e){
        print 'Exception : '.$e->getMessage();
    }
}
