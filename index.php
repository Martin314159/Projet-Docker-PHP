<?php

session_start();

if(isset($_SESSION['USER'])) {
  header('Location: ./src/feed.php');
  exit;
}

// we check that the fields exist and that they are correctly filled in
if(!empty($_POST)) {
  
  if(!empty($_POST['username']) && !empty($_POST['password']))
  {
    $name = strip_tags($_POST['username']);
    $adm = strip_tags($_POST['admin']);
    if(!isset($adm)) {
      $admin = 'user';
    } else {
      $admin = strip_tags($_POST['admin']);
    }
    $pwd = password_hash($_POST['password'], PASSWORD_ARGON2ID);

    require_once './database/pdo.php';

    $check = $pdo->prepare('SELECT * FROM users WHERE userName = :name');
    $check->bindValue(':name', $name, PDO::PARAM_STR);
    $check->execute();

    $data = $check->fetch();
    $row = $check->rowCount();

    if($row == 0) {
      $add = 'INSERT INTO users (userName, userPassword, userAdmin) VALUES (:name, :pwd, :admin)';
      $query = $pdo->prepare($add);
      $query->bindValue(':name', $name, PDO::PARAM_STR);
      $query->bindValue(':pwd', $pwd, PDO::PARAM_STR);
      $query->bindValue(':admin', $admin, PDO::PARAM_STR);
      $query->execute();

      $id = $pdo->lastInsertId();

      $_SESSION['user'] = [
        'id' => $id,
        'name' => $name,
        'admin' => $admin
      ];

      header('Location: ./src/feed.php');
    } else {
      die('Utilisateur déjà éxistant');
    }

  } else {
    die('Le formulaire est incomplet');
  }
}

$title = 'Inscription';
$css = './css/index.css';
require_once './templates/header.php'
?>

<div class='box'>

<h1>Inscription</h1>

<form method='post'>

    <div class='form__group field'>
        <input required='' placeholder='Nom' name='username' class='form__field' type='text'>
        <label class='form__label' for='name'>Nom</label>
    </div>

    <div class='form__group field'>
        <input required='' placeholder='Mot de passe' name='password' class='form__field' type='password'>
        <label class='form__label' for='name'>Mot de passe</label>
    </div>

    <div class='switch-container'>
      <label for='admin' class='switch-text'>Admin</label>
      <label class='switch'>
        <input type='checkbox' name='admin' value='admin'>
        <span class='slider'></span>
      </label>
    </div>

    <button class='light' type='submit'>S'inscrire</button>
  </form>
  
  <a href='src/login.php'><button class='dark'>Connexion</button></a>

</div>