<?php
  return $config = [
  	'adminID' => '1',
    'name' => 'PZT',
    'filesPath' => $_SERVER["DOCUMENT_ROOT"].'/users/files/',
    'codePath' => $_SERVER["DOCUMENT_ROOT"].'/users/code/',
    'uploadExtensions' => array("zip", "rar", "txt"),
    'uploadSize' => 33554432, // Bytes
    'maxSpace' => 104857600 // Bytes
  ];

?>
