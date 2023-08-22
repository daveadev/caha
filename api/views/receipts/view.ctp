<?php
App::import('Vendor','receipts/receipt');


$pr= new OfficialReceipt();
$pr->receipt();
$pr->data($data);
$pr->output();
?>

