<?php
App::import('Vendor','daily_remittance');
pr($data);exit;

$pr= new DailyRemittance();
$pr->hdr($data['date']);
$pr->booklet($data['booklet']);
$pr->cash_breakdown($data['breakdown']);

$pr->output();
?>

