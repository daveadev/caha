<?php
App::import('Vendor','receipt');

//pr($data);exit;


$pr= new OfficialReceipt();
$pr->receipt();
$pr->data($data);
$pr->output();
?>

