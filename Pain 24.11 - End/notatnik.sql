/* #1
Baza danych "szkoła"
student (id,name,surname)
klasa (id,name)
teacher (id,name,surname,age)
subject (id,name)

id - auto increment

relacje:
wiele uczniow w jednej klasie
klasa ma wiele przedmiotów
jeden nauczyciel uczy jednego przedmiotu
*/

/* #2
Dodać użytkownikowi (Imie, Nazwisko, Wiek)
Użytkownik może edytować swoje dane
Migracja + uzupełnianie pustych kolumn
*/

/* #3
Admin może widzieć użytkowników (bez hasła)

*/

DROP DATABASE Oleksiewicz_Szkola;
CREATE DATABASE Oleksiewicz_Szkola;

USE Oleksiewicz_Szkola;
CREATE TABLE userdata (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username varchar(50) NOT NULL,
	password varchar(50) NOT NULL
);
ALTER TABLE userdata
	ADD name varchar(30) NOT NULL DEFAULT 'Janusz',
	ADD surname varchar(50) NOT NULL DEFAULT 'Kowalski',
	ADD age int(2) NOT NULL DEFAULT '0',
	ADD admin BOOLEAN NOT NULL DEFAULT '0';

CREATE TABLE student (
	id int AUTO_INCREMENT PRIMARY KEY,
	name varchar(20) NOT NULL,
	surname varchar(30) NOT NULL,
	class_id int NOT NULL,
    added_by int NOT NULL,
    FOREIGN KEY (added_by) REFERENCES userdata(id)
);

CREATE TABLE class (
	id int AUTO_INCREMENT PRIMARY KEY,
	name varchar(20) NOT NULL,
    added_by int NOT NULL,
    FOREIGN KEY (added_by) REFERENCES userdata(id)
);

CREATE TABLE teacher (
	id int PRIMARY KEY AUTO_INCREMENT,
	name varchar(20) NOT NULL,
	surname varchar(30) NOT NULL,
	age int(2) NOT NULL,
    added_by int NOT NULL,
    FOREIGN KEY (added_by) REFERENCES userdata(id)
);

CREATE TABLE subject (
	id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(20) NOT NULL,
	class_id int NOT NULL,
    added_by int NOT NULL,
    FOREIGN KEY (added_by) REFERENCES userdata(id)
);


ALTER TABLE student ADD CONSTRAINT student_class FOREIGN KEY (class_id) REFERENCES class(id);
ALTER TABLE subject ADD CONSTRAINT class_subject FOREIGN KEY (class_id) REFERENCES class(id);
ALTER TABLE teacher ADD CONSTRAINT teacher_subject FOREIGN KEY (id) REFERENCES subject(id);
/*
INSERT INTO class (name) VALUES ('3bTE');
INSERT INTO student (name, surname, class_id) VALUES ('Mateusz', 'Kobźdzej', 1);
INSERT INTO student (name, surname, class_id) VALUES ('Teresa', 'Fraś', 1);
INSERT INTO class (name) VALUES ('3bT5');
INSERT INTO student (name, surname, class_id) VALUES ('Anna', 'Papajov', 2);
INSERT INTO student (name, surname, class_id) VALUES ('Tomasz', 'Mad', 2);
INSERT INTO student (name, surname, class_id) VALUES ('Zbigniew', 'Działka', 2);
INSERT INTO subject (name, class_id) VALUES ('Matematyka', 1);
INSERT INTO subject (name, class_id) VALUES ('Język polski', 1);
INSERT INTO subject (name, class_id) VALUES ('Język angielski', 2);
INSERT INTO teacher (name, surname, age) VALUES ('Sergei', 'Bomboniarov', 53);
INSERT INTO teacher (name, surname, age) VALUES ('Andżej', 'Dudu', 86);
INSERT INTO teacher (name, surname, age) VALUES ('Ronald', 'McTusk', 23);
*/
