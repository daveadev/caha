<?php
App::import('Vendor','receipts/receipt');

$pr= new OfficialReceipt($details);
$pr->receipt();
$pr->data($or_details);
$pr->output();