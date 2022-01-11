<html lang="pl" dir="ltr">
<?php
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
   header("location: /../login.php");
   exit;
  } else
    {
      $user = user("id", $_SESSION['id']);
    }
?>
  <head>
      <meta charset="utf-8">
      <title>PZT: Strona Główna</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="/assets/css/reset.css?v=<?php echo time(); ?>">
      <link rel="stylesheet" href="/assets/css/main.css?v=<?php echo time(); ?>">
  </head>
