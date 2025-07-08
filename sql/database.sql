CREATE DATABASE IF NOT EXISTS vic_generator;
USE vic_generator;

DROP TABLE IF EXISTS favorite_vicevi;
DROP TABLE IF EXISTS komentari;
DROP TABLE IF EXISTS ocene;
DROP TABLE IF EXISTS vicevi;
DROP TABLE IF EXISTS kategorije;

CREATE TABLE kategorije (
  id INT AUTO_INCREMENT PRIMARY KEY,
  naziv VARCHAR(50) NOT NULL
);

CREATE TABLE vicevi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tekst TEXT NOT NULL,
  kategorija_id INT,
  FOREIGN KEY (kategorija_id) REFERENCES kategorije(id) ON DELETE SET NULL
);

CREATE TABLE ocene (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vic_id INT,
  ocena TINYINT NOT NULL CHECK (ocena BETWEEN 1 AND 5),
  sesija_id VARCHAR(50),
  vreme DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (vic_id) REFERENCES vicevi(id) ON DELETE CASCADE
);

CREATE TABLE komentari (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vic_id INT,
  korisnik VARCHAR(50) DEFAULT 'Анонимни',
  komentar TEXT NOT NULL,
  vreme DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (vic_id) REFERENCES vicevi(id) ON DELETE CASCADE
);

CREATE TABLE favorite_vicevi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vic_id INT,
  sesija_id VARCHAR(50),
  vreme DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (vic_id) REFERENCES vicevi(id) ON DELETE CASCADE
);

INSERT INTO kategorije (naziv) VALUES
('Смешни'),
('Црни хумор'),
('Технолошки');

INSERT INTO vicevi (tekst, kategorija_id) VALUES
-- smesni (10)
('Зашто је пиле прешло улицу?\nДа стигне на другу страну.', 1),
('Како се зове мачка која ради у IT-ју?\nКод-ека.', 1),
('Шта ради риба у пеку?\nПлива у маслу.', 1),
('Зашто је компјутер хладан?\nЈер има вентилатор.', 1),
('Како се зове човек који много прича?\nПричалица.', 1),
('Шта каже пас када види лопту?\nХајде да се играмо!', 1),
('Зашто је мачка добра у математици?\nЈер рачуна сваку мишицу.', 1),
('Како се зове човек који воли спорт?\nСпортски фан.', 1),
('Шта ради петао ујутро?\nКукурикује.', 1),
('Зашто је сунце важно?\nЈер даје светлост.', 1),

-- crni humor (10)
('Живот је као сендвич.\nНајвише боли када изгубиш филове.', 2),
('Нисам се плашио смрти, али сам се плашио да нећу имати интернет.', 2),
('Питају мртваца: Како си?\nНема лоше, само сам мало хладно.', 2),
('Зашто духови не иду у школу?\nЈер су већ испунили све празнине.', 2),
('Када је човек најјачи?\nКада се сруши.', 2),
('Зашто зомби не иду код доктора?\nЈер им ионако мозак не функционише.', 2),
('Живот је као лампа - понекад те упали, понекад те угаси.', 2),
('Смрт није крај, већ смањење стручности.', 2),
('За шта служи лобања?\nЗа чувар мозга.', 2),
('Живот је као погрешна линија кода - стално баца грешке.', 2),

-- tehnicki (10)
('Како програмер броји до 10?\n0, 1, 10, 11, 100...', 3),
('Зашто програмери воле тамну тему?\nЈер је лакше читати када је живот таман.', 3),
('Шта каже компјутер када се замрзне?\n“Морам да рестартујем свој живот.”', 3),
('Зашто програмери не излазе напоље?\nЈер се боје бага у природи.', 3),
('Како се зове програмер који не спава?\nДевелопер који дебагује.', 3),
('Зашто програмери не користе сат?\nЈер рачунају време у милисекундама.', 3),
('Шта ради програмер кад му нешто не ради?\nПроба да га искључи и укључи.', 3),
('Зашто је код писање као уметност?\nЈер захтева инспирацију и стрпљење.', 3),
('Како зову програмера који воли кафу?\nJava дев.', 3),
('Шта је горе од багова?\nДокументован баг.', 3);
