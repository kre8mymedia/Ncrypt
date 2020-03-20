<?php

$t = time();
$dir = "./files/upload";

// List files inside dir
$things = scandir($dir);
// Remove unecessaries
array_shift($things);
array_shift($things);

// Generate range AlphaNumeric string with length of N
function generateRandomString($length = 64) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

// Generate Random 64bit characterString
$encryption_key = generateRandomString();
$ciphering = "AES-128-CTR";
$iv_leng = openssl_cipher_iv_length($ciphering);
$options = 0;
$encryption_iv = '1234567891011121';

// Declare empty array to reference
$files = [];

// Foreach file in directory
foreach($things as $thing) {
  $file = str_replace(" ", "_", $thing);
  array_push($files, $file);
}

// Declare empty links array to reference
$file_links = [];

// If the files array count is less than 0...
if (count($files) > 0) {

  // For each file in files array...
  foreach ($files as $file) {

    // Get file type from extension
    $ext = pathinfo($file);
    $file_type = $ext['extension'];

    // Upload using shell exec with the filename on the end
    $command = shell_exec('curl -X POST "https://siasky.net/skynet/skyfile" -F file=@files/upload/' . $file);

    // After uploaded remove from upload folder
    echo shell_exec('rm -rf files/upload/' . $file);
    $json = json_decode($command, true);
    $skylink = $json['skylink'];
    $url = 'https://siasky.net/' . $skylink;
    array_push($file_links, $url);

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



