<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 29 Sep 1990 05:00:00 GMT");
?>
<?php include_once('utility.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('elements/header.php');?>
</head>
<body ng-controller="RootController" class="" ng-init="initRoot()">
    <?php include_once('elements/wrapper.php');?>
    <?php include_once('elements/footer.php');?>
</body>
</html>