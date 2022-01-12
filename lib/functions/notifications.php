<?php
  class Notifications extends User
  {
    public $userid;

    function __construct($userid)
    {
      $this->userid = $userid;
      return $this;
    }
    public function showNotifications()
    {
      $db = new DB_PDO();
      $result = $db->freeRun("SELECT * FROM `notifications` WHERE `userid` = '{$this->userid}' ORDER BY id DESC");
      while ($row = $result->fetch()) {
        echo "<li>".$row['text']."</li>";
      }
      if($result->rowCount() <= 0)
      {
        echo "<li>Brak powiadomień</li>";
      } else {
        echo '<li></li>';
        echo '<li class="deleteNotifications">Usuń powiadomienia</li>';
      }
    }
    public function sendNotifications($text)
    {
        $date = time();
        $values = [
          [ "type" => "char", "val" => "0" ],
          [ "type" => "char", "val" => $this->userid ],
          [ "type" => "char", "val" => $date ],
          [ "type" => "char", "val" => $text ]
        ];
        $class = new DB_PDO();
        $class->insertInto("notifications", $values);
    }
    public function deleteNotifications()
    {
      $db = new DB_PDO();
      $db->freeRun("DELETE FROM `notifications` WHERE userid = '{$this->userid}'");
      new Feedback("location", "/index.php");
      return;
    }
  }

?>
