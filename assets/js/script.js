document.addEventListener('DOMContentLoaded', () => {//ovo da dok se ne ucitaju svi elemnti ne krene js da radi
  //pravimo reference na html elemente
  const vicDiv = document.getElementById('vic');//div gde se prikazuje vic
  const kategorijaSelect = document.getElementById('kategorija');//padajuca lista za izbot iz kategorije
  const noviVicBtn = document.getElementById('noviVic');//dugme za ucitavanje novog vica
  const ocenaDiv = document.getElementById('ocena');//sistem za ocenjivanje
  const starsDiv = document.getElementById('stars');//div gde ce se prikazati zvezdice
  const prosecnaOcenaDiv = document.getElementById('prosecnaOcena');//div gde se prikazuje prosecna ocena
  const komentariDiv = document.getElementById('komentariDiv');//div za prikaz komentara i forme komentara
  const komentariLista = document.getElementById('komentariLista');//mesto gde se prikazuju kometari
  const commentForm = document.getElementById('commentForm');//forma za slanje komentara
  const korisnikInput = document.getElementById('korisnik');//input polje za ime ko komentarise
  const komentarInput = document.getElementById('komentar');//teksutalno polje za komentar
  const toggleFavoriteBtn = document.getElementById('toggleFavorite');//dugme za dodavanje/uklanjanje omiljeni vic
  const statistikaDiv = document.getElementById('statistika');//div za prikaz statistike
  const toggleThemeBtn = document.getElementById('toggleTheme');//dugme za prebacivanje teme
  //promenljive za cuvanje stanja aplikacije
  let currentVicId = null;  //id trenutno prikazanog vica
  let currentRating = 0; //trenutna ocena korisnika 
  let isFavorite = false; //da li je omiljeni ili ne

  // klasa koja ce se dodati na body za tamnu temu
  const darkClass = 'dark-theme';
  // proveravam local storidz da li imamo dark temu
  if(localStorage.getItem('theme') === 'dark') {
    document.body.classList.add(darkClass); //dodajemo klasu na body 
    toggleThemeBtn.textContent = 'Светла тема'; //menjamo tekst dugmeta
  }
  //dodajemo listener za klik na dugme na promenu teme
  toggleThemeBtn.addEventListener('click', () => {
    if(document.body.classList.contains(darkClass))  { //ako je trenutno aktivna tamna tema
      document.body.classList.remove(darkClass);  //ukloni temu
      toggleThemeBtn.textContent = 'Тамна тема';  //promeni tekst
      localStorage.setItem('theme', 'light'); //snimimo da je svetla
    } else {
      document.body.classList.add(darkClass); //ako nije aktivna dodajemo dark klasu u body
      toggleThemeBtn.textContent = 'Светла тема'; //menjamo tekst dugmeta
      localStorage.setItem('theme', 'dark');  //snimamo da je tamna tema
    }
  });

  // ucitavanje vica sa servera, get-joke.php
  function loadJoke() {
    const kategorija = kategorijaSelect.value;  //uzimamo trenutno izabranu kategoriju iz menija
    fetch(`get-joke.php?kategorija_id=${kategorija}`) //poziv api preko fetcha da zatrazi vic za tu kategoriju
      .then(res => res.json())  //kada stigne odgovor pretvaramo ga u json
      .then(data => {
        if(data.success) {  //ako je uspesno, cuvamo id i prikazemo tekst vica na stranici
          currentVicId = data.vic.id;
          vicDiv.textContent = data.vic.tekst;
          ocenaDiv.style.display = 'block';   //prikazujemo ocenjivanje i komentare
          komentariDiv.style.display = 'block';
          updateStars(0);   //resetujemo prikaz zvezdica na nula
          loadRating(currentVicId);   //ucitavamo prosecnu ocenu
          loadComments(currentVicId); //ucitavamo komentar za vic
          checkFavorite(currentVicId);  //proverava da li je omiljeni
          loadStats();  //ucitavamo statistiku
        } else {  //ako nema viceva za kategoriju, prikazujemo poruku
          vicDiv.textContent = 'Нема вицева за изабрану категорију.';
          ocenaDiv.style.display = 'none';
          komentariDiv.style.display = 'none';
          statistikaDiv.textContent = '';
          currentVicId = null;
        }
      })  
      .catch(err => { //ako postoji greska pri ucitavanju prikazujemo poruku o gresci
        vicDiv.textContent = 'Грешка при учитавању вицева.';
        ocenaDiv.style.display = 'none';
        komentariDiv.style.display = 'none';
        statistikaDiv.textContent = '';
        currentVicId = null;
      });
  }

  // funkcija za crtanje zvezdica i ocenjivanje
  function updateStars(rating) {
    starsDiv.innerHTML = '';  //prvo ocistimo prethodne zvezdice
    for(let i=1; i<=5; i++) {
      const star = document.createElement('span');  //napravimo span element za svaku zvezdicu
      star.textContent = i <= rating ? '★' : '☆'; //popunjena ili prazna zvezdica
      star.style.cursor = 'pointer';  //promeni krusor na ruku kad predje preko zvezdica
      star.style.fontSize = '1.5rem'; //velicina zvezdice
      star.style.color = i <= rating ? '#ffc107' : '#ccc';    //zuta popunjena , siva prazna
      star.dataset.value = i; //cuvamo broj zvezdice
      star.addEventListener('click', () => {  //klik na svaku zvezdicu moze da oceni vic
        submitRating(i); //funkcija za slanje ocene na server
      });
      starsDiv.appendChild(star);//dodajemo zvezdice u div
    }
  }

  // funkcija za slanje ocene na server
  function submitRating(rating) {
    if(!currentVicId) return; //ako nema aktivnog vica nista se ne desava
    fetch('rate-joke.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({vic_id: currentVicId, ocena: rating}) //saljemo json objekat sa id vica i ocenom
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        updateStars(rating);  //azuriramo prikaz zvezdica prea novoj oceni
        loadRating(currentVicId); //ponovo ucitavamo prosecnu ocenu sa servera
        loadStats();  //ucitavamo statistiku
      } else {
        alert('Није могуће послати оцену.');
      }
    })
    .catch(() => alert('Грешка при слању оцене.'));
  }

  // funkcija za ucitavanje prosecne ocene i broja glasova za dati vic
  function loadRating(vicId) {
    fetch(`rate-joke.php?vic_id=${vicId}`)
      .then(res => res.json())
      .then(data => {
        if(data.success) {
          prosecnaOcenaDiv.textContent = `Просечна оцена: ${data.prosek} (${data.broj} гласа)`;
        } else {
          prosecnaOcenaDiv.textContent = '';
        }
      });
  }

  // funkcija za ucitavanje komentara za dati vic
  function loadComments(vicId) {
    fetch(`comment.php?vic_id=${vicId}`)
      .then(res => res.json())
      .then(data => {
        komentariLista.innerHTML = ''; //praznimo listu komentara
        if(data.success && data.komentari.length > 0) { //ako ima komentara, prolazimo kroz njih i prikazujemo ih
          data.komentari.forEach(k => {
            const div = document.createElement('div');
            div.classList.add('mb-2'); //ubacujemo ime, vreme i tekst komentara
            div.innerHTML = `<strong>${escapeHtml(k.korisnik)}</strong> <small class="text-muted">${k.vreme}</small><br>${escapeHtml(k.komentar)}`;
            komentariLista.appendChild(div);
          });
        } else {
          komentariLista.textContent = 'Нема коментара.';
        }
      });
  }

  // kada korisnik posalje komentar
  commentForm.addEventListener('submit', e => {
    e.preventDefault();
    if(!currentVicId) return alert('Учитај виц прво.');
    const korisnik = korisnikInput.value.trim() || 'Анонимни';
    const komentar = komentarInput.value.trim();
    if(komentar.length === 0) return alert('Коментар не може бити празан.');
    //slanje komentara na server
    fetch('comment.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({vic_id: currentVicId, korisnik, komentar})
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        komentarInput.value = '';
        korisnikInput.value = '';
        loadComments(currentVicId);
      } else {
        alert('Није могуће послати коментар.');
      }
    })
    .catch(() => alert('Грешка при слању коментара.'));
  });

  // bezbednost
  function escapeHtml(text) {
    return text.replace(/[&<>"']/g, function(m) {
      return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;'}[m];
    });
  }

  //za omiljeni vic
  toggleFavoriteBtn.addEventListener('click', () => {
    if(!currentVicId) return alert('Учитај виц прво.');
    fetch('favorite.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({vic_id: currentVicId})
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        isFavorite = data.is_favorite;
        toggleFavoriteBtn.textContent = isFavorite ? 'Уклони из омиљених' : 'Додај у омиљене';
        loadStats();
      } else {
        alert('Грешка при ажурирању омиљених.');
      }
    })
    .catch(() => alert('Грешка при ажурирању омиљених.'));
  });

  // provera da li je omiljen vic
  function checkFavorite(vicId) {
    fetch(`favorite.php?vic_id=${vicId}`)
      .then(res => res.json())
      .then(data => {
        isFavorite = data.is_favorite || false;
        toggleFavoriteBtn.textContent = isFavorite ? 'Уклони из омиљених' : 'Додај у омиљене';
      });
  }

  // funkcija za statistiku
  function loadStats() {
    fetch('stats.php')
      .then(res => res.json())
      .then(data => {
        if(data.success) {
          statistikaDiv.innerHTML = `
            Укупно вицева: ${data.ukupno} |
            Оцењених: ${data.ocenjenih} |
            Омљених: ${data.omiljenih}
          `;
        } else {
          statistikaDiv.textContent = '';
        }
      });
  }

  // dugmad
  noviVicBtn.addEventListener('click', loadJoke);
  kategorijaSelect.addEventListener('change', loadJoke);

  //ucitavanje
  loadJoke();
});
