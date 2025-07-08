
# 🎭 Генератор Вицева PRO

**Генератор Вицева PRO** је веб апликација која приказује случајне вицеве из базе података, омогућава корисницима да их оцењују, коментаришу, чувају као омиљене и прате статистику. Подржава и прелазак између тамне и светле теме ради бољег корисничког искуства.

## 🚀 Функционалности

- ✅ Приказ случајних вицева по категоријама (смешни, црни хумор, итд.)
- ⭐ Оцењивање вицева (1–5 звездица)
- 💬 Коментарисање уз могућност анонимности
- 📌 Додавање у омиљене
- 📊 Приказ основне статистике (укупан број вицева, оцењених и омиљених)
- 🌙 Светла / тамна тема (са чувањем избора)
- 🎯 Фронтенд и бекенд повезани преко API захтева

## ⚙️ Технологије

- **Frontend:** HTML, CSS, JavaScript, Bootstrap
- **Backend:** PHP, MySQL
- **База:** MySQL база са табелама за вицеве, категорије, оцене, коментаре и омиљене

## 🛠️ Постављање локално

1. Клонирај репозиторијум:
    ```
    git clone https://github.com/NikolaDjurovic5/random_joke_generator.git
    ```

2. Постави XAMPP или WAMP и покрени Apache и MySQL.

3. У `phpMyAdmin` направи базу са именом `vic_generator` и покрени SQL скрипту из `sql/` фолдера.

4. Уреди `includes/db.php` ако је потребно (подешавања базе).

5. Отвори апликацију у прегледачу:
    ```
    http://localhost/random_joke_generator/index.php
    ```

## 📁 Структура

```
random_joke_generator/
│
├── assets/
│   ├── css/style.css
│   └── js/script.js
│
├── includes/
│   └── db.php
│
├── sql/
│   └── vic_generator.sql
│
├── index.php
├── get-joke.php
├── rate-joke.php
├── comment.php
├── favorite.php
├── stats.php
└── README.md
```

## 📌 Аутор

- Никола Ђуровић  


---

## 🌍 English Version

# 🎭 Joke Generator PRO

**Joke Generator PRO** is a web application that displays random jokes from a database, allows users to rate them, comment, save favorites, and view statistics. It also supports dark/light theme switching for a better user experience.

## 🚀 Features

- ✅ Show random jokes by category (funny, dark humor, etc.)
- ⭐ Rate jokes (1–5 stars)
- 💬 Comment system (anonymous supported)
- 📌 Add to favorites
- 📊 Show statistics (total jokes, rated, favorites)
- 🌙 Dark/Light mode (with saved preference)
- 🎯 Frontend and backend communication via API calls

## ⚙️ Technologies

- **Frontend:** HTML, CSS, JavaScript, Bootstrap  
- **Backend:** PHP , MySQL  
- **Database:** MySQL with tables for jokes, categories, ratings, comments, and favorites

## 🛠️ Local Setup

1. Clone the repository:
    ```
    git clone https://github.com/NikolaDjurovic5/random_joke_generator.git
    ```

2. Install and start XAMPP or WAMP (Apache + MySQL)

3. Create a database `vic_generator` in `phpMyAdmin` and import the SQL file from the `sql/` folder.

4. Edit `includes/db.php` if needed (database credentials).

5. Open in browser:
    ```
    http://localhost/random_joke_generator/index.php
    ```

## 📁 Folder Structure

```
random_joke_generator/
│
├── assets/
│   ├── css/style.css
│   └── js/script.js
│
├── includes/
│   └── db.php
│
├── sql/
│   └── vic_generator.sql
│
├── index.php
├── get-joke.php
├── rate-joke.php
├── comment.php
├── favorite.php
├── stats.php
└── README.md
```

## 📌 Author

- Nikola Djurović  