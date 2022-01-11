<?php
require($_SERVER["DOCUMENT_ROOT"]."/lib/main.php");
?>
<html>
  <head>
      <meta charset="utf-8">
      <title>PZT: Strona Główna</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="/assets/css/reset.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="/assets/css/login.css?v=<?php echo time(); ?>">
  </head>
  <header>
    <h1><i class="fas fa-crow"></i> PZT</h1>
  </header>
  <main class="login">
    <div class="container">
      <form class="" action="">
        <h2>Zaloguj się</h2>
        <label>Email</label>
        <input type="email" name="email">
        <label>Hasło</label>
        <input type="password" name="password" value="">
        <input type="submit" value="Zaloguj się">
      <span>Nie posiadasz konta? <a href="/register.php">Zarejestruj się</a></span>
      </form>
    </div>
  </main>
<?php require($_SERVER["DOCUMENT_ROOT"]."/includes/root/footer.php"); ?>
