<?php

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

class Workspace
{
  public $userid;
  public $filename;
  public $filepath;
  public $codePath;
  public $ifWorkspace;

  function __construct($userid)
  {
    global $config;
    $this->userid = $userid;
    $this->filepath = $config['filesPath'].$this->userid;
    $this->codePath = $config['codePath'].$this->userid;
  }

  public function generateFilename()
  {
    $this->filename = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'),0,16);
    return $this;
  }

  public function ifWorkspace()
  {
    if(is_dir($this->filepath) || is_dir($this->codePath))
    {
      $this->ifWorkspace = true;
    } else
      {
        $this->ifWorkspace = false;
      }
    return $this;
  }
  public function createWorkspace()
  {
    if($this->ifWorkspace)
    {
      new Feedback(false, "Przestrzeń robocza już istnieje");
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
  function __construct($userid)
  {
    global $config;
    $this->userid = $userid;
    $this->filepath = $config['filesPath'].$userid."/";
    $this->codepath = $config['codePath'].$userid."/";
    if($this->existUser("id", $userid)) {
      $this->check = true;
    } else {
      $this->check = false;
      new Feedback(false, "Użytkownik nie istnieje");
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
        new Feedback(false, "Użytkownik nie istnieje");
        return;
      }
  }
  public function ifExist($type, $filename)
  {
    if($type == "files")
    {
      $path = $this->filepath;
    } else if($type == "codes")
      {
        $path = $this->codepath;
      } else
        {
          new Feedback(false, "Nierozpoznany typ pliku");
          return;
        }
    if(file_exists($path.$filename))
    {
      return true;
    } else
      {
        return false;
      }
  }
  public function getFiles($type)
  {
    if($this->check)
    {
      switch ($type)
      {
          case "files":
              $path = $this->filepath;
              break;
          case "codes":
              $path = $this->codepath;
              break;
      }
      $files = array_diff(scandir($path), array('.', '..'));
      $array = [];
      foreach($files as $file) {
        $a = [$file => filesize($path.$file)];
        array_push($array, $a);
      }
      return $array;
    } else
      {
        new Feedback(false, "Użytkownik nie istnieje");
        return;
      }
  }
  public function fileExt($file)
  {
    $path = $file['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    return $ext;
  }
  public function upload($type, $title, $file)
  {
    global $config;
    if($this->check)
    {
      if(!$this->ifExist($type, basename($file['name'])))
      {
        if( in_array($this->fileExt($file), $config['uploadExtensions'] ) )
        {
          if(($file['size'] <= $config['uploadSize']))
          {
            switch ($type)
            {
                case "files":
                    $path = $this->filepath;
                    break;
                case "codes":
                    $path = $this->codepath;
                    break;
            }
            $uploadfile = $path . basename($file['name']);
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
              $db = $db->insertInto($type, $values);
              new Feedback(true, "Pomyślnie dodano plik");
              return;
            } else {
              new Feedback(false, "Nie udało się wrzucić pliku");
              return;
            }
          } else
            {
              new Feedback(false, "Plik jest za duży maksymalna wartość to ".uploadSize());
              return;
            }
        } else
          {
            new Feedback(false, "Niepoprawny format pliku");
            return;
          }

      } else
        {
          new Feedback(false, "Taki plik już istnieje");
          return;
        }
    } else
      {
        new Feedback(false, "Użytkownik nie istnieje");
        return;
      }
  }
  public function fileDelete($type, $id)
  {
    if($this->check)
    {
      switch ($type)
      {
          case "files":
              $path = $this->filepath;
              break;
          case "codes":
              $path = $this->codepath;
              break;
      }
      $db = new DB_PDO();
      $result = $db->selectWhere($type, "id", "=", $id, "char");
      $row = $result->fetch();
      if($this->ifExist($type, $row['filename']) && $row['addedby'] == $this->userid)
      {
        if (unlink($path.$row['filename']))
        {
          $db = new DB_PDO();
          $db->freeRun("DELETE FROM `{$type}` WHERE id = '{$id}'");
          new Feedback(true, "Udało się usunąć plik");
          return;
        } else
          {
            new Feedback(false, "Wystąpił błąd przy usuwaniu pliku");
            return;
          }

      } else
        {
          new Feedback(false, "Plik nie istnieje");
          return;
        }
    } else
      {
        new Feedback(false, "Użytkownik nie istnieje");
        return;
      }
  }

  public function showFiles($type)
  {
    switch ($type)
    {
        case "files":
            $path = $this->filepath;
            break;
        case "codes":
            $path = $this->codepath;
            break;
    }
    $files = $this->getFiles($type);
    $currentuser = $_SESSION['id'];
    $db = new DB_PDO();
    $sql = "SELECT * FROM `{$type}` WHERE `addedby` = {$currentuser} OR `sharedto` LIKE '%{$currentuser}%'";
    $result = $db->freeRun($sql);
    while($row = $result->fetch()) {
      $class = new Share($type, $row['id'], $_SESSION['id'], $row['sharedto']);
      $class = $class->labels();
      echo
      '
      <div class="label">
        <div class="col">
      ';
          if($_SESSION['id'] == $row['addedby'])
          {
            echo
            '
            <i class="fas fa-trash delete" for="'.$type.'" data-id="'.$row['id'].'"></i>
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
            <h3>'.$row['title'].'</h3>
            <span>'.uploadSize($row['size']).'</span>
          </div>
          <div>
            <a href="'.$path.$row['filename'].'"><button class="button info">Pobierz</button></a>
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
    $this->type = $type;
    $this->fileid = $fileid;
    $this->userid = $userid;
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
            new Feedback(true, "Udostępniłeś plik");
            return;
          } else
            {
              new Feedback(false, "Nie udało się udostępnić pliku");
              return;
            }
      } else
        {
          new Feedback(false, "Plik nie istnieje");
          return;
        }
    } else
      {
        new Feedback(false, "Autoryzacja nieudana");
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
            new Feedback(true, "Usunałeś udostępnianie pliku");
            return;
          } else
            {
              new Feedback(false, "Nie udało się usunąć udostępniania pliku");
              return;
            }
      } else
        {
          new Feedback(false, "Plik nie istnieje");
          return;
        }
    } else
      {
        new Feedback(false, "Autoryzacja nieudana");
        return;
      }
  }
}




?>
