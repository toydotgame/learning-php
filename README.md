# XAMPP MySQL Form
A demonstration of a HTML form that submits data to a MySQL database running on XAMPP.

### Contents:
* [Requirements](#requirements)
* [Setup & Use](#setup--use)
	* [Prerequisites](#prerequisites)
	* [Download](#download)
	* [Install](#install)
	* [Database Administration](#database-administration)

***

## Requirements
### Linux:
* Apache
* MySQL Server
* HTML5-compatible browser
### Windows:
* XAMPP, running:
	* Apache
	* MySQL Server
* HTML5-compatible browser

## Setup & Use
### Prerequisites
#### Linux:
You'll need the packages for MySQL (most common client is MariaDB) and Apache (known on some platforms as httpd):
```sh
# Debian/Ubuntu and compatibles:
sudo apt install mysql-client apache2

# Arch and most other distros: (Substitue in your package manager instead)
sudo pacman -S mariadb apache
```
Start both servers:
```sh
# Debian/Ubuntu:
sudo systemctl start apache2
sudo systemctl start mysql

# Arch and others:
sudo systemctl start httpd
sudo systemctl start mariadb
```
Set up MySQL:
```sh
mysql -u root # root privileges are needed to create a new database and users.
```
```sql
CREATE DATABASE learning;
USE learning;
CREATE TABLE student_details (id text, PRIMARY KEY (id(6)), name text, surname text, cool text);

-- Password is "1337":
CREATE USER apache IDENTIFIED BY PASSWORD "*C6F8F27F2F530B7B270D641A3604424B9B404D43";
GRANT SELECT, INSERT, UPDATE ON *.* to apache;

EXIT;
```
#### Windows:
Navigate to `localhost:80/phpmyadmin`.
##### Create a new database:
* _Databases_ → _Database name_ → "learning" → _Create_
* _Create table_ → _Name:_ → "student\_details" → _Number of columns:_ → 4
* Insert the following in to the console at the bottom:
```sql
CREATE TABLE student_details (id text, PRIMARY KEY (id(6)), name text, surname text, cool text);
```
* Ctrl+Enter
##### Create a new user:
* Go to phpMyAdmin homepage
* _User accounts_ → _New_ → _Add user account_:

Field | Value
----- | -----
User name: | apache
Host name: | %
Password: | 1337
Authentication plugin: | Native MySQL Authentication
* _Global priveleges_ → _Data_ → enable _SELECT_, _INSERT_, and _UPDATE_
* _SSL_ → _REQUIRE NONE_
* _Go_

***

### Download
```sh
git clone https://github.com/toydotgame/learning-php.git; cd learning-php/
```

***

### Install
#### Linux:
```sh
# Debian/Ubuntu and compatibles:
sudo cp -rf . /var/www/html/

# Arch and other httpd versions of Apache:
sudo cp -rf . /srv/http/
```
Then open your browser to `localhost:80` to see the form page.
#### Windows:
In XAMPP's Control Panel, go to the _Apache_ Module → _Actions_ → _Config_ → _Apache (httpd.conf)_, and find `DocumentRoot`, set it to the directory that this repository was cloned to. i.e:
```
DocumentRoot "C:/Users/User/Documents/learning-php"
```
Then, navigate to `localhost:80` in your browser to see the form page.

***

### Database Administration
#### Linux:
Login to MySQL: (password is "1337")
```sh
mysql -u apache -p learning
```
Show all data in the database and/or select a single entry via `<student number>`:
```sql
SELECT * FROM student_details;
SELECT * FROM student_details WHERE id LIKE "<student number>";
```
#### Windows:
Navigate to `localhost:80/phpmyadmin` and find the _student\_details_ table (under the _learning_ database) in the left column. Click on that and view database entries on the right.
