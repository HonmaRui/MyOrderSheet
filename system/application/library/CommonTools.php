<?php
/**
 * アプリケーション共通で利用する関数用クラス
 *
 * @category   ECSite
 * @package    Library
 * @subpackage CommonTools
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     鈴木
 * @version    v1.0
 */

class CommonTools {
    
    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
    }
    
    /**
     * 初期化処理
     *
     * @access public
     * @return void
     */
    public function init() {
        // initialize
    }
    
    /**
     * フォームデータのシリアライズ処理
     *
     * @access public
     * @param  array $arrForm      フォームデータを格納した配列
     * @return string
     */
    public function getSerializeFormData($arrForm) {
        $stForm = base64_encode(serialize($arrForm));
        
        return $stForm;
    }
    
    /**
     * フォームデータのアンシリアライズ処理
     *
     * @access public
     * @param  string $stForm      フォームデータを格納した文字列
     * @return array
     */
    public function getUnserializeFormData($stForm) {
        //$arrResult = unserialize(base64_decode($stForm));
        // POSTによるBase64の「+」記号が勝手にスペースに変換されてしまう対応
        $arrResult = unserialize(base64_decode(str_replace(' ', '+', $stForm)));
        
        return $arrResult;
    }
    
    /**
     * フォームデータから指定テーブルのデータのみ抽出処理
     *
     * @access public
     * @param  array    $arrTableName 抽出するテーブル名
     * @param  array    $arrForm      フォームデータを格納した配列
     * @return array
     */
    public function getExtractTableData($arrTableName, $arrForm) {
        $arrExtractTableData = array();
        
        try {
            
            foreach ($arrTableName as $stTableName) {
                $iStrLen = strlen($stTableName) + 1;

                if(is_array($arrForm)) {
                    foreach($arrForm as $key => $val ){
                        if(preg_match('/^'. $stTableName .'_(.*)$/', $key)) {
                            $stColumnNameInitial = substr($key, $iStrLen, 1);
                            if (ctype_upper($stColumnNameInitial)) {
                                $arrExtractTableData[$key] = $val;
                            }
                        }
                    }
                }
            }
            
            return $arrExtractTableData;
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * token生成処理
     *
     * @access public
     * @param  string   $stSalt             ソルト
     * @return string
     */
    public function generateTokenData($stSalt) {

        try {
            // salt + 固定値（ドメイン名） + セッションID
            $stToken = sha1($stSalt.DOMAIN.session_id());

            return $stToken;
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * token生成+Postされたtokenとのチェック処理
     * tokenが合致すればTRUEを返し、合致しなければエラーをスローする。
     *
     * @access public
     * @param  string   $stSalt             ソルト
     * @param  string   $stPostToken        POSTされたtoken
     * @return boolean
     */
    public function checkTokenData($stSalt, $stPostToken) {

        try {
            // salt + 固定値（ドメイン名） + セッションID
            $stToken = sha1($stSalt.DOMAIN.session_id());
            if ($stToken !== $stPostToken) {
                throw new Zend_Exception('tokenが正しくありません。もう一度処理をやり直してください。');
            } else {
                $bReturn = true;
            }

            return $bReturn;
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 文字列定数から配列データを作成する処理
     *
     * @access public
     * @param  integer  $iRowCount      取得行数
     * @param  string   $stConstName    定数文字列（'key|value'）の定数名（最後の_以降の数値は除く）
     * @return array
     */
    public function setConstStringArray($iRowCount, $stConstName) {

        $arrReturn = array();
        
        try {
            for ($iLoop = 1; $iLoop <= $iRowCount; $iLoop++) {
                $arrConstExplode = explode("|",constant($stConstName.$iLoop));
                $arrReturn[$arrConstExplode[0]] = $arrConstExplode[1] ;
            }

            return $arrReturn;
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 文字列定数から配列データを作成し、指定されたkeyのvalueを返す
     *
     * @access public
     * @param  integer  $iKey           取得したいKey
     * @param  integer  $iRowCount      取得行数
     * @param  string   $stConstName    定数文字列（'key|value'）の定数名（最後の_以降の数値は除く）
     * @return string
     */
    public function getConstStringName($iKey, $iRowCount, $stConstName) {

        $stReturnConstName = '';
        $arrConst = array();
        
        try {
            $arrConst = self::setConstStringArray($iRowCount, $stConstName);

            if (array_key_exists($iKey,$arrConst)) {
                $stReturnConstName = $arrConst[$iKey] ;
            }
        
            return $stReturnConstName;
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 定数文字列（'key|value'）から、keyを返す
     *
     * @access public
     * @param  string   $stConstName    定数文字列（'key|value'）の定数名
     * @return integer
     */
    public function getConstStringKey($stConstName) {

        $stReturnConstKey = 0;
        
        try {
            $arrConstExplode = explode("|", $stConstName);
            $stReturnConstKey = $arrConstExplode[0];
        
            return intval($stReturnConstKey);
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 定数文字列（'key|value'）から、valueを返す
     *
     * @access public
     * @param  string   $stConstName    定数文字列（'key|value'）の定数名
     * @return integer
     */
    public function getConstStringValue($stConstName) {

        $stReturnConstKey = 0;
        
        try {
            $arrConstExplode = explode("|", $stConstName);
            $stReturnConstKey = $arrConstExplode[1];
        
            return $stReturnConstKey;
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 配列制御（DBから取得した2項目ある添字配列を連想配列にする）
     * フォームのSELECTやラジオボタン用に配列を編集する
     *
     * @access public
     * @param  array        $arrData        DBから取得した2項目ある配列
     * @param  boolean      $appendFlag     DBから取得した1項目と2項目を連結して連想配列にするかどうかフラグ
     * @return array
     */
    public function changeDbArrayForFormTag($arrData, $addFlag = false) {

        $arrReturn = array();
        
        try {
            if (is_array($arrData) && count($arrData) > 0) {
                foreach($arrData as $ikey => $arrValue) {
                    $arrTemp =array();
                    foreach($arrValue as $key => $value) {
                        $arrTemp[] = $value;
                    }
                    if ($addFlag == false) {
                        $arrReturn[$arrTemp[0]] = $arrTemp[1];
                    } else {
                        $arrReturn[$arrTemp[0]] = $arrTemp[0] . '／' . $arrTemp[1];
                    }
                }
            }
        
            return $arrReturn;
            
        } catch(Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
    * 文字列の文字数を全角2バイト、半角1バイトとしてカウントする
    *
    * @param   string     $str_text    バイト数をカウントする対象文字列
    * @param   string     $encode      文字エンコーディング
    * @return  integer    文字のバイト数
    */
    public function get_string_length($str_text , $encode='UTF-8')
    {
        $ret = 0;
        $count = mb_strwidth($str_text, $encode);
        // $count = mb_strlen($str_text, $encode);

        for ($i=0; $i<$count; $i++) {
            $s = substr($str_text, $i, 1);
            //  $s = mb_substr($str_text, $i, 1)
            $l = strlen(bin2hex($s)) / 2;
            if ($l==1) {
                $ret++;
            } else {
                $ret = $ret + 2;
            }
        }

        return $ret;
    }
    
    /**
    * 配列の任意の位置へ要素を挿入し、挿入後の配列を返す
    *
    * @param   array     $arrArray    挿入される配列
    * @param   string    $stInsert    挿入する値
    * @param   int       $iPos        挿入位置（先頭は0）
    * @return  array     $arrArray    成功した場合挿入後の配列、そうでない場合、空文字列を返す
    */    
    function arrayInsert($arrArray, $stInsert, $iPos) {
        // 引数$arrayが配列でない場合は空文字列を返す
        if (!is_array($arrArray)) {
            return "";
        }

        // 挿入する位置～末尾まで
        $arrLast = array_splice($arrArray, $iPos);
        
        // 先頭～挿入前位置までの配列に、挿入する値を追加
        
        array_push($arrArray, $stInsert);
        
        // 配列を結合
        $arrArray = array_merge($arrArray, $arrLast);
        
        return $arrArray;
    }    

    /**
    * 連想配列の任意の位置へ要素を挿入し、挿入後の連想配列を返す
    *
    * @param   array     $arrArray     挿入される連想配列
    * @param   string    $stInsert     挿入する値
    * @param   int       $iPos         挿入位置（先頭は0）
    * @param   string    $stKeyName    挿入キー名
    * @return  array     $arrArray     成功した場合挿入後の連想配列、そうでない場合、空文字列を返す
    */    
    function hashInsert($arrArray, $stInsert, $iPos, $stKeyName) {
        // 引数$arrayが配列でない場合は空文字列を返す
        if (!is_array($arrArray)) {
            return "";
        }

        // 挿入する位置～末尾まで
        $arrLast = array_splice($arrArray, $iPos);
        
        // 先頭～挿入前位置までの配列に、挿入する値を追加
        $arrArray[$stKeyName] = $stInsert;
        
        // 配列を結合
        $arrArray = array_merge($arrArray, $arrLast);
        
        return $arrArray;
    }        
}