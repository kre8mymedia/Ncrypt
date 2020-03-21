<?php
$y = -1;

while ($y < 0) { 
  echo shell_exec('php /scripts/upload.php');
  sleep(2);
}