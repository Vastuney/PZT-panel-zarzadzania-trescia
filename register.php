<?php
require $_SERVER["DOCUMENT_ROOT"]."/lib/sessions.php";
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php");
  exit;
}
?>
<html>
  <head>
      <meta charset="utf-8">
      <title>PZT: Strona Główna</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="/assets/css/reset.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="/assets/css/main.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="/assets/css/login.css?v=<?php echo time(); ?>">
  </head>
  <header>
    <h1><i class="fas fa-crow"></i> PZT</h1>
  </header>
  <main class="login">
    <div class="container">
      <form class="ajax needs-validation" method="POST">
        <h2>Zarejestruj się</h2>
        <div>
          <label>Imię</label>
          <input type="text" name="name" minlength="3" maxlength="20" required>
          <label>Nazwisko</label>
          <input type="text" name="lastname" minlength="3" maxlength="30" required>
        </div>
        <div>
        <label>Email</label>
          <input type="email" name="email" minlength="3" maxlength="60" required>
          <label>Hasło</label>
          <input type="password" name="password" minlength="5" maxlength="30" required>
        </div>
        <div>
          <label>Token</label>
          <input type="text" name="token" minlength="63" maxlength="65" required>
          <input type="hidden" name="method" value="createUser">
        </div>
        <div>
          <input type="submit" value="Zarejestruj">
        </div>
      <span>Jeśli posiadasz konto <a href="/login.php">Zaloguj się</a></span>
      </form>
    </div>
  </main>
<?php require($_SERVER["DOCUMENT_ROOT"]."/includes/root/footer.php"); ?>
