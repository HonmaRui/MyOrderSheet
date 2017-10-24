<?php
/**
 *
 * 渡された値を評価し、結果を返す関数群
 * 
 *
 *
 */
class BasicCheck {
    function __construct() {
        $this->objMessage = new Message();
        $this->objFormat = new Format();
    }
    
    // $stVarが1以上の数値であるか判定する
    function isSetInt($stVar) {
        if(is_numeric($stVar)) {
            return true;
        } else {
            return false;
        }
    }
    
    // $stVarが1以上の数値であるか判定する
    function isSetNumber($stVar) {
        if($stVar > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // $stVarが1以上の数値であるか判定する
    function isSetID($stVar) {
        if($stVar > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // $stVarが1文字以上の文字列であるか判定する
    function isSetStrings($stVar) {
        if(strlen($stVar) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // $arrayの要素が1以上存在するか判定する
    function isSetArray($array) {
        if(is_array($array) || is_object($array)) {
            $array = (array) $array;
            if(count($array) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    // $arrayに値の入った要素があるか判断し、結果を返す
    function countValueFromArray($array) {
        if(is_array($array) || is_object($array)) {
            $iCount = 0;
            $array = (array) $array;
            foreach($array as $key => $value) {
                if(!empty($value)) {
                    $iCount++;
                }
            }
            return $iCount;
        } else {
            return false;
        }
    }
    
    // 文字列がEmailの形式がどうか判定する
    function isSetEmail($stEmail) {
        $stEmail = str_replace('+', '', $stEmail);
        if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $stEmail)) {
            return true;
        } else {
            return false;
        }
    }
    
    // $stUrlが正常なURLであるか判定する
    function isSetUrl($stUrl) {
        if(preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $stUrl)) {
            return true;
        } else {
            return false;
        }
    }
    
    // $stFilePathで指定された場所にファイルが存在するか判定する
    function isExistFile($stFilePath) {
        if(file_exists($stFilePath)) {
            return true;
        } else {
            return false;
        }
    }
    
    // $stUniqueIDが1文字以上の文字列であるか判定する
    function isSetUniqueID($stUniqueID) {
        if(strlen($stUniqueID) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // $stPrevUrlが1文字以上の文字列であるか判定する
    function isSetPrevUrl($stPrevUrl) {
        if(strlen($stPrevUrl) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function isSetDate($stDate) {
        if(!is_null($stDate)) {
            $arrDate = $this->objFormat->parseDate($stDate);
            if(is_array($arrDate)) {
                if(checkdate($arrDate["Month"],$arrDate["Day"],$arrDate["Year"])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}