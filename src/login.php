<?php

session_start();

if(isset($_SESSION['USER'])) {
  header('Location: ../index.php');
  exit;
}

// we check that the fields exist and that they are correctly filled in
if(!empty($_POST)) {
  
  if(!empty($_POST['username']) && !empty($_POST['password']))
  {
    $name = strip_tags($_POST['username']);
    $pwd = password_hash($_POST['password'], PASSWORD_ARGON2ID);

    require_once '../database/pdo.php';

    $search = 'SELECT * FROM users WHERE userName = :name';
    $query = $pdo->prepare($search);
    $query->bindValue(':name', $name, PDO::PARAM_STR);
    $query->execute();

    $user = $query->fetch();

    if(!$user) {
      die('Utilisateur existe pas');
    }

    if(!password_verify($_POST['password'], $user['userPassword'])) {
      die('mot de passe incorect');
    }

    $_SESSION['user'] = [
      'id' => $user['userId'],
      'name' => $user['userName'],
      'admin' => $user['userAdmin']
    ];

    header('Location: ./feed.php');

  } else {
    die('Le formulaire est incomplet');
  }
}

$title = 'Connection';
$css = '../css/index.css';
require_once '../templates/header.php'
?>

<div class='box'>

<h1>Connection</h1>

<form name='signIn' method='post'>
<div class='form__group field'>
        <input required='' placeholder='Nom' name='username' class='form__field' type='text'>
        <label class='form__label' for='name'>Nom</label>
    </div>

    <div class='form__group field'>
        <input required='' placeholder='Mot de passe' name='password' class='form__field' type='password'>
        <label class='form__label' for='name'>Mot de passe</label>
    </div>

    <button class='light' type='submit'>Se connecter</button>
</form>
<a href='../index.php'><button class='dark'>Inscription</button></a>

</div>