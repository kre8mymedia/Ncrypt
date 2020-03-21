<?php

array_shift($argv);

if (!(isset($argv[0]))) {
  die("\n>> Model Name was not set!\n");
}
$model_name = $argv[0];
echo "\nModel Name: " . $model_name . "\n";

if (!(isset($argv[1]))) {
  die("\n>> Please Enter a URL as second cli argument\n");
}
$POST_URL = $argv[1];
echo "Post URL: " . $POST_URL . "\n";

// Retrieve Data Schema
$data = file_get_contents($POST_URL);
// Decode into readable json
$user = json_decode($data, true);
// Outputs Empty JSON model
print_r($user); 

// Function to generateRandomKeyString with N characters long
function generateRandomString($length = 32) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

// openSSL config & random generated key
$encryption_key = generateRandomString();
$options = 0;
$ciphering = "AES-128-CTR";
$iv_leng = openssl_cipher_iv_length($ciphering);
$encryption_iv = '1234567891011121';

// insert data into model
$user['key'] = 1;
$user['name'] = 'Ben';
$user['work'] = 'Colorado Engineering';
$user['email'] = 'BenSiewert@gmail.com';
$user['phone'] = '602-555-5234';
// Outputs the new user model
print_r($user);

// Declare empty array to store public facing keys
$pub_keys = [];

// Foreach piece of data in model Encrypt then push to pub_keys array
foreach($user as $key => $val) {
  $encryption = openssl_encrypt($val, $ciphering, $encryption_key, $options, $encryption_iv);
  $val = $encryption;
  array_push($pub_keys, $val);
}

// Replace non-encrypted data with encrypted data in object
$user['key'] = $pub_keys[0];
$user['name'] = $pub_keys[1];
$user['work'] = $pub_keys[2];
$user['email'] = $pub_keys[3];
$user['phone'] = $pub_keys[4];

// set the encrypted as a new_user variable
$new_user = json_encode($user);
// Output the array to be uploaded
print_r(json_decode($new_user, true));
// Push the contents into a text file to be exec'd below
$data = file_put_contents('dump.txt', $new_user);

// Uploads new encrypted data object to storage
$command = shell_exec('curl -X POST "https://siasky.net/skynet/skyfile" -F file=@dump.txt');
$json = json_decode($command, true);
$skylink = $json['skylink'];
$url = 'https://siasky.net/' . $skylink;

// Output the URL of the Filfe and the Encryption Key Used
echo "\n==============================================================================\n";
echo "Model Name: " . $model_name . "\n";
echo "Address: " . $url . "\n";
echo "password: " . $encryption_key . "\n";
echo "==============================================================================\n";

$t = time();
$address_book = file_get_contents("json/address_book.json");


  $new_book = str_replace('}
}', '},
  "' . $t . "_" . $model_name .'": {
    "address": "'. $url .'",
    "key": "'. $encryption_key .'"
  }
}', $address_book);

  // echo $new_book . "\n";
  echo "Length: " . file_put_contents("json/address_book.json", $new_book) . "\n";
