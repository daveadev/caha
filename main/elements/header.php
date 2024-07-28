<?php 
    $versionNo = Utility::getVersionNo();
    $description = Utility::$versionData['DESCRIPTION'];
    $author = Utility::$versionData['AUTHOR'];
    $product = Utility::$versionData['PRODUCT'];
    $alias = Utility::$versionData['ALIAS'];
    $title = sprintf("%s(%s)",$product,$alias);
    $description = Utility::$versionData['DESCRIPTION'];
    $copyright =sprintf("Copyright © %s,%s", Utility::$versionData['COPYRIGHT'],$product);
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="title" content="<?php echo $title; ?>" />
<meta name="application-name" content="<?php echo $product; ?>" />
<meta name="application-alias" content="<?php echo $alias; ?>" />
<meta name="description" content="<?php echo $description; ?>" />
<meta name="author" content="<?php echo $author; ?>" />
<meta name="version" content="<?php echo $versionNo; ?>"/>
<meta name="copyright" content="<?php echo $copyright; ?>"/>

<title><?php echo $title; ?></title>
 
 <!-- Global site tag (gtag.js) - Google Analytics -->
<!--   
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-133157539-4"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-133157539-4');
</script>
-->
<!-- Custom CSS -->
<?php 
    // Load css from an array using a foreach loop
    $cssFiles = array(
    'app/assets/css/bootstrap.min.css',
    'app/assets/css/simple-uix.css',
    'app/assets/css/animate.css',
    'app/assets/css/custom-window.css',
    'app/vendors/atomic_design/css/style.css'
    );
foreach($cssFiles as $fileName)
    echo Utility::loadStatic('css',$fileName);
?>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script data-main="config/main.js?<?php echo $versionNo; ?>" src="app/bower_components/requirejs/require.js?<?php echo $versionNo; ?>"> </script>