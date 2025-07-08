<?php
session_start();//pokrecemo sesiju
header('Content-Type: application/json'); //ocekuje odgovor u json formatu
include 'includes/db.php'; //povezuje sa bazom

$kategorija_id = isset($_GET['kategorija_id']) ? (int)$_GET['kategorija_id'] : 0; //uzima kategorija iz geta, u suprotnom stavlja nulu
//ako je prosledjen validan id kategorije
if ($kategorija_id > 0) { //prirema upit, i vezuje kategorija_id kao ceo broj int za upit
    $stmt = $conn->prepare("SELECT * FROM vicevi WHERE kategorija_id = ? ORDER BY RAND() LIMIT 1");
    $stmt->bind_param("i", $kategorija_id); 
} else { //ako nije pronasao, uzmi nasumican viz iz svih kategorija
    $stmt = $conn->prepare("SELECT * FROM vicevi ORDER BY RAND() LIMIT 1");
}
//priprema sql upita
$stmt->execute();
$result = $stmt->get_result(); //rezultat upita
//ako je pronadjen bar jedan vic
if ($result->num_rows > 0) {
    $vic = $result->fetch_assoc(); //uzima red kao niz
    echo json_encode(['success' => true, 'vic' => $vic]); //vraca jsonsa statusom uspeha i podacima
} else {
    echo json_encode(['success' => false, 'message' => 'Нема вицева у овој категорији.']);//ako nema vica vrati gresku
}
$stmt->close();
$conn->close();
