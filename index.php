<?php
session_start();//pokrecemo sesiju
include 'includes/db.php'; //povezujemo bazu

//kreiramo sesijski id ako ne postoji za anonimne korisnike
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = bin2hex(random_bytes(16));
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <title>Joke Generator PRO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="assets/js/script.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">

    <h1 class="text-center mb-4">Генератор Вицева --- Radnom Joke Generator</h1>

    <!--tema -->
    <div class="mb-3 text-end">
        <button id="toggleTheme" type="button" class="btn btn-secondary btn-sm">Тамна тема</button>
    </div>

    <!--izbor kategorije -->
    <div class="mb-3">
        <label for="kategorija" class="form-label">Изабери категорију:</label>
        <select id="kategorija" class="form-select">
            <option value="0" selected>Све категорије</option>
            <?php
            $res = $conn->query("SELECT * FROM kategorije");
            while ($cat = $res->fetch_assoc()) {
                echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['naziv']) . "</option>";
            }
            ?>
        </select>
    </div>

    <!--prikaz viceva-->
    <div id="vic" class="p-4 bg-white rounded shadow-sm mb-3" style="min-height:120px; font-size:1.25rem;">
        Кликни на дугме да видиш виц!
    </div>

    <!--ocena-->
    <div id="ocena" class="mb-3" style="display:none;">
        <label>Оцени виц:</label>
        <div id="stars" class="star-rating">
            <!-- Звездице ће се овде динамички приказивати -->
        </div>
        <div id="prosecnaOcena" class="mt-2 text-muted"></div>
    </div>

    <!--komentari-->
    <div id="komentariDiv" style="display:none;">
        <h5>Коментари</h5>
        <form id="commentForm" class="mb-3">
            <input type="text" id="korisnik" class="form-control mb-2" placeholder="Твоје име (опционо)" maxlength="50" />
            <textarea id="komentar" class="form-control mb-2" placeholder="Напиши коментар" required></textarea>
            <button type="submit" class="btn btn-primary btn-sm">Пошаљи коментар</button>
        </form>
        <div id="komentariLista" class="mb-4" style="max-height:200px; overflow-y:auto; border:1px solid #ccc; padding:10px; border-radius:5px;"></div>
    </div>

    <!--dugme za novi vic-->
    <button id="noviVic" class="btn btn-primary">Нови виц</button>

    <!--omiljeni i statistika -->
    <div class="mt-4 d-flex justify-content-between">
        <button id="toggleFavorite" class="btn btn-outline-warning">Додај у омиљене</button>
        <div id="statistika" class="text-muted"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
