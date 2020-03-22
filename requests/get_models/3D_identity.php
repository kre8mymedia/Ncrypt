<?php
  // Full Identity Model 3D 
  // (bottom 2 wont work at the moment 3d array problem)
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
  $obj['job']['employees']['manager'] = $pub_keys[12];
  $obj['job']['employees']['developer'] = $pub_keys[13];