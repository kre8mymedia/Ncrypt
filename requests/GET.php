<?php

// Will not trigger unless argument supplied
if (!(isset($argv[1]))) {
  die("\n>> Please enter URL as argv\n");
} else {
  // First argument is the GET url
  $url = $argv[1];
}

// Make request to get contents
$data = file_get_contents($url);
// Decode into json
$obj = json_decode($data, true);
echo "\nObject @ URL\n";
// Print out returned ENCRYPTED object
print_r($obj) . "\n";

// Fetch local address book 
$get_key = file_get_contents('json/address_book.json');
// Decode the address book to json
$addr_obj = json_decode($get_key, true);

// Declare decryption constants
$decryption_key = "";
$options = 0;
$ciphering = "AES-128-CTR";
$iv_leng = openssl_cipher_iv_length($ciphering);
$decryption_iv = '1234567891011121';

// Keep track of loop index
$i = 0;

// Declare loop constant variables
$found_address = "";
$found_key = "";

// For each model return from local address_book
foreach ($addr_obj as $model) {
  // If the models' address matches the GET URL
  if($model['address'] == $url) {

    // Set the FOUND contents
    $found_address = $model['address'];
    $found_key = $model['key'];
    
    echo "\n=====================================  FOUND!  =======================================\n";
    echo "                                Located at index: " . $i . "\n";
    echo "--------------------------------------------------------------------------------------\n";
    echo "address: '" . $model['address'] . "'\n";
    echo "key: '" . $model['key'] . "'\n";
    echo "======================================================================================\n";

    // The decryption key is equal to the local key found in address_book
    $decryption_key = $found_key;
    // Get the contents from OUR local address
    $found_contents = file_get_contents($found_address);
    // Decode it into json
    $found_json = json_decode($found_contents, true);

    // Declare empty decrypted objects 
    $pub_keys = [];
    $counter = 0;

    // For each key, value pair in json
    foreach($found_json as $key => $val) {

      if (gettype($val) == 'string') {
        echo "[" . $counter . "] String value: " . $val . "\n";
        // Decrypt the VALUE
        $decryption = openssl_decrypt($val, $ciphering, $decryption_key, $options, $decryption_iv);
        $val = $decryption;
        // Push the DECRYPTED value to the decrypt_obj array
        array_push($pub_keys, $val);
        $counter++;
      }

      if (gettype($val) == 'array') {
        echo "\n[" .$key."] Array\n";

        foreach ($val as $arr_key => $arr_val) {
          echo "[" . $counter . "] Array value: " . $arr_val . "\n";
          // Decrypt the VALUE
          $decryption = openssl_decrypt($arr_val, $ciphering, $decryption_key, $options, $decryption_iv);
          $nest_val = $decryption;
          // Push the DECRYPTED value to the decrypt_obj array
          array_push($pub_keys, $nest_val);
          $counter++;
        }
      }
    }

    // Will print the newly decrypted object values and their INDEX's to console
    print_r($pub_keys);

    // Set decrypt_obj VALUES to their corresponding keys
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

    // Print the DECrypted Object
    print_r($obj);

  // If the URL does NOT match this INDEX
  } else {
    echo ">> Not found at " . $i . "\n";
  }
  // Increment Index
  $i++;
}



