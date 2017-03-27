<?php

#base_url = http://download.asradiostation.com.br/overdrive/01overdrive.mp3
# composite_url = http://download.asradiostation.com.br/?file=http://download.asradiostation.com.br/overdrive/01overdrive.mp3&ref=1

$file = filter_input( INPUT_GET, 'file', FILTER_SANITIZE_URL );
if(empty($file)) {
  http_response_code(404);
  die();
}

$ref = filter_input( INPUT_GET, 'ref', FILTER_SANITIZE_NUMBER_INT );
if (empty($ref)){
  $ref = 1;
}

date_default_timezone_set('America/Sao_Paulo');
$date = date("Y-m-d H:i:s");

try
{
  $db = new PDO('sqlite:../main.db');
  $log = $db->prepare("INSERT INTO downloads (file, date, reference) VALUES (:file, :date, :reference)");
  $log->execute(array('file' => $file, 'date' => $date, 'reference' => $ref));
  $db = null;
}

catch(PDOException $e){
  print 'Exception : '.$e->getMessage();
}

header('Location: ' . $file);
exit;
