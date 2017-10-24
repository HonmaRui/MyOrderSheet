<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     鈴木
 * @version    v1.0
 */
 
require_once(realpath(dirname( __FILE__)) . "/Validate.require.php");

class Validate {
    
    /**
     * @var array
     */
    protected $_arrErrorMessage = array();
    
    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        //$this->objBasicCheck = new BasicCheck();
        //$this->objPreperDate = new PreperDate();
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
     * メモ:
     * $objValidate->Execute(array("d_order_master_OrderID" => "受注ID"), array("StringLength", $stParam, 10, 50))
     */
    
    /**
     * バリデート処理の実行と、エラーメッセージの格納
     *
     * @access public
     * @param  mixed        $mixColumn    第一引数
     * @param  mixed        $mixResult    第一引数
     * @return self
     */
    public function Execute($arrColumn, $arrParam) {
        // バリデート用クラス呼び出し
        $stClassName = "Validate_" . $arrParam[0];
        $objValidateClass = new $stClassName($arrColumn, $arrParam);
        $stMessage = $objValidateClass->isValid();
        // エラーでなかった場合
        if($stMessage === true) {
            return true;
        }
        // エラーだった場合
        // メモ: カラム名が複数セットされていても、配列要素一つ目のカラム名をエラーメッセージとして格納する。
        else {
            foreach($arrColumn as $key => $value) {
                $this->setResult($key, $stMessage);
                break;
            }
        }
        
        unset($objValidateClass);
        
        return $this;
    }
    
    // メンバ変数にバリデート結果をセットする
    public function setResult($stKey, $stMessage) {
        $this->_arrErrorMessages[$stKey] = $stMessage;
        return $this;
    }
    
    // メンバ変数に格納されたバリデート結果を返す
    public function getResult() {
        if(count($this->_arrErrorMessages) > 0) {
            return $this->_arrErrorMessages;
        } else {
            return false;
        }
    }
    
    // $stKeyに紐づくエラーメッセージを削除
    public function removeResult($stKey) {
        try {
            $arrResult = $this->getResult();
            if(count($arrResult) > 0 && isset($arrResult[$stKey])) {
                unset($arrResult[$stKey]);
                return $this;
            } else {
                return false;
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION_, $e);
        }
    }

    // $stKeyに紐づくエラーメッセージをメンバ変数から削除
    public function unsetResult($stKey) {
        try {
            $arrResult = $this->getResult();
            if(count($arrResult) > 0 && isset($arrResult[$stKey])) {
                unset($this->_arrErrorMessages[$stKey]);
            }
            return true;
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION_, $e);
        }
    }
}