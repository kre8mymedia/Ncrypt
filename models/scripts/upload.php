<?php

$t = time();
// $repo = $t . "_" . $argv[1];
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
  // sleep(3);
  foreach ($files as $file) { 

    $ext = pathinfo($file);
    $file_type = $ext['extension'];

    echo shell_exec('mv files/upload/'. $file . ' files/upload/' . $ext['filename'] . '.txt');
    if ($file_type == "txt") {
      $command = shell_exec('curl -X POST "https://siasky.net/skynet/skyfile" -F file=@files/upload/' . $file);
      echo shell_exec('rm -rf files/upload/' . $file);
      $json = json_decode($command, true);
      $skylink = $json['skylink'];
      $url = 'https://siasky.net/' . $skylink;
      // echo $url;
      array_push($file_links, $url);
    }
  }
}

$i = 0;
$t = time();

foreach ($file_links as $link) {
  echo "\n==============================================================================";
  echo "\nfilename: " . $files[$i] . "\n";
  echo "skylink: " . $link . "\n";
  echo "==============================================================================\n";

  $address_book = file_get_contents("address_book.json");
  // echo $address_book;

  $new_book = str_replace('}
}', '},
  "' . $t . "_" .$files[$i] .'": {
    "address": "'. $link .'"
  }
}', $address_book);

  // echo $new_book . "\n";
  echo "Length: " . file_put_contents("address_book.json", $new_book) . "\n";
  $i++;
}



