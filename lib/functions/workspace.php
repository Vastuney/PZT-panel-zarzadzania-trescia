<?php

class Workspace
{
  public $userid;
  public $filename;
  public $filepath;
  public $codePath;

  function __construct($userid)
  {
    global $config;
    $this->userid = $userid;
    $this->filepath = $config['filesPath'].$this->userid;
    $this->codePath = $config['codePath'].$this->userid;
  }

  public function ifWorkspace()
  {
    if(is_dir($this->filepath) || is_dir($this->codePath))
    {
      return true;
    } else
      {
        return false;
      }
    return $this;
  }
  public function createWorkspace()
  {
    if($this->ifWorkspace())
    {
      new Feedback("error", "Przestrzeń robocza już istnieje");
      return;
    } else
      {
        mkdir($this->filepath, 0777, true);
        mkdir($this->codePath, 0700, true);
      }
  }
}

class Files extends User
{
  public $userid;
  public $filepath;
  public $codepath;
  public $check;
  function __construct($userid, $type)
  {
    global $config;
    $this->userid = $userid;
    $this->filepath = $config['filesPath'].$userid."/";
    $this->codepath = $config['codePath'].$userid."/";
    $this->type = $type;
    switch ($type)
    {
        case "files":
            $this->path = $this->filepath;
            break;
        case "codes":
            $this->path = $this->codepath;
            break;
    }
    if($this->existUser("id", $userid)) {
      $this->check = true;
    } else {
      $this->check = false;
      new Feedback("error", "Użytkownik nie istnieje");
      return;
    }
    return $this;
  }
  public function count()
  {
    if($this->check)
    {
      $countFiles = count(glob($this->filepath . "*"));
      $countCode = count(glob($this->codepath . "*"));
      $sql = "UPDATE `users` SET `filesCount` = '{$countFiles}' WHERE id = '{$this->userid}'";
      $sql2 = "UPDATE `users` SET `codeCount` = '{$countCode}' WHERE id = '{$this->userid}'";
      $db = new DB_PDO();
      $db->freeRun($sql);
      $db->freeRun($sql2);
    } else
      {
        new Feedback("error", "Użytkownik nie istnieje");
        return;
      }
  }
  public function ifExist($filename)
  {
    if(file_exists($this->path.$filename))
    {
      return true;
    } else
      {
        return false;
      }
  }
  public function getFiles()
  {
    if($this->check)
    {
      $files = array_diff(scandir($this->path), array('.', '..'));
      $array = [];
      foreach($files as $file) {
        $a = [$file => filesize($this->path.$file)];
        array_push($array, $a);
      }
      return $array;
    } else
      {
        new Feedback("error", "Użytkownik nie istnieje");
        return;
      }
  }
  public function fileExt($file)
  {
    $path = $file['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return $ext;
  }
  public function upload($title, $file)
  {
    global $config;
    $validate = validate(["title" => $title]);
    $title = $validate['title'];
    if($this->check)
    {
      if(!$this->ifExist(basename($file['name'])))
      {
        if( in_array($this->fileExt($file), $config['uploadExtensions'] ) )
        {
          if(($file['size'] <= $config['uploadSize']))
          {
            if(usedSpace($_SESSION['id']) < $config['maxSpace'])
            {
              $space = intval(usedSpace($_SESSION['id'])) + intval($file['size']);
              if($space < $config['maxSpace'])
              {
                $uploadfile = $this->path . basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
                  $date = time();
                  $values = [
                    [ "type" => "char", "val" => "0" ],
                    [ "type" => "char", "val" => $file['name'] ],
                    [ "type" => "char", "val" => $title ],
                    [ "type" => "char", "val" => $this->userid ],
                    [ "type" => "char", "val" => $date ],
                    [ "type" => "char", "val" => "0" ],
                    [ "type" => "char", "val" => $file['size'] ]
                  ];
                  $db = new DB_PDO();
                  $db = $db->insertInto($this->type, $values);
                  new Feedback("location", "/index.php");
                  return;
                } else {
                  new Feedback("error", "Nie udało się wrzucić pliku");
                  return;
                }
              } else
                {
                  new Feedback("error", "Nie starczy Ci miejsca na pliki !");
                  return;
                }
            } else
              {
                new Feedback("error", "Wykorzystałeś limit dysku, usuń pliki.");
                return;
              }
          } else
            {
              new Feedback("error", "Plik jest za duży maksymalna wartość to ".uploadSize());
              return;
            }
        } else
          {
            new Feedback("error", "Niepoprawny format pliku");
            return;
          }

      } else
        {
          new Feedback("error", "Taki plik już istnieje");
          return;
        }
    } else
      {
        new Feedback("error", "Użytkownik nie istnieje");
        return;
      }
  }
  public function fileDelete($id)
  {
    $validate = validate(["id" => $id]);
    $id = $validate['id'];
    if($this->check)
    {
      $db = new DB_PDO();
      $result = $db->selectWhere($this->type, "id", "=", $id, "char");
      $row = $result->fetch();
      if($this->ifExist($row['filename']) && $row['addedby'] == $this->userid)
      {
        if (unlink($this->path.$row['filename']))
        {
          $db = new DB_PDO();
          $db->freeRun("DELETE FROM `{$this->type}` WHERE id = '{$id}'");
          new Feedback("location", "/index.php");
          return;
        } else
          {
            new Feedback("error", "Wystąpił błąd przy usuwaniu pliku");
            return;
          }

      } else
        {
          new Feedback("error", "Plik nie istnieje");
          return;
        }
    } else
      {
        new Feedback("error", "Użytkownik nie istnieje");
        return;
      }
  }

  public function showFiles()
  {
    $files = $this->getFiles();
    $currentuser = $_SESSION['id'];
    $db = new DB_PDO();
    $sql = "SELECT * FROM `{$this->type}` WHERE `addedby` = {$currentuser} OR `sharedto` LIKE '%{$currentuser}%'";
    $result = $db->freeRun($sql);
    while($row = $result->fetch()) {
      $class = new Share($this->type, $row['id'], $_SESSION['id'], $row['sharedto']);
      $class = $class->labels();
      echo
      '
      <div class="label search_index">
        <div class="col">
      ';
          if($_SESSION['id'] == $row['addedby'])
          {
            echo
            '
            <i class="fas fa-trash delete" for="'.$this->type.'" data-id="'.$row['id'].'"></i>
            <i class="fas fa-share seeModal"></i>
            <div class="modal">
              <div class="modal_top">
                <h2>Udostępnij</h2>
                <div class="close"><i class="fas fa-times"></i></div>
              </div>
              <div class="modal_container">
              '.$class.'
              </div>
              </div>
              <li class="dropdown">
                <i class="fas fa-ellipsis-v"></i>
                <ul class="dropdown">
                  <li><a href="#">Czysty tekst</a></li>
                </ul>
              </li>
            ';
          } else
            {
              $getUser = $this->getUser("id", $row['addedby']);
              echo
              '
              <span>Udostępnione przez <a href="/profile.php?id='.$getUser['id'].'">'.$getUser['name'].'</a></span>
              ';
            }
          echo
          '
        </div>
        <div class="col">
          <div>
            <h3 class="search_title">'.$row['title'].'</h3>
            <span>'.uploadSize($row['size']).'</span>
          </div>
          <div>
            <a href="'.$this->path.$row['filename'].'"><button class="button info">Pobierz</button></a>
          </div>
        </div>
      </div>
      ';
    }
  }

}

class Share extends User
{
  public $type;
  public $fileid;
  public $userid;
  public $sharedto;
  function __construct($type, $fileid, $userid, $shared = 0)
  {
    $validate = validate(["type" => $type, "fileid" => $fileid, "userid" => $userid]);
    $this->type = $validate["type"];
    $this->fileid = $validate["fileid"];
    $this->userid = $validate["userid"];
    $this->sharedto = $shared;
    return $this;
  }
  public function labels()
  {
    $string = "";
    $db = new DB_PDO();
    $sql = "SELECT * FROM `users` WHERE `id` != {$this->userid}";
    $result = $db->freeRun($sql);
    while($row = $result->fetch()) {
      if(mb_strpos($this->sharedto,$row['id']) !== false)
      {
        $button = '<button class="button info unshare" data-target="files" for="'.$this->fileid.'" data-id="'.$row['id'].'">Zatrzymaj</button>';
      } else
        {
          $button = '<button class="button info share" data-target="files" for="'.$this->fileid.'" data-id="'.$row['id'].'">Udostępnij</button>';
        }
      $string .=
      '
      <div class="label">
        <div class="col">
          <span>'.$row['email'].'</span>
        </div>
        <div class="col">
          <div>
            <h3>'.$row['name'].' '.$row['surname'].'</h3>
          </div>
          <div>
            '.$button.'
          </div>
        </div>
      </div>
      ';
    }
    return $string;
  }
  public function authentication()
  {
    $db = new DB_PDO();
    $result = $db->selectWhere($this->type, "addedby", "=", $_SESSION['id'], "char");
    if($row = $result->fetch())
    {
      return true;
    } else
      {
        return false;
      }
  }
  public function fileExist()
  {
    $db = new DB_PDO();
    $result = $db->selectWhere($this->type, "id", "=", $this->fileid, "char");
    if($row = $result->fetch())
    {
      return true;
    } else
      {
        return false;
      }
  }
  public function shareFile()
  {
    if($this->authentication())
    {
      if($this->fileExist())
      {
        $db = new DB_PDO();
        $result = $db->selectWhere($this->type, "id", "=", $this->fileid, "char");
        $row = $result->fetch();
        $sharedTo = $row['sharedto'];
        if($sharedTo == "0" || $sharedTo == "")
        {
          $sharedTo = $this->userid;
        } else
          {
            $sharedTo .= ",".$this->userid;
          }
          $db = new DB_PDO();
          $sql = "UPDATE `{$this->type}` SET `sharedTo` = '{$sharedTo}' WHERE id = '{$this->fileid}'";
          $db = $db->freeRun($sql);
          if($db)
          {
            $class = new Notifications($this->userid);
            $class->sendNotifications('Plik '.$row['title']." został Ci udostępniony");
            new Feedback("success", "Udostępniłeś plik");
            return;
          } else
            {
              new Feedback("error", "Nie udało się udostępnić pliku");
              return;
            }
      } else
        {
          new Feedback("error", "Plik nie istnieje");
          return;
        }
    } else
      {
        new Feedback("error", "Autoryzacja nieudana");
        return;
      }
  }
  public function unshareFile()
  {
    if($this->authentication())
    {
      if($this->fileExist())
      {
        $db = new DB_PDO();
        $result = $db->selectWhere($this->type, "id", "=", $this->fileid, "char");
        $row = $result->fetch();
        $str = $row['sharedto'];
        preg_match_all('!\d+!', $str, $matches);
        $matches = array_diff( $matches[0], [$this->userid] );
        $sharedTo = implode(",",$matches);
          $db = new DB_PDO();
          $sql = "UPDATE `{$this->type}` SET `sharedTo` = '{$sharedTo}' WHERE id = '{$this->fileid}'";
          $db = $db->freeRun($sql);
          if($db)
          {
            $class = new Notifications($this->userid);
            $class->sendNotifications('Plik '.$row['title']." nie jest Ci już udostępniany");
            new Feedback("success", "Usunałeś udostępnianie pliku");
            return;
          } else
            {
              new Feedback("error", "Nie udało się usunąć udostępniania pliku");
              return;
            }
      } else
        {
          new Feedback("error", "Plik nie istnieje");
          return;
        }
    } else
      {
        new Feedback("error", "Autoryzacja nieudana");
        return;
      }
  }
}




?>
