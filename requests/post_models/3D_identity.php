<?php
// 3-Dimension Identity Model
$model_schema = "3D_identity";

// Full Identity Model 3D 
// (bottom 2 wont work at the moment 3d array problem)
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
$obj['job']['employees']['manager'] = "Henry Tompson";
$obj['job']['employees']['developer'] = "Jimmy Josh";