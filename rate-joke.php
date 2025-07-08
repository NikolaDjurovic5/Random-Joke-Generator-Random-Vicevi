<?php
session_start();//pokrecemo sesiju
header('Content-Type: application/json');//ocekuje odgovor u json formatu
include 'includes/db.php';//povezujemo bazu

//dobijamo session id za anonimnog korisnika
$session_id = session_id();
//da li je koriscena metoda post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //prijem ocene, dekdira json u php niz, uzima vic id i ocena
    $data = json_decode(file_get_contents('php://input'), true);
    $vic_id = isset($data['vic_id']) ? (int)$data['vic_id'] : 0;
    $ocena = isset($data['ocena']) ? (int)$data['ocena'] : 0;
    //proverava da li su podaci validni
    if ($vic_id > 0 && $ocena >= 1 && $ocena <= 5) {
        //proveravam da li je korisnik ocenjivao vec ovaj vic
        $stmt = $conn->prepare("SELECT id FROM ocene WHERE vic_id = ? AND sesija_id = ?");
        $stmt->bind_param("is", $vic_id, $session_id);
        $stmt->execute();
        $stmt->store_result();
        //ako je vec glasao
        if ($stmt->num_rows > 0) {
            // updejtuj ocenu
            $stmt->close();
            $stmt = $conn->prepare("UPDATE ocene SET ocena = ?, vreme = NOW() WHERE vic_id = ? AND sesija_id = ?");
            $stmt->bind_param("iis", $ocena, $vic_id, $session_id);
            $stmt->execute();
            $stmt->close();
        } else { //ako nije jos glasao
            //novi unos
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO ocene (vic_id, ocena, sesija_id) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $vic_id, $ocena, $session_id);
            $stmt->execute();
            $stmt->close();
        }

        echo json_encode(['success' => true]); //vraca uspescan json odgovor
    } else {
        echo json_encode(['success' => false, 'message' => 'Погрешни параметри.']);//podaci nisu validni
    }
} else {
    //ako nije post, prepostavim da je get
    $vic_id = isset($_GET['vic_id']) ? (int)$_GET['vic_id'] : 0;
    if ($vic_id > 0) {//ako je prosledjen validan id
        $stmt = $conn->prepare("SELECT COUNT(*) as broj, AVG(ocena) as prosek FROM ocene WHERE vic_id = ?");
        $stmt->bind_param("i", $vic_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        $stmt->close();
        //ako postoji bar jedna ocena
        if ($data['broj'] > 0) {
            echo json_encode([
                'success' => true,
                'broj' => (int)$data['broj'],
                'prosek' => number_format($data['prosek'], 2)
            ]);
        } else {//ako nema ocena vraca 0
            echo json_encode(['success' => true, 'broj' => 0, 'prosek' => 0]);
        }
    } else {//ako nije prosledjen vic id
        echo json_encode(['success' => false, 'message' => 'Није послат ид вицa.']);
    }
}

$conn->close(); //zatvaram konekciju sa bazom
