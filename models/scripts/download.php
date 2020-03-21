<?php
include "upload.php";

$t = time();

$i = 0;

foreach ($file_links as $link) {
  echo shell_exec('curl "'. $link .'" -o ./files/download/' . $t . '_' . $files[$i]);
  $i++;
}