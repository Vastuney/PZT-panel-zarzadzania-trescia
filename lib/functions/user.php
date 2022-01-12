<?php
require($_SERVER['DOCUMENT_ROOT']. "/lib/phpMailer/src/PHPMailer.php");
require($_SERVER['DOCUMENT_ROOT']. "/lib/phpMailer/src/SMTP.php");
require($_SERVER['DOCUMENT_ROOT']. "/lib/phpMailer/src/Exception.php");
class User
{
  public function getUser($searchkey, $value)
  {
    $db = new DB_PDO();
    $result = $db->selectWhere("users", $searchkey, "=", $value, "char");
    while($row = $result->fetch()) {
      return $row;
    }
  }
  public function existUser($searchkey, $value)
  {
    $db = new DB_PDO();
    $result = $db->selectWhere("users", $searchkey, "=", $value, "char");
    if ($result->rowCount() > 0)
    {
      return true;
    } else
      {
        return false;
      }
  }
  public function remindPassword($email)
  {
    global $config;
    $user = $this->getUser("email", $email);
    if($user)
    {
      $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
                === 'on' ? "https" : "http") .
                "://" . $_SERVER['HTTP_HOST'] .
                $_SERVER['REQUEST_URI'];
      $link .= 'reset.php';
      $emailMD = $user['email'];
      $password = md5($user['password']);
      $link = '<a href="'.$link.'?e='.$emailMD.'&p='.$password.'">Zresetuj</a>';
      $message = 'Aby zresetować hasło użyj tego linku '.$link;
      $mail = new PHPMailer\PHPMailer\PHPMailer();
      $mail->IsSMTP();
      $mail->CharSet="UTF-8";
      $mail->Host = $config['emailHost']; /* Zależne od hostingu poczty*/
      $mail->SMTPDebug = 1;
      $mail->Port = $config['emailPort'] ; /* Zależne od hostingu poczty, czasem 587 */
      $mail->SMTPSecure = 'ssl'; /* Jeżeli ma być aktywne szyfrowanie SSL */
      $mail->SMTPAuth = true;
      $mail->IsHTML(true);
      $mail->Username = $config['emailLogin']; /* login do skrzynki email często adres*/
      $mail->Password = $config['emailPassword']; /* Hasło do poczty */
      $mail->setFrom($config['emailLogin'], $config['emailForm']); /* adres e-mail i nazwa nadawcy */
      $mail->AddAddress($email); /* adres lub adresy odbiorców */
      $mail->Subject = "PZT: Resetowanie hasła"; /* Tytuł wiadomości */
      $mail->Body = $message;
      new Feedback("success", "Instrukcja resetowania hasła wysłana na podany adres email");
    }
  }
  public function resetPassword($email, $oldpassword, $newpassword)
  {
    $user = $this->getUser("email", $email);
    if(md5($user['password']) == $oldpassword)
    {
      $sql = "UPDATE `users` SET `password` = '{$newpassword}' WHERE email = '{$email}'";
      $db = new DB_PDO();
      $db->freeRun($sql);
      new Feedback("location", "/login.php");
    } else
      {
        new Feedback("error", "Wystąpił błąd :(");
      }
  }
}


class Person extends User
{
  public $email;
  public $name;
  public $lastname;
  public $password;
  public $token;
  public $group;

  function __construct($email, $name, $lastname, $password, $token)
  {
    $validate = validate(["email" => $email, "name" => $name, "lastname" => $lastname, "password" => $password, "token" => $token]);
    $this->email = $validate["email"];
    $this->name = $validate["name"];
    $this->lastname = $validate["lastname"];
    $this->password = $validate["password"];
    $this->token = $validate["token"];
    return $this;
  }

  public function createUser($group)
  {
    if($this->existUser("email", $this->email))
    {
      new Feedback("error", "Konto o podanym adresie email już istnieje");
    } else
      {
      $this->group = $group;
      $t = new Token();
      $t = $t->ifExistToken($this->token);
      $t = $t->ifActiveToken();
      if($t)
      {
        $date = date('Y-m-d H:i:s');
        $values = [
          [ "type" => "char", "val" => "0" ],
          [ "type" => "char", "val" => $this->email ],
          [ "type" => "char", "val" => $this->name ],
          [ "type" => "char", "val" => $this->lastname ],
          [ "type" => "char", "val" => $this->password ],
          [ "type" => "char", "val" => $this->token ],
          [ "type" => "char", "val" => $date ],
          [ "type" => "char", "val" => $this->group ],
          [ "type" => "char", "val" => "0" ],
          [ "type" => "char", "val" => "0"]
        ];
        $class = new DB_PDO();
        $class->insertInto("users", $values);
        $user = $this->getUser("email", $this->email);
        $class = new Workspace($user['id']);
        $class->ifWorkspace();
        $class->createWorkspace();
        $class = new Token();
        $class->ifExistToken($this->token);
        $class->updateToken();
        new Feedback("location", "/login.php");
      } else
        {
          new Feedback("error", "Token nie jest już aktywny");
        }
      }
  }

  function __destruct()
  {
    unset($this->email);
    unset($this->name);
    unset($this->lastname);
    unset($this->password);
    unset($this->token);
    unset($this->group);
  }
}


class UserLogin extends User
{
  public $email;
  public $password;

  function __construct($email, $password)
  {
    $validate = validate(["email" => $email, "password" => $password]);
    $this->email = $validate["email"];
    $this->password = $validate["password"];
    $this->logIn();
  }

  private function logIn()
  {
    $db = new DB_PDO();
    $result = $db->selectFreeRun("SELECT * FROM users WHERE email = '{$this->email}' AND password = '{$this->password}'");
    if ($result->rowCount() > 0)
    {
      $user = $this->getUser("email", $this->email);
      $_SESSION["loggedin"] = true;
      $_SESSION["id"] = $user['id'];
      new Feedback("location", "index.php");
    } else
      {
        new Feedback("error", "Podano niepoprawny email bądź hasło");
        return;
      }
  }

  function __destruct()
  {
    unset($this->email);
    unset($this->password);
  }

}




?>
