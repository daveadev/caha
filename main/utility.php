<?php

class Utility {
    public static $versionData = null;

    public static function getVersionInfo() {
        if (self::$versionData !== null) {
            return self::$versionData;
        }

        // Construct the path to the version.info file
        $versionFilePath = dirname(__FILE__) . '/../version/version.info';
        $versionInfo = file_get_contents($versionFilePath);

        if ($versionInfo === false) {
            return "Error: Unable to read version file.";
        }

        $versionLines = explode("\n", $versionInfo);
        self::$versionData = [];
        foreach ($versionLines as $line) {
            $lineParts = explode('=', $line, 2);
            if (count($lineParts) == 2) {
                self::$versionData[trim($lineParts[0])] = htmlspecialchars(trim($lineParts[1]));
            }
        }

        return self::$versionData;
    }
    public static function getVersionNo(){
        Utility::getVersionInfo();
        $basePath = strtolower(basename(dirname(dirname(dirname(__FILE__)))));
        if($basePath=='htdocs') $basePath = 'local';
        $versionNo = sprintf("v%s.%s.%s",self::$versionData['MAJOR'],self::$versionData['MINOR'],self::$versionData['PATCH']);
        $versionNo = htmlspecialchars($versionNo);
    
        return $versionNo;
    }
    public static function loadStatic($type, $name) {
        Utility::getVersionInfo();
        $versionInfo = self::$versionData;
        if (!is_array($versionInfo)) {
            return; // Handle error or do nothing
        }

        $versionString = $versionInfo['MAJOR'] . '.' . $versionInfo['MINOR'] . '.' . $versionInfo['PATCH'];
        $filePath = "$name?v=$versionString";

        if ($type === 'css') {
            echo "<link rel='stylesheet' type='text/css' href='$filePath'>";
        }
        // Add more conditions for other file types if necessary
    }
}
?>
