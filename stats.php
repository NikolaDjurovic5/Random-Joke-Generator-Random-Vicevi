<?php
session_start();//pokrece sesiju
header('Content-Type: application/json');//odgovor servera u json formatu
include 'includes/db.php';//povezujemo sa bazom
//uzimam trenutni id
$session_id = session_id();
//kreiram prazan niz
$data = [];

//ukupno viceva, cuva u dataka ukupno kao ceo broj
$res = $conn->query("SELECT COUNT(*) as ukupno FROM vicevi");
$row = $res->fetch_assoc();
$data['ukupno'] = (int)$row['ukupno'];

//broj ocena, vicevi koji imaju ocenu, cuva se u data ocenjenih
$res = $conn->query("SELECT COUNT(DISTINCT vic_id) as ocenjenih FROM ocene");
$row = $res->fetch_assoc();
$data['ocenjenih'] = (int)$row['ocenjenih'];

//broj omiljenih za ovu sesiju
$stmt = $conn->prepare("SELECT COUNT(*) as omiljenih FROM favorite_vicevi WHERE sesija_id = ?");
$stmt->bind_param("s", $session_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();//uzima red rezultata ako asocijativni niz
$data['omiljenih'] = (int)$row['omiljenih'];
$stmt->close();

echo json_encode(['success' => true] + $data); // vraca json odgovor success = true i ukupan broj viceva, ocenjenih i omiljenih

$conn->close(); //zatvara koneciju sa bazom
