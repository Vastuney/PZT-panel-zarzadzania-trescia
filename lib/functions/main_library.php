<?php
function user($searchkey, $value)
{
  $user = new User();
  if($user->existUser($searchkey, $value))
  {
    return $user->getUser($searchkey, $value);
  }
}

function validate($array){
  foreach($array as $key => $value)
  {
    if (!empty($value) && isset($value))
    {
      $value = trim($value);
      $value = stripslashes($value);
      $value = htmlspecialchars($value);
      $array[$key] = $value;
    } else
      {
        new Feedback("error", "Autoryzacja danych wykazała błąd");
        return;
      }
  }
  return $array;
}
function extensions() {
  global $config;
  $string = " ";
  $array = $config['uploadExtensions'];
  $toEnd = count($array);
  foreach($array as $key => $value)
  {
    if (0 === --$toEnd)
    {
      $string .= $value;
    } else
      {
        $string .= $value.", ";
      }
  }
  return $string;
}
function uploadSize($bytes) {
    global $config;
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $precision = 0;
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
function usedSpace($userid) {
  global $config;
  $bytestotal = 0;
  $bytestotal2 = 0;
  $path = realpath($config['filesPath'].$userid."/");
  $path2 = realpath($config['codePath'].$userid."/");
    if($path!==false && $path!='' && file_exists($path)){
      foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
        $bytestotal += $object->getSize();
      }
    }
    if($path2!==false && $path2!='' && file_exists($path2)){
      foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path2, FilesystemIterator::SKIP_DOTS)) as $object){
        $bytestotal += $object->getSize();
      }
    }
  return $bytestotal;
}

class Feedback
{
  public $state;
  public $text;

  function __construct($state, $text)
  {

    $this->state = $state;
    $this->text = $text;
    switch ($this->state)
    {
      case "location":
        $this->location();
        break;
      case "success":
        $this->success();
        break;
      case "error":
        $this->error();
        break;
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
    return;
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
    return;
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
    return;
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
        new Feedback("error", "Token nie już aktywny");
      }

  }

  public function updateToken()
  {
    if($this->ifExist)
    {
      $sql = "UPDATE `token` SET `used` = used + 1 WHERE token = '{$this->token}'";
      $db = new DB_PDO();
      $result = $db->freeRun($sql);
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
