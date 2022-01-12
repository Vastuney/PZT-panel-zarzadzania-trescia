<?php
  require $_SERVER["DOCUMENT_ROOT"]."/lib/sessions.php";
  require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/main_library.php";
  require $_SERVER["DOCUMENT_ROOT"]."/lib/config/config.php";
  require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/database.php";
  require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/user.php";
  require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/workspace.php";
  require $_SERVER["DOCUMENT_ROOT"]."/lib/functions/notifications.php";
  if(isset($_POST['method']))
  { $method = $_POST['method'];
    if($method == "createUser")
    {
      $person = new Person($_POST['email'], $_POST['name'], $_POST['lastname'], $_POST['password'], $_POST['token']);
      $person->createUser("user");
    } else if($method=="loginIn")
      {
        new UserLogin($_POST['email'], $_POST['password']);
      }
      else if($method=="upload")
      {
        $class = new Files($_SESSION['id'], $_POST['type']);
        $class = $class->upload($_POST['title'], $_FILES['file']);
      }
      else if($method=="deleteFile")
      {
        $class = new Files($_SESSION['id'], $_POST['type']);
        $class = $class->fileDelete($_POST['id']);
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
      else if($method=="deleteNotifications")
      {
        $class = new Notifications($_SESSION['id']);
        $class->deleteNotifications();
      }
      else if($method=="remindPassword")
      {
        echo "tak";
      }
    }
?>
