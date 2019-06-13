<?php
 
$strAccessToken = "Ce9xPpmJB6TPrGx9BU6wHQCvehuo62ueuEVENuGntzDlMzocz937YZIuIu3Xutc0amDzj1zGgcZdQhxr6raeIDKyTCYrY0D47U4mXZ+4C2z+y1E/2gfnl1lWoLE8xkMaz6cJiPb+VZ51BwD+jkZUUwdB04t89/1O/w1cDnyilFU=";
 
$content = file_get_contents('php://input');
$arrJson = json_decode($content, true);
 
$strUrl = "https://api.line.me/v2/bot/message/reply";
 
$arrHeader = array();
$arrHeader[] = "Content-Type: application/json";
$arrHeader[] = "Authorization: Bearer {$strAccessToken}";
 
$numInput = $arrJson['events'][0]['message']['text'];

# The 24 Game Solver Python Version
# @author Xingfan Xia
$PRECISION = 1E-6;
$COUNT_OF_NUMBER = 4;
$NUMBER_TO_BE_CAL = 24;
$g_expression = [];
$g_number = [];
#input
//$numInput = "5 6 7 8";
#initialization
$g_expression = explode(" ",$numInput);
for($k = 0;$k<count($g_expression);$k++){
    $g_number[$k] = 0;
}
$COUNT_OF_NUMBER = count($g_expression);
$msg = "";
//print_r($g_expression);
//print_r($g_number);
for ($i = 0;$i < count($g_expression);$i++){
    $g_number[$i] = (int)$g_expression[$i];
}
    
#recursive solver
function solve($n,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression) {
    if(1 == $n){
        //echo abs($NUMBER_TO_BE_CAL - $g_number[0]) < $PRECISION;
        if(abs($NUMBER_TO_BE_CAL - $g_number[0]) < $PRECISION){
            $strAccessToken = "Ce9xPpmJB6TPrGx9BU6wHQCvehuo62ueuEVENuGntzDlMzocz937YZIuIu3Xutc0amDzj1zGgcZdQhxr6raeIDKyTCYrY0D47U4mXZ+4C2z+y1E/2gfnl1lWoLE8xkMaz6cJiPb+VZ51BwD+jkZUUwdB04t89/1O/w1cDnyilFU=";
 
            $content = file_get_contents('php://input');
            $arrJson = json_decode($content, true);
 
            $strUrl = "https://api.line.me/v2/bot/message/reply";
 
            $arrHeader = array();
            $arrHeader[] = "Content-Type: application/json";
            $arrHeader[] = "Authorization: Bearer {$strAccessToken}";
            $msg = "คำตอบ : " . $g_expression[0] . " = 24";
            $arrPostData = array();
            $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
            $arrPostData['messages'][0]['type'] = "text";
            $arrPostData['messages'][0]['text'] = $msg;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$strUrl);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
curl_close ($ch);
            return True;
        }
        else{
            return False;
        }
    }
    else{
        for($i=0;$i<$n;$i++){
            for($j=$i+1;$j<$n;$j++){
                //echo $n;
                //echo "<br>";
                $a = $g_number[$i];
                $b = $g_number[$j];
                #**********************************
                #   Move the meaingful forward
                #   answer saved in [i]
                #   number[j]can just be overwritten by the last number
                #   *******************************
                $g_number[$j] = $g_number[$n - 1];
                $expa = $g_expression[$i];
                $expb = $g_expression[$j];
                $g_expression[$j] = $g_expression[$n - 1];
                # cal a+b
                $g_expression[$i] = '(' . $expa . '+' . $expb . ')';
                $g_number[$i] = $a + $b;
                if ( solve($n - 1,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression) ) {
                    return True;
                }
                # cal a-b
                $g_expression[$i] = '(' . $expa . '-' . $expb . ')';
                $g_number[$i] = $a - $b;
                if ( solve($n - 1,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression) ) {
                    return True;
                }
                # cal b-a
                $g_expression[$i] = '(' . $expb . '-' . $expa . ')';
                $g_number[$i] = $b - $a;
                if ( solve($n - 1,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression) ){
                    return True;
                }
                # cal (a*b)
                $g_expression[$i] = '(' . $expa . '*' . $expb . ')';
                $g_number[$i] = $a * $b;
                if ( solve($n - 1,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression) ){
                    return True;
                }
                # cal (a/b)
                if ($b != 0) {
                    $g_expression[$i] = '(' . $expa . '/' . $expb . ')';
                    $g_number[$i] = $a / $b;
                    if ( solve($n - 1,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression) ) {
                        return True;
                    }
                # cal (b/a)
                    if ($a != 0) {
                        $g_expression[$i] = '(' . $expb . '/' . $expa . ')';
                        $g_number[$i] = $b / $a;
                        if ( solve($n - 1,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression) ){
                            return True;
                        }
                    }
                }
                 # resume and recursion
                $g_number[$i] = $a;
                $g_number[$j] = $b;
                $g_expression[$i] = $expa;
                $g_expression[$j] = $expb;
            }
        }
        return False;
    }

}
    
#main
if(!solve($COUNT_OF_NUMBER,$NUMBER_TO_BE_CAL,$g_number,$PRECISION,$g_expression)){
   $arrPostData = array();
   $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
   $arrPostData['messages'][0]['type'] = "text";
   $arrPostData['messages'][0]['text'] = "ไม่มีคำตอบ";
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL,$strUrl);
   curl_setopt($ch, CURLOPT_HEADER, false);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   $result = curl_exec($ch);
   curl_close ($ch);
}
 
?>
