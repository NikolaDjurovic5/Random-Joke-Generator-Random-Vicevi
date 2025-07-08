<?php
session_start(); //startujemo sesiju
header('Content-Type: application/json'); // odgovor ce biti u json formatu
include 'includes/db.php';// povezujem bazu

$session_id = session_id(); //dobija jedinstveni id trenutne sesije

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //proveravam da li je zahtev post
    $data = json_decode(file_get_contents('php://input'), true); //ucitava json podatke i pretvara ih u php niz
    $vic_id = isset($data['vic_id']) ? (int)$data['vic_id'] : 0; //uzima vrednost vica id
    //ako je vic validan, tj ima nesto
    if ($vic_id > 0) {
        //proverava da li je vec u omiljenima
        $stmt = $conn->prepare("SELECT id FROM favorite_vicevi WHERE vic_id = ? AND sesija_id = ?");
        $stmt->bind_param("is", $vic_id, $session_id);  //povezuje parametre sa upitom
        $stmt->execute();   //izvrsava upit
        $stmt->store_result();  //cuva rezultatupita za daje obrade
        //ako je vic pronadjen u tabeli
        if ($stmt->num_rows > 0) {
            //ukloni iz omiljenih
            $stmt->close();
            $stmt = $conn->prepare("DELETE FROM favorite_vicevi WHERE vic_id = ? AND sesija_id = ?");
            $stmt->bind_param("is", $vic_id, $session_id);
            $stmt->execute(); //izvrsava brisanje
            echo json_encode(['success' => true, 'is_favorite' => false]); //vraca json odgovor da je vic uspeno uklonjen
        } else {    //ako nije pronadje u omiljenima
            // dodaj ga u omiljene
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO favorite_vicevi (vic_id, sesija_id) VALUES (?, ?)");
            $stmt->bind_param("is", $vic_id, $session_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'is_favorite' => true]); //vraca json odgovor da je vic uspesno dodat
        }
        $stmt->close();
    } else {    //ako vic nije validan
        echo json_encode(['success' => false, 'message' => 'Није послат ид вицa.']);
    }
} else {
    //ako zahtev nije post, onda ocekujem da je get, i kupim ga iz linka
    $vic_id = isset($_GET['vic_id']) ? (int)$_GET['vic_id'] : 0;
    //da li je vic validan
    if ($vic_id > 0) {
        $stmt = $conn->prepare("SELECT id FROM favorite_vicevi WHERE vic_id = ? AND sesija_id = ?");
        $stmt->bind_param("is", $vic_id, $session_id);
        $stmt->execute();
        $stmt->store_result();  //cuva rezultat da proverio broj redova

        echo json_encode(['success' => true, 'is_favorite' => $stmt->num_rows > 0]); //vraca json da li je vic favorite pronadjen
        $stmt->close();
    } else { //vraca gresku ako nije poslat ili nije validan vic
        echo json_encode(['success' => false, 'message' => 'Није послат ид вицa.']);
    }
}

$conn->close();
