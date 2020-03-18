<?php
$dir = "./files/upload";

// List files inside dir
$things = scandir($dir);
// Remove unecessaries
array_shift($things);
array_shift($things);

$files = [];

foreach($things as $thing) {
  $file = str_replace(" ", "_", $thing);
  array_push($files, $file);
}
// print_r($files);
// die();

$file_links = [];

if (count($files) > 0) {

  foreach ($files as $file) { 

    $command = shell_exec('curl -X POST "https://siasky.net/skynet/skyfile" -F file=@files/upload/' . $file);
    echo shell_exec('rm -rf files/upload/' . $file);
    $json = json_decode($command, true);
    $skylink = $json['skylink'];
    $url = 'https://siasky.net/' . $skylink;
    // echo $url;
    array_push($file_links, $url);
  }
}

foreach ($file_links as $link) {
  echo "==============================================================================";
  echo "\nFile link >> " . $link . "\n";
  echo "==============================================================================\n";
} 