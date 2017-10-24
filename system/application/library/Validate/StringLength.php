<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage StringLength
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     鈴木
 * @version    v1.0
 */
class Validate_StringLength extends Validate_Abstract {
    /**
     * エラーメッセージ用文章
     * @var array
     */
    protected $_arrMessageTemplates = array(
        "Invalid" => "%ColumnName%は%From%文字以上、%To%文字以下で入力してください。",
        "TooShort" => "%ColumnName%は%From%文字以上で入力してください。",
        "TooLong" => "%ColumnName%は%To%文字以下で入力してください。"
    );
    
    /**
     * コンストラクタ
     *
     * @access public
     * @param  array    $arrColumn[0]    カラム名 => 項目名 (連想配列)
     * @param  array    $arrParam[0]     バリデート処理クラス名
     * @param  array    $arrParam[1]     バリデート対象データ
     * @param  array    $arrParam[2]     始点
     * @param  array    $arrParam[3]     終点
     * @return void
     */
    public function __construct($arrColumn, $arrParam) {
        try {
            // Message
            $this->objMessage = Zend_Registry::get('objMessage');
            
            // 例外処理
            if(empty($arrColumn))   throw new Zend_Exception('カラム名を設定してください。');
            if(empty($arrParam[2])) throw new Zend_Exception('始点を設定してください。');
            if(empty($arrParam[3])) throw new Zend_Exception('終点を設定してください。');
            
            // カラム名、項目名をセット
            foreach($arrColumn as $key => $value) {
                $this->setColumn($key);
                $this->setColumnName($value);
            }
            
            // パラメータセット
            $this->setParam($arrParam[1]);
            // 始点セット
            $this->setFrom($arrParam[2]);
            // 終点セット
            $this->setTo($arrParam[3]);
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * セットされたパラメータの文字長がFrom～Toの間であるかチェックする。
     *
     * @access public
     * @return mixed
     */
    public function isValid() {
        $iLength = strlen($this->getParam());
        if($iLength >= $this->getFrom() && $iLength <= $this->getTo()) {
            return true;
        } else {
            $this->setMessage("Invalid");
            return $this->getMessage();
        }
    }
}