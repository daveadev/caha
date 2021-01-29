<?php
App::import('Vendor','student_registration_form');
//pr($data);exit;

$pr= new StudentRegistrationForm();
$pr->hdr();
$pr->data();

$pr->output();
?>

