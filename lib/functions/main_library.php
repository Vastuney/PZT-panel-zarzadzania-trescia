<?php
function user($searchkey, $value)
{
  $user = new User();
  if($user->existUser($searchkey, $value))
  {
    return $user->getUser($searchkey, $value);
  }
}
class Feedback
{
  public $state;
  public $text;

  function __construct($state, $text)
  {

    $this->state = $state;
    $this->text = $text;
    if($this->state === "location")
    {
      $this->location();
    } else if($this->state === true)
      {
        $this->success();
      } else if($this->state === false)
        {
          $this->error();
        }
  }

  public function error()
  {
    global $config;
    $this->text = "{$config['name']}: " . $this->text;
    $data = [
      'return' => 'error',
      'text' => $this->text
    ];
    echo json_encode($data);
    return false;
  }
  public function success()
  {
    global $config;
    $this->text = "{$config['name']}: " . $this->text;
    $data = [
      'return' => 'success',
      'text' => $this->text
    ];
    echo json_encode($data);
    return true;
  }
  public function location()
  {
    global $config;
    $this->text = $this->text;
    $data = [
      'return' => 'location',
      'text' => $this->text
    ];
    echo json_encode($data);
    return true;
  }

}

class Token
{
  public $token;
  public $ifExist;
  public $ifActive;

  function __construct()
  {
    $this->ifExist = false;
    $this->ifActive = false;
  }

  public function ifExistToken($token)
  {
    $this->token = $token;
    $db = new DB_PDO();
    $result = $db->selectFreeRun("SELECT * FROM token WHERE token = '{$this->token}'");
    if ($result->rowCount() > 0)
    {
      $this->ifExist = true;
    } else
      {
        $this->ifExist = false;
      }
    return $this;
  }

  public function ifActiveToken()
  {
    if($this->ifExist)
    {
      $db = new DB_PDO();
      $result = $db->selectFreeRun("SELECT * FROM token WHERE token = '{$this->token}' AND active = 1");
      if ($result->rowCount() > 0)
      {
          $this->ifActive = true;
          return $this->ifActive;
      } else
        {
          $this->ifActive = false;
          return $this->ifActive;
        }
    } else
      {
        $this->ifActive = false;
        return $this->ifActive;
      }
  }

  public function getToken()
  {
    if($this->ifExist)
    {
      $db = new DB_PDO();
      $result = $db->selectWhere("token", "token", "=", $this->token, "char");
      while($row = $result->fetch()) {
        return $row;
      }
    } else
      {
        new Feedback(false, "Token nie istnieje");
      }

  }

  public function updateToken()
  {
    if($this->ifExist)
    {
      $sql = "UPDATE `token` SET `used` = used + 1 WHERE token = '{$this->token}'";
      $db = new DB_PDO();
      $result = $db->freeRun($sql);
      if ($result->rowCount() > 0)
      {
        return true;
      } else
        {
          return false;
        }
    }
  }

  function __destruct()
  {
    unset($this->token);
    unset($this->ifExist);
    unset($this->ifActive);
  }
}

?>
