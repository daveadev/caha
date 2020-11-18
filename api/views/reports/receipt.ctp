<?php
App::import('Vendor','receipts/receipt');

//pr($data);exit;


$pr= new OfficialReceipt();
$pr->receipt();
$pr->data($data);
$pr->output();
?>

