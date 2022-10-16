<?php
session_start();

if(isset($_SESSION['USER'])) {
  header('Location: ../index.php');
  exit;
}

require_once '../database/pdo.php';

$sql = 'SELECT users.userName, articles.content FROM articles JOIN users ON articles.user = users.userId';

$request = $pdo->query($sql);

$articles = $request->fetchAll();

if(!empty($_POST)) {
  if(!empty($_POST['content']))
  {
    $content = strip_tags($_POST['content']);

    $article = 'INSERT INTO articles (user, content) VALUES (:user, :content)';
    $query = $pdo->prepare($article);
    $query->bindValue(':user', $_SESSION['user']['id'], PDO::PARAM_STR);
    $query->bindValue(':content', $content, PDO::PARAM_STR);
    $query->execute();
  }

}

$title = 'Feed';
$css = '../css/feed.css';
include_once '../templates/header.php';
?>

<h1>Bonjour <?= $_SESSION['user']['name'] ?></h1>

<?php foreach($articles as $art): ?>
  <div>
    <span><?= $art['userName'] ?></span>
    <p><?= $art['content'] ?></p>
  </div>
<?php endforeach; ?>

<form method="post">
  <textarea type="text" name="content" id=""></textarea>
  <button>envoyer</button>
</form>