<?php
App::import('Vendor','receipts/acknowledgement_receipt');

$pr= new AcknowledgementReceipt($details);
$pr->template();
$pr->output();