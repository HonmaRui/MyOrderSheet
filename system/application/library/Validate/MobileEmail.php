<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage MobileEmail
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     鈴木
 * @version    v1.0
 */
class Validate_MobileEmail extends Validate_Abstract {
    /**
     * エラーメッセージ用文章
     * @var array
     */
    protected $_arrMessageTemplates = array(
        "Invalid" => "%ColumnName%は携帯電話のメールアドレスを入力してください。",
    );
    
    /**
     * コンストラクタ
     *
     * @access public
     * @param  array    $arrColumn[0]    カラム名 => 項目名 (連想配列)
     * @param  array    $arrParam[0]     バリデート処理クラス名
     * @param  array    $arrParam[1]     バリデート対象データ
     * @return void
     */
    public function __construct($arrColumn, $arrParam) {
        try {
            // Message
            $this->objMessage = Zend_Registry::get('objMessage');
            
            // バリデート対象データが空の場合はバリデート処理を行わない
            if($arrParam[1] == "") return true;
            
            // 例外処理
            if(empty($arrColumn)) throw new Zend_Exception('カラム名を設定してください。');
            
            // カラム名、項目名をセット
            foreach($arrColumn as $key => $value) {
                $this->setColumn($key);
                $this->setColumnName($value);
            }
            
            // パラメータセット
            $this->setParam($arrParam[1]);
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * セットされたパラメータに携帯電話向けメールアドレスのドメインが含まれているかチェックする。
     *
     * @access public
     * @return mixed
     */
    public function isValid() {
        if (!preg_match("/^.+@(docomo\.ne\.jp|ezweb\.ne\.jp|softbank\.ne\.jp|t\.vodafone\.ne\.jp|d\.vodafone\.ne\.jp|h\.vodafone\.ne\.jp|c\.vodafone\.ne\.jp|k\.vodafone\.ne\.jp|r\.vodafone\.ne\.jp|n\.vodafone\.ne\.jp|s\.vodafone\.ne\.jp|q\.vodafone\.ne\.jp|pdx\.ne\.jp|wm\.pdx\.ne\.jp|di\.pdx\.ne\.jp|dj\.pdx\.ne\.jp|dk\.pdx\.ne\.jp)$/",$this->getParam(), $stResult) ) {
            return true;
        } else {
            $this->setMessage("Invalid");
            return $this->getMessage();
        }
    }
}