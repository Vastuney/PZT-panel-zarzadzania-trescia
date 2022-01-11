<?php

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
}


class Person extends User
{
  public $email;
  public $name;
  public $lastname;
  public $password;
  public $token;
  public $group;

  function __construct()
  {
  }

  public function validate()
  {
      if(empty($this->email) || empty($this->name) || empty($this->lastname) || empty($this->password) || empty($this->password) || empty($this->token))
      {
        new Feedback(false, "Niektóre z danych były puste");
      } else
        {
          $this->email = trim($this->email);
          $this->name = trim($this->name);
          $this->lastname = trim($this->lastname);
          $this->password = trim($this->password);
          $this->token = trim($this->token);

          return $this;
        }
  }

  public function createUser($group)
  {
    if($this->existUser("email", $this->email))
    {
      new Feedback(false, "Konto o podanym adresie email już istnieje");
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
        $db = new DB_PDO();
        $db = $db->insertInto("users", $values);
        $db = $this->getUser("email", $this->email);

        $ws = new Workspace($db['id']);
        $ws = $ws->ifWorkspace();
        $ws = $ws->createWorkspace();

        $t = new Token();
        $t->ifExistToken($this->token);
        $t->updateToken();

        new Feedback("location", "/login.php");
      } else
        {
          new Feedback(false, "Token nie jest już aktywny");
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
    $this->email = $email;
    $this->password = $password;
    if($this->existUser("email", $this->email))
    {
      $this->validate()->logIn();
    } else
      {
        new Feedback(false, "Podano niepoprawny email bądź hasło");
      }
  }

  private function validate()
  {
      if(empty($this->email) || empty($this->password))
      {
        new Feedback(false, "Niektóre z danych były puste");
      } else
        {
          $this->email = trim($this->email);
          $this->password = trim($this->password);
          return $this;
        }
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
        new Feedback(false, "Podano niepoprawny email bądź hasło");
      }
  }

  function __destruct()
  {
    unset($this->email);
    unset($this->password);
  }

}




?>
