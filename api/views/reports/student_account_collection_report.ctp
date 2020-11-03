<?php
App::import('Vendor','student_account_collection_report');


$pr= new StudentAccountCollection();
$pr->hdr();
$pr->data();


$pr->output();
?>

