<?php
require $_SERVER["DOCUMENT_ROOT"]."/lib/sessions.php";
require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/main_library.php";
require $_SERVER["DOCUMENT_ROOT"]."/lib/config/config.php";
require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/database.php";
require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/user.php";
require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/workspace.php";
if(isset($_POST['method']))
{ $method = $_POST['method'];


  if($method == "createUser")
  {
    $person = new Person();
    $person->email = $_POST['email'];
    $person->name = $_POST['name'];
    $person->lastname = $_POST['lastname'];
    $person->password = $_POST['password'];
    $person->token = $_POST['token'];
    $person->validate();
    $person->createUser("user");
  } else if($method=="loginIn")
    {
      new UserLogin($_POST['email'], $_POST['password']);
    }
    else if($method=="upload")
    {
      $class = new Files($_SESSION['id']);
      $class = $class->upload($_POST['type'], $_POST['title'], $_FILES['file']);
    }
    else if($method=="deleteFile")
    {
      $class = new Files($_SESSION['id']);
      $class = $class->fileDelete($_POST['type'], $_POST['id']);
    }
    else if($method=="share")
    {
      $class = new Share($_POST['type'], $_POST['targetId'], $_POST['id']);
      $class->shareFile();
    }
    else if($method=="unshare")
    {
      $class = new Share($_POST['type'], $_POST['targetId'], $_POST['id']);
      $class->unshareFile();
    }


}
?>
