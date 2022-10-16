<?php
$user = 'root';
$pwd = 'password';

$pdo = new PDO('mysql:host=db', $user, $pwd);

// create the database
$createDB = $pdo->prepare('CREATE DATABASE IF NOT EXISTS blog_docker CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;');
$createDB->execute();
$createDB->closeCursor();

$pdo = new PDO('mysql:host=db:3306;dbname=blog_docker', $user, $pwd);

$createUserTable = $pdo->prepare('
CREATE TABLE IF NOT EXISTS users (
  userId INT AUTO_INCREMENT,
  userName VARCHAR(255) NOT NULL,
  userPassword VARCHAR(255) NOT NULL,
  userAdmin VARCHAR(255),
  PRIMARY KEY (userId)
  ) ENGINE=InnoDB;');
$createUserTable->execute();
$createUserTable->closeCursor();

$createArticleTable = $pdo->prepare('
CREATE TABLE IF NOT EXISTS articles (
  articleId INT AUTO_INCREMENT,
  user INT NOT NULL,
  content VARCHAR(255),
  PRIMARY KEY (articleId),
  FOREIGN KEY (user) REFERENCES users (userId)
  ) ENGINE=InnoDB;');
$createArticleTable->execute();
$createArticleTable->closeCursor();

$title = 'Database';
$css = '../css/initdb.css';
require_once '../templates/header.php';
?>

<div class='box'>
  <span class='box_icon'>🎉</span>
  <span class='box_text'>La base de données a bien été installée.</span>
</div>

<?php require_once '../templates/footer.php' ?>