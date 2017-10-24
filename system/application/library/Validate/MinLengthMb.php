<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage MinLengthMb
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     柿崎
 * @version    v1.0
 */
class Validate_MinLengthMb extends Validate_Abstract {
    /**
     * エラーメッセージ用文章
     * @var array
     */
    protected $_arrMessageTemplates = array(
        "Invalid" => "%ColumnName%は%Threshold%文字以上で入力してください。",
    );
    
    /**
     * コンストラクタ
     *
     * @access public
     * @param  array    $arrColumn[0]    カラム名 => 項目名 (連想配列)
     * @param  array    $arrParam[0]     バリデート処理クラス名
     * @param  array    $arrParam[1]     バリデート対象データ
     * @param  array    $arrParam[2]     閾値
     * @return void
     */
    public function __construct($arrColumn, $arrParam) {
        try {
            // Message
            $this->objMessage = Zend_Registry::get('objMessage');
            
            // カラム名、項目名をセット
            foreach($arrColumn as $key => $value) {
                $this->setColumn($key);
                $this->setColumnName($value);
            }
            
            // バリデート対象データが空の場合はバリデート処理を行わない
            if($arrParam[1] == "") return true;
            
            // 例外処理
            if(empty($arrColumn)) throw new Zend_Exception('カラム名を設定してください。');
            if(empty($arrParam[2])) throw new Zend_Exception('閾値を設定してください。');
            
            // パラメータセット
            $this->setParam($arrParam[1]);
            // 閾値セット
            $this->setThreshold($arrParam[2]);
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * セットされたパラメータが閾値で設定された文字長以上かチェックする。
     *
     * @access public
     * @return mixed
     */
    public function isValid() {
        $stParam = $this->getParam();
        $encode = mb_detect_encoding($stParam);
        
        // 渡されたパラメータ
        $iLength = mb_strlen($stParam, $encode);
        $iRuleLength = $this->getThreshold();
        
        if($iLength >= $iRuleLength) {
            return true;
        } else {
            $this->setMessage("Invalid");
            return $this->getMessage();
        }
    }
}