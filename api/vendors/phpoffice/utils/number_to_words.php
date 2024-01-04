<?php 
class NumberToWordsConverter
{
    private static $aTens = [ "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
    private static $aOnes = [ "Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine",
        "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", 
        "Nineteen" ];

    public static function convertToHundreds($num)
    {
        $cWords = "";
        $num %= 1000;
        if ($num > 99) {
            /* Hundreds. */
            $cNum = strval($num);
            $nNum = intval($cNum[0]);
            $cWords .= self::$aOnes[$nNum] . " Hundred";
            $num %= 100;
            if ($num > 0)
                $cWords .= " and ";
        }

        if ($num > 19) {
            /* Tens. */
            $cNum = strval($num);
            $nNum = intval($cNum[0]);
            $cWords .= self::$aTens[$nNum - 2];
            $num %= 10;
            if ($num > 0)
                $cWords .= "-";
        }
        if ($num > 0) {
            /* Ones and teens. */
            $nNum = floor($num);
            $cWords .= self::$aOnes[$nNum];
        }
        return $cWords;
    }

   public static function amountToWords($num)
{
    $aUnits = [ "Thousand", "Million", "Billion", "Trillion", "Quadrillion" ];
    
    $cWords = ($num >= 1 && $num < 2) ? "Peso " : "Pesos ";
    $nLeft = floor($num);
    $hasCentavos = false;
    
    for ($i = 0; $nLeft > 0; $i++) { 
        if ($nLeft % 1000 > 0) {
            if ($i != 0)
                $cWords = self::convertToHundreds($nLeft) . " " . $aUnits[$i - 1] . " " . $cWords;
            else
                $cWords = self::convertToHundreds($nLeft) . " " . $cWords;
        }
        $nLeft = floor($nLeft / 1000);
    }
    
    $num = round($num * 100) % 100;
    
    if ($num > 0) {
        $cWords .= "and ";
        if ($num != 1)
            $cWords .= self::convertToHundreds($num) . " Centavos";
        else
            $cWords .= "One Centavo";
        $hasCentavos = true;
    }
    
   
    
    return $cWords;
}


}
