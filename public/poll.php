<?php
$y = -1;

while ($y < 0) { 
  echo shell_exec('php download.php');
  sleep(2);
}