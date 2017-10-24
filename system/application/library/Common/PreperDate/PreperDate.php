<?php
/**
 *
 *検索画面の共通項目定義クラス
 *
 */

// 日付整形用ファイル読み込み
require_once "Zend/Date.php";
$dat = new Zend_Date();  

class PreperDate {

    // メンバ変数
    var $start_year;
    var $month;
    var $day;
    var $end_year;

    // 基本チェッククラス
    private $objBasicCheck = '';

    public function init(){
        /* ----- 初期設定 ----- */
        // default_ini.phpファイルのクラス定義
//        $this->objIni = new ini();
        // ini -> init() 実行(Partsファイルの読み込みとクラスのグローバル変数化)
//        $Parts = $this->objIni->init();

        // 基本チェッククラス
        $this->objBasicCheck = new BasicCheck();
        // Message
        $this->objMessage = new Message();
    }
    
    public function getPreperDate($dtDate, $stPreperStyle, $stPartItem, $stLanguage) {
        try{
            $this->init();
                switch( $stPreperStyle ){
                    // 2011 04
                    case "Y4M2":
                        $stDate = $this->getPreDateY4M2($dtDate, $stPartItem, $stLanguage);
                        break;

                    // 2011 04 18
                    case "Y4M2D2":
                        $stDate = $this->getPreDateY4M2D2($dtDate, $stPartItem, $stLanguage);
                        break;

                    // 2011 04 18 T02:07
                    case "Y4M2D2T4":
                        $stDate = $this->getPreDateY4M2D2T4($dtDate, $stPartItem, $stLanguage);
                        break;

                    // 2011 04 18 T02:07:35.0
                    case "Y4M2D2T6":
                        $stDate = $this->getPreDateY4M2D2T6($dtDate, $stPartItem, $stLanguage);
                        break;
                    // 11 28
                    case "M2D2":
                        $stDate = $this->getPreDateM2D2($dtDate, $stPartItem, $stLanguage);
                        break;
                    // default
                    default:
                        $stDate = $dtDate;
                        break;
                }

            return $stDate;

        } catch (Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }


    /**
    * 2011 05
    *
    */
    public function getPreDateY4M2($dtDate, $stPartItem, $stLanguage) {
        
            //日付生成用クラス定義
            $dat = new Zend_Date();  
            // 引数の日付をzend_dateに渡す
            $dat->set($dtDate);
            // メンバ変数初期化
            // return用のdate変数
            $dtPreDate = null;
            
            if($stLanguage == "ja"){
                if($stPartItem == "jp"){
                    // 年・月の指定
                    $stPartItemY = "年";
                    $stPartItemM = "月";
                    // フォーマット指定
                    $stPreStyles = "yyyy".$stPartItemY."MM".$stPartItemM;                    
                    // 日付生成処理
                    $dtPreDate = $dat->toString( $stPreStyles );
                } else {
                    // 2011/04
                    $stPreStyles = "yyyy".$stPartItem."MM";
                    $dtPreDate = $dat->toString($stPreStyles);
                }
            } else if( $stLanguage == "en_long" ) {
                // 04/2011
                $stPreStyles = "MMMM".$stPartItem."yyyy";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            } else if( $stLanguage == "en_short" ) {                
                // 04/2011
                $stPreStyles = "MMM".$stPartItem."yyyy";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            }

        return $dtPreDate;
    }

    /**
    * 2011 05 03
    *
    */
    public function getPreDateY4M2D2($dtDate, $stPartItem, $stLanguage) {
        
            //日付生成用クラス定義
            $dat = new Zend_Date();  
            // 引数の日付をzend_dateに渡す
            $dat->set($dtDate);
            // メンバ変数初期化
            // return用のdate変数
            $dtPreDate = null;
            
            if($stLanguage == "ja"){
                if($stPartItem == "jp"){
                    // 年・月の指定
                    $stPartItemY = "年";
                    $stPartItemM = "月";
                    $stPartItemD = "日";
                    // フォーマット指定
                    $stPreStyles = "yyyy".$stPartItemY."MM".$stPartItemM."dd".$stPartItemD;
                    // 日付生成処理
                    $dtPreDate = $dat->toString( $stPreStyles );
                } else {
                    // 2011/04
                    $stPreStyles = "yyyy".$stPartItem."MM".$stPartItem."dd";
                    $dtPreDate = $dat->toString($stPreStyles);
                }
            } else if( $stLanguage == "en_long" ) {
                // 04/2011
                $stPreStyles = "MMMM".$stPartItem."dd".$stPartItem."yyyy";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            } else if( $stLanguage == "en_short" ) {                
                // 04/2011
                $stPreStyles = "MMM".$stPartItem."dd".$stPartItem."yyyy";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            }

        return $dtPreDate;
    }


    /**
    * 2011 05 03 10:25
    *
    */
    public function getPreDateY4M2D2T4($dtDate, $stPartItem, $stLanguage) {
        
            //日付生成用クラス定義
            $dat = new Zend_Date();  
            // 引数の日付をzend_dateに渡す
            $dat->set($dtDate);
            // メンバ変数初期化
            // return用のdate変数
            $dtPreDate = null;
            // 年・月の指定
            $stPartItemY = "年";
            $stPartItemM = "月";
            $stPartItemD = "日";
            $stPartItemh = "時";
            $stPartItemmin = "分";
            
            if($stLanguage == "ja"){
                if($stPartItem == "jp"){
                    // フォーマット指定
                    $stPreStyles = "yyyy".$stPartItemY."MM".$stPartItemM."dd".$stPartItemD."HH". $stPartItemh."mm".$stPartItemmin;
                    // 日付生成処理
                    $dtPreDate = $dat->toString( $stPreStyles );
                } else {
                    // 2011/04
                    $stPreStyles = "yyyy".$stPartItem."MM".$stPartItem."dd".$stPartItem."HH".":"."mm";
                    $dtPreDate = $dat->toString($stPreStyles);
                }
            } else if( $stLanguage == "en_long" ) {
                // 04/2011
                $stPreStyles = "MMMM".$stPartItem."dd".$stPartItem."yyyy".$stPartItem."HH"."ｈ"."mm"."ｍ";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            } else if( $stLanguage == "en_short" ) {                
                // 04/2011
                $stPreStyles = "MMM".$stPartItem."dd".$stPartItem."yyyy".$stPartItem."HH". ":"."mm";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            }

        return $dtPreDate;
    }


    /**
    * 2011 05
    *
    */
    public function getPreDateY4M2D2T6($dtDate, $stPartItem, $stLanguage) {
        
            //日付生成用クラス定義
            $dat = new Zend_Date();  
            // 引数の日付をzend_dateに渡す
            $dat->set($dtDate);
            // メンバ変数初期化
            // return用のdate変数
            $dtPreDate = null;
            // 年・月の指定
            $stPartItemY = "年";
            $stPartItemM = "月";
            $stPartItemD = "日";
            $stPartItemh = "時";
            $stPartItemmin = "分";
            $stPartItemsec = "秒";
            
            if($stLanguage == "ja"){
                if($stPartItem == "jp"){
                    // フォーマット指定
                    $stPreStyles = "yyyy".$stPartItemY."MM".$stPartItemM."dd".$stPartItemD."HH". $stPartItemh."mm".$stPartItemmin."ss".$stPartItemsec;
                    // 日付生成処理
                    $dtPreDate = $dat->toString( $stPreStyles );
                } else {
                    // 2011/04
                    $stPreStyles = "yyyy".$stPartItem."MM".$stPartItem."dd".$stPartItem."HH".":"."mm".":"."ss";
                    $dtPreDate = $dat->toString($stPreStyles);
                }
            } else if( $stLanguage == "en_long" ) {
                // 04/2011
                $stPreStyles = "MMMM".$stPartItem."dd".$stPartItem."yyyy".$stPartItem."HH"."ｈ"."mm"."ｍ"."ss"."ｓ";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            } else if( $stLanguage == "en_short" ) {                
                // 04/2011
                $stPreStyles = "MMM".$stPartItem."dd".$stPartItem."yyyy".$stPartItem."HH". ":"."mm".":"."ss";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            }

        return $dtPreDate;
    }

    public function getPreDateM2D2($dtDate, $stPartItem, $stLanguage) {
        
            //日付生成用クラス定義
            $dat = new Zend_Date();  
            // 引数の日付をzend_dateに渡す
            $dat->set($dtDate);
            // メンバ変数初期化
            // return用のdate変数
            $dtPreDate = null;
            
            if($stLanguage == "ja"){
                if($stPartItem == "jp"){
                    // 年・月の指定
                    $stPartItemM = "月";
                    $stPartItemD = "日";
                    // フォーマット指定
                    $stPreStyles = "MM".$stPartItemM."dd".$stPartItemD;
                    // 日付生成処理
                    $dtPreDate = $dat->toString( $stPreStyles );
                } else {
                    // 2011/04
                    $stPreStyles = "MM".$stPartItem."dd";
                    $dtPreDate = $dat->toString($stPreStyles);
                }
            } else if( $stLanguage == "en_long" ) {
                // 04/2011
                $stPreStyles = "MMMM".$stPartItem."dd".$stPartItem."yyyy";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            } else if( $stLanguage == "en_short" ) {                
                // 04/2011
                $stPreStyles = "MMM".$stPartItem."dd".$stPartItem."yyyy";
                $dtPreDate = $dat->toString($stPreStyles, "en_US");
            }

        return $dtPreDate;
    }




    // コンストラクタ
    function SC_Date($start_year='', $end_year='') {
        if ( $start_year )  $this->setStartYear($start_year);
        if ( $end_year )    $this->setEndYear($end_year);
    }

    function setStartYear($year){
        $this->start_year = $year;
    }

    function getStartYear(){
        return $this->start_year;
    }

    function setEndYear($endYear) {
        $this->end_year = $endYear;
    }

    function getEndYear() {
        return $this->end_year;
    }

    function setMonth($month){
        $this->month = $month;
    }

    function setDay ($day){
        $this->day = $day;
    }


    // 年の一覧を取得する
    function getYear($year = '', $default = ''){
        if ( $year ) $this->setStartYear($year);

        $year = $this->start_year;
        if ( ! $year ) $year = DATE("Y");

        $end_year = $this->end_year;
        if ( ! $end_year ) $end_year = (DATE("Y") + 3);

        $year_array = array();

        for ($i=$year; $i<=($end_year); $i++){
                $year_array[$year] = $i;

                if($year == $default) {
                    $year_array['----'] = "----";
                }
            $year++;
        }
        return $year_array;
    }

    //
    function getZeroYear($year = ''){
        if ( $year ) $this->setStartYear($year);

        $year = $this->start_year;
        if ( ! $year ) $year = DATE("Y");

        $end_year = $this->end_year;
        if ( ! $end_year ) $end_year = (DATE("Y") + 3);

        $year_array = array();

        for ($i=$year; $i<=($end_year); $i++){
            $key = substr($i, -2);
            $year_array[$key] = $key;
        }
        return $year_array;
    }


    //
    function getZeroMonth(){
        $month_array = array();
        for ($i=1; $i <= 12; $i++){
            $val = sprintf("%02d", $i);
            $month_array[$val] = $val;
        }
        return $month_array;
    }

    function getZeroDay(){
        $day_array = array();
        for ($i=1; $i <= 31; $i++){
            $val = sprintf("%02d", $i);
            $day_array[$val] = $val;
        }
        return $day_array;
    }

    // 月の一覧を取得
    function getMonth(){
        $month_array = array();
        for ($i=0; $i < 12; $i++){
            $month_array[$i + 1 ] = $i + 1;
        }
        return $month_array;
    }


    // 日の一覧を取得
    function getDay(){
        $day_array = array();
        for ($i=0; $i < 31; $i++){
            $day_array[ $i + 1 ] = $i + 1;
        }
        return $day_array;
    }


    // 時間の一覧を取得
    function getHour(){
        $day_array = array();
        for ($i=0; $i<=23; $i++){
            $hour_array[$i] = $i;
        }

        return $hour_array;
    }
    
    // 時間の一覧を取得
    function getZeroHour(){
        $hour_array = array();
        for ($i=0; $i<=23; $i++){
            $val = sprintf("%02d", $i);
            $hour_array[$val] = $val;
        }

        return $hour_array;
    }
    
    // 分の一覧を取得
    function getMinutes(){
        $minutes_array = array();
        for ($i=0; $i<=59; $i++){
            $minutes_array[$i] = $i;
        }

        return $minutes_array;
    }
    
    function getZeroMinutes(){
        $minutes_array = array();
        for ($i=0; $i<=59; $i++){
            $val = sprintf("%02d", $i);
            $minutes_array[$val] = $val;
        }

        return $minutes_array;
    }

    function getMinutesInterval(){
        $minutes_array = array("00"=>"00", "30"=>"30");
        return $minutes_array;
    }
    
    function parseDatetime($stDate, $bTime = false) {
        try {
            $this->init();
            // 開始年月日
            // 2014/06/30 年月日の区切り文字として / も許容するように変更
//            preg_match("|[0-9]+\-[0-9]+\-[0-9]+|", $stDate, $match);
            preg_match("/([0-9]+)[\-|\/]([0-9]+)[\-|\/]([0-9]+).*/", $stDate, $match);
//            $arrDate = explode("-", $match[0]);
//            $arrDatetime["Year"] = $arrDate[0];
//            $arrDatetime["Month"] = $arrDate[1];
//            $arrDatetime["Day"] = $arrDate[2];
            $arrDatetime["Year"] = $match[1];
            $arrDatetime["Month"] = $match[2];
            $arrDatetime["Day"] = $match[3];
            
            // 開始時刻
            if($bTime) {
                preg_match("|[0-9]+\:[0-9]+\:[0-9]+|", $stDate, $match2);
                if (empty($match2)) {
                    preg_match("|[0-9]+\:[0-9]+|", $stDate, $match2);
                }
                $arrTime = explode(":", $match2[0]);
                $arrDatetime["Hour"] = $arrTime[0];
                $arrDatetime["Min"] = $arrTime[1];
//                $arrDatetime["Sec"] = $arrTime[2];
                $arrDatetime["Sec"] = (!empty($arrTime[2]) ? $arrTime[2] : '00');
                // 日付形式チェック
                //if(!checkdate($arrDate[1], $arrDate[2], $arrDate[0])) {
                //    throw new Zend_Exception('$stDateに正しい日付が渡されていません。');
                //}
            }
            
            return $arrDatetime;
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    // 2つの日付の差分を取得
    function getCompareDate($stEndDate, $stStartDate) {
        // 開始年月日
        $arrStartDatetime = $this->parseDatetime($stStartDate);
        // 終了年月日
        $arrEndDatetime = $this->parseDatetime($stEndDate);
        
        // 日付差分
        $stStart = mktime(0, 0, 0, $arrStartDatetime["Month"], $arrStartDatetime["Day"], $arrStartDatetime["Year"]);
        $stEnd = mktime(0, 0, 0, $arrEndDatetime["Month"], $arrEndDatetime["Day"], $arrEndDatetime["Year"]);
        $diff = $stStart - $stEnd;
        $diffDay = $diff / 86400;//1日は86400秒
        return $diffDay;
    }
    
    public function getDayOfTheWeek($stDate) {
        $this->init();
        $arrDatetime = $this->parseDatetime($stDate);
        $arrWeek = $this->objIni->DayOfTheWeek;
        $iDayOfTheWeek = date("w", mktime(0, 0, 0, $arrDatetime["Month"], $arrDatetime["Day"], $arrDatetime["Year"]));
        
        return $arrWeek[$iDayOfTheWeek];
    }
    
    function getMonthEndDay($stYear, $stMonth) {
        //mktime関数で日付を0にすると前月の末日を指定したことになります
        //$month + 1 をしていますが、結果13月のような値になっても自動で補正されます
        $stDate = mktime(0, 0, 0, $stMonth + 1, 0, $stYear);
        return date("d", $stDate);
    }
    
    function execComputeDate($stDate, $iAddDays, $bTime = false) {
        $this->init();
        $arrBaseDate = $this->parseDatetime($stDate);
        $iBaseSec = mktime(0, 0, 0, $arrBaseDate["Month"], $arrBaseDate["Day"], $arrBaseDate["Year"]);
        $iAddSec = $iAddDays * 86400;
        $iTargetSec = $iBaseSec + $iAddSec;
        if($bTime) {
            return date("Y-m-d H:i:s", $iTargetSec);
        } else {
            return date("Y-m-d", $iTargetSec);
        }
    }
    
    function execComputePreDate($stDate, $iDays, $bTime = false) {
        $this->init();
        $arrBaseDate = $this->parseDatetime($stDate);
        $iBaseSec = mktime(0, 0, 0, $arrBaseDate["Month"], $arrBaseDate["Day"], $arrBaseDate["Year"]);
        $iSec = $iDays * 86400;
        $iTargetSec = $iBaseSec - $iSec;
        if($bTime) {
            return date("Y-m-d H:i:s", $iTargetSec);
        } else {
            return date("Y-m-d", $iTargetSec);
        }
    }
    
    function execComputeMonth($stDate, $iAddMonths) {
        $this->init();
        $arrBaseDate = $this->parseDatetime($stDate);
        $arrBaseDate["Month"] += $iAddMonths;
        $dtEndDay = $this->getMonthEndDay($arrBaseDate["Year"], $arrBaseDate["Month"]);//ここで、前述した月末日を求める関数を使用します
        if($arrBaseDate["Day"] > $dtEndDay) $arrBaseDate["Day"] = $dtEndDay;
        $dtDate = mktime(0, 0, 0, $arrBaseDate["Month"], $arrBaseDate["Day"], $arrBaseDate["Year"]);//正規化
        return date("Y-m-d", $dtDate);
    }
    
    function execComputePreMonth($stDate, $iMonth) {
        $this->init();
        $arrBaseDate = $this->parseDatetime($stDate);
        $arrBaseDate["Month"] -= $iMonth;
        $dtEndDay = $this->getMonthEndDay($arrBaseDate["Year"], $arrBaseDate["Month"]);//ここで、前述した月末日を求める関数を使用します
        if($arrBaseDate["Day"] > $dtEndDay) $arrBaseDate["Day"] = $dtEndDay;
        $dtDate = mktime(0, 0, 0, $arrBaseDate["Month"], $arrBaseDate["Day"], $arrBaseDate["Year"]);//正規化
        return date("Y-m-d", $dtDate);
    }
    
    function execComputeMin($stDate, $iAddMin) {
        $this->init();
        $arrBaseDate = $this->parseDatetime($stDate, $bTime = true);
        $arrBaseDate["Min"] += $iAddMin;
        $dtEndDay = $this->getMonthEndDay($arrBaseDate["Year"], $arrBaseDate["Month"]);//ここで、前述した月末日を求める関数を使用します
        if($arrBaseDate["Day"] > $dtEndDay) $arrBaseDate["Day"] = $dtEndDay;
        $dtDate = mktime($arrBaseDate["Hour"], $arrBaseDate["Min"], $arrBaseDate["Sec"], $arrBaseDate["Month"], $arrBaseDate["Day"], $arrBaseDate["Year"]);//正規化
        return date("Y-m-d H:i:s", $dtDate);
    }
    
    function execComputePreMin($stDate, $iMin) {
        $this->init();
        $arrBaseDate = $this->parseDatetime($stDate, $bTime = true);
        $arrBaseDate["Min"] -= $iMin;
        $dtEndDay = $this->getMonthEndDay($arrBaseDate["Year"], $arrBaseDate["Month"]);//ここで、前述した月末日を求める関数を使用します
        if($arrBaseDate["Day"] > $dtEndDay) $arrBaseDate["Day"] = $dtEndDay;
        $dtDate = mktime($arrBaseDate["Hour"], $arrBaseDate["Min"], $arrBaseDate["Sec"], $arrBaseDate["Month"], $arrBaseDate["Day"], $arrBaseDate["Year"]);//正規化
        return date("Y-m-d H:i:s", $dtDate);
    }
    
    function getCurrentDatetime() {
        $Datetime = date("Y-m-d H:i:s");
        return $Datetime;
    }
    
    // 指定した期間内の日付を配列にして返す
    function makeDate($dtStart, $dtEnd) {
        // UNIXタイムスタンプ取得
        $arrStart = $this->parseDatetime($dtStart);
        $arrEnd = $this->parseDatetime($dtEnd);
        $su = mktime(0, 0, 0, $arrStart["Month"], $arrStart["Day"], $arrStart["Year"]);
        $eu = mktime(0, 0, 0, $arrEnd["Month"], $arrEnd["Day"], $arrEnd["Year"]);
        // 1日の秒数
        $sec = 60 * 60 * 24;// 60秒 × 60分 × 24時間
        // 日付取得
        $key = 0;
        for( $i = $su; $i <= $eu; $i += $sec ) {
            $arrDate[$key] = date("Y-m-d H:i:s", $i);
            $key ++;
        }
        
        return $arrDate;
    }
    
    function getJapaneseDatetime($dtDate, $bTime = false) {
        $arrDate = $this->parseDatetime($dtDate, $bTime);
        $stDate = $arrDate["Year"] . "年" . $arrDate["Month"] . "月" . $arrDate["Day"] . "日";
        if($bTime) {
            $stDate .= " " . $arrDate["Hour"] . "時" . $arrDate["Min"] . "分" . $arrDate["Sec"] . "秒";
        }
        return $stDate;
    }
    
    function getStandardDatetime($dtDate, $bTime = false) {
        $arrDate = $this->parseDatetime($dtDate, $bTime);
        $stDate = $arrDate["Year"] . "/" . $arrDate["Month"] . "/" . $arrDate["Day"] . " ";
        if($bTime) {
            $stDate .= $arrDate["Hour"] . ":" . $arrDate["Min"] . ":" . $arrDate["Sec"] . ":";
        }
        return $stDate;
    }
    
    function makeTimestamp($arrDate, $bTime = false) {
        $this->init();
        try {
            if($this->objBasicCheck->isSetArray($arrDate)) {
                $stDate = $arrDate["Year"] . "-" . $arrDate["Month"] . "-" . $arrDate["Day"] . " ";
            } else {
                throw new Zend_Exception('$arrDateが空です。');
            }
            
            if($bTime) {
                $stDate .= $arrDate["Hour"] . ":" . $arrDate["Min"] . ":" . $arrDate["Sec"];
            } else {
                $stDate .= "00:00:00";
            }
            
            return $stDate;
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    function getUnixTime($dtDate, $bTime = false) {
        $this->init();
        $arrDate = $this->parseDatetime($dtDate, $bTime);
        $iUnixTime = mktime($arrDate["Hour"], $arrDate["Min"], $arrDate["Sec"], $arrDate["Month"], $arrDate["Day"], $arrDate["Year"]);
        return $iUnixTime;
    }
    
    
    
}
