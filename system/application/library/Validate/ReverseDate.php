<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage ReverseDate
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     鈴木
 * @version    v1.0
 */
class Validate_ReverseDate extends Validate_Abstract {
    /**
     * エラーメッセージ用文章
     * @var array
     */
    protected $_arrMessageTemplates = array(
        "Invalid" => "%ColumnName%は%OptionColumnName%より以前の日付を入力してください。",
    );
    
    /**
     * コンストラクタ
     *
     * @access public
     * @param  array    $arrColumn[0]    カラム名 => 項目名 (連想配列)
     * @param  array    $arrColumn[1]    オプションカラム名 => オプション項目名(連想配列)
     * @param  array    $arrParam[0]     バリデート処理クラス名
     * @param  array    $arrParam[1]     バリデート対象データ(始点)
     * @param  array    $arrParam[2]     閾値(終点)
     * @return void
     */
    public function __construct($arrColumn, $arrParam) {
        try {
            // PreperDate
            $this->objPreperDate = Zend_Registry::get('objPreperDate');
            // Message
            $this->objMessage = Zend_Registry::get('objMessage');
            
            // バリデート対象データが空の場合はバリデート処理を行わない
            if($arrParam[1] == "") return true;
            
            // 例外処理
            if(empty($arrColumn)) throw new Zend_Exception('カラム名を設定してください。');
            if(empty($arrParam[2])) throw new Zend_Exception('終点を設定してください。');
            
            // カラム名、項目名をセット
            $i = 0;
            foreach($arrColumn as $key => $value) {
                if($i == 0) {
                    $this->setColumn($key);
                    $this->setColumnName($value);
                } else {
                    $this->setOptionColumn($key);
                    $this->setOptionColumnName($value);
                }
                $i++;
            }
            
            // パラメータセット
            $this->setParam($arrParam[1]);
            // 終点をセット
            $this->setThreshold($arrParam[2]);
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * セットされたパラメータが終点を超える日付でないかチェックする。
     *
     * @access public
     * @return mixed
     */
    public function isValid() {
        if($this->objPreperDate->getCompareDate( $this->getParam(), $this->getThreshold()) < 0) {
            return true;
        } else {
            $this->setMessage("Invalid");
            return $this->getMessage();
        }
    }
}