<?php
$host = 'localhost'; //host koji korsitimo
$user = 'root'; //korisniko ime, za xmapp je podrazumevano root
$pass = ''; //lozinka nema za root username u xmapp
$db = 'vic_generator'; //ime baze

$conn = new mysqli($host, $user, $pass, $db); //pokusavamo se povezemo na bazu sa podacima koje smo gore naveli

if ($conn->connect_error) { //da li je doslo do greske za povezivanje
    die("Неуспешно повезивање са базом: " . $conn->connect_error);
}
?>
