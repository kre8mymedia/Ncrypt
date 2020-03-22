<?php

array_shift($argv);

if (!(isset($argv[0]))) {
  die("\n>> Enter a name for this Model as first \n");
}
$model_name = $argv[0];
echo "\nModel Name: " . $model_name . "\n";

if (!(isset($argv[1]))) {
  die("\n>> Insert Model Schema URL as second Arg\n");
}
$POST_URL = $argv[1];
echo "Schema URL: " . $POST_URL . "\n";
echo "++ Initial Schema \n";
// Retrieve Data Schema
$data = file_get_contents($POST_URL);
// Decode into readable json
$obj = json_decode($data, true);
// Outputs Empty JSON model
print_r($obj); 

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
$obj['first_name'] = "Jamie";
$obj['last_name'] = 'Roger';
$obj['current_address'] = '11291 E. Lupine Ave';
$obj['social_security'] = "324-34-5435";
$obj['phone_number'] = "553-253-5322";
$obj['bank']['name'] = "Chase";
$obj['bank']['account_number'] = "40239209920392392";
$obj['bank']['routing_number'] = "50223049802";
$obj['job']['industry'] = "Banking";
$obj['job']['name'] = "Chase";
$obj['job']['title'] = "Head of Banking";
$obj['job']['years_at_company'] = 70;
// $obj['job']['employees']['manager'] = "Henry Tompson";
// $obj['job']['employees']['developer'] = "Jimmy Josh";

echo "++ User Added Object Schema (Non_Ncrpted) \n";
// Outputs the new user model
print_r($obj);

// Declare empty array to store public facing keys
$pub_keys = [];
$counter = 0;
// Foreach piece of data in model Encrypt then push to pub_keys array
foreach($obj as $key => $val) {
  
  if (gettype($val) == 'string') {
    echo "\n++ One Dimensional [Key, Value] Pair\n";
    echo "[" . $counter . "] " . $key . " => " . $val . "\n";
    $encryption = openssl_encrypt($val, $ciphering, $encryption_key, $options, $encryption_iv);
    $val = $encryption;
    array_push($pub_keys, $val);
    $counter++;
  }

  if (gettype($val) == 'array') {
    echo "\n++ Arrays Follow by their Values\n";
    echo "[" .$key."] Array\n";

    foreach ($val as $arr_key => $arr_val) {
      echo "[" . $counter . "] Array value: " . $arr_val . "\n";
      $encryption = openssl_encrypt($arr_val, $ciphering, $encryption_key, $options, $encryption_iv);
      $nest_val = $encryption;
      array_push($pub_keys, $nest_val);
      $counter++;
    }
  }
}

echo "\n++ Encrypted Objects Array\n";
print_r($pub_keys);

// Replace non-encrypted data with encrypted data in object
$obj['first_name'] = $pub_keys[0];
$obj['last_name'] = $pub_keys[1];
$obj['current_address'] = $pub_keys[2];
$obj['social_security'] = $pub_keys[3];
$obj['phone_number'] = $pub_keys[4];
$obj['bank']['name'] = $pub_keys[5];
$obj['bank']['account_number'] = $pub_keys[6];
$obj['bank']['routing_number'] = $pub_keys[7];
$obj['job']['industry'] = $pub_keys[8];
$obj['job']['name'] = $pub_keys[9];
$obj['job']['title'] = $pub_keys[10];
$obj['job']['years_at_company'] =$pub_keys[11];
// $obj['job']['employees']['manager'] = $pub_keys[12];
// $obj['job']['employees']['developer'] = $pub_keys[13];


echo "\n++ Encryted Values added to Schema\n";
// set the encrypted as a new_user variable
$new_obj = json_encode($obj);
// Output the array to be uploaded
print_r(json_decode($new_obj, true));
// Push the contents into a text file to be exec'd below
$data = file_put_contents('dump.txt', $new_obj);

// Uploads new encrypted data object to storage
$command = shell_exec('curl -X POST "https://siasky.net/skynet/skyfile" -F file=@dump.txt');
$json = json_decode($command, true);
$skylink = $json['skylink'];
$url = 'https://siasky.net/' . $skylink;

$t = time();
echo "\nTimestamp: " . $t . "\n";

// Output the URL of the Filfe and the Encryption Key Used
echo "\n++ Added this config to address_book.json locally\n";
echo "==============================================================================\n";
echo "Model Name: " . $t ."_". $model_name . "\n";
echo "Address: " . $url . "\n";
echo "password: " . $encryption_key . "\n";
echo "==============================================================================\n";


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
