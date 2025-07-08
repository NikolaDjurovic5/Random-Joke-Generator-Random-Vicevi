<?php
session_start(); //startujemo sesiju
header('Content-Type: application/json'); //odgovri koji saljem bice u json formatu
include 'includes/db.php';  //ukljucujemo fal db.php koji povezuje sa bazom

if ($_SERVER['REQUEST_METHOD'] === 'POST') {//proveravamo da li je metod koriscenja post
    $data = json_decode(file_get_contents('php://input'), true);    //citamo json podatke koje je fetch poslao iz js, pretvaram ga u php niz
    $vic_id = isset($data['vic_id']) ? (int)$data['vic_id'] : 0; //uzimamo podatke id vica, ime korisnika, i komentar
    $korisnik = isset($data['korisnik']) && trim($data['korisnik']) !== '' ? trim($data['korisnik']) : 'Анонимни';
    $komentar = isset($data['komentar']) ? trim($data['komentar']) : '';
    //provera dali su id i komentar validini, da ima nesto nisu prazni
    if ($vic_id > 0 && strlen($komentar) > 0) {
        $stmt = $conn->prepare("INSERT INTO komentari (vic_id, korisnik, komentar) VALUES (?, ?, ?)"); //priprema za sql upit
        $stmt->bind_param("iss", $vic_id, $korisnik, $komentar);
        if ($stmt->execute()) { //ako uspe vracamo true, ako ne gresku
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Грешка при додавању коментара.']);
        }
        $stmt->close(); //zatvara pripremljenu izjavu
    } else { //ako nije dobar vic_id ili prazan komentar
        echo json_encode(['success' => false, 'message' => 'Погрешни параметри.']);
    }
} else {
    //ako metod nije post, ocekujemo da je get, i onda se salje preko urla
    $vic_id = isset($_GET['vic_id']) ? (int)$_GET['vic_id'] : 0;
    if ($vic_id > 0) {//ako je id validan pripremamo upit
        $stmt = $conn->prepare("SELECT korisnik, komentar, DATE_FORMAT(vreme, '%d.%m.%Y %H:%i') as vreme FROM komentari WHERE vic_id = ? ORDER BY vreme DESC");
        $stmt->bind_param("i", $vic_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $komentari = [];
        while ($row = $res->fetch_assoc()) {//prolazimo kroz svaki red rezultata i dodajemo u niz komentara
            $komentari[] = $row;
        }
        $stmt->close();//zatvaramo upit i vracamo komentari kao json
        echo json_encode(['success' => true, 'komentari' => $komentari]);
    } else {//ako nije poslat vic id javljam gresku
        echo json_encode(['success' => false, 'message' => 'Није послат ид вицa.']);
    }
}

$conn->close();
