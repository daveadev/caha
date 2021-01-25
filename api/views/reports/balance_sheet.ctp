<?php
App::import('Vendor','accounting/balance_sheet');

$pr= new BalanceSheet();
$pr->hdr();
$pr->output();
?>

