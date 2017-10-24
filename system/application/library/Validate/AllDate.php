<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage AllDate
 * @copyright  Copyright (c) 2016 Majestic-PIC Co., Ltd.
 * @author     本間
 * @version    v1.0
 */
class Validate_AllDate extends Validate_Abstract {
    /**
     * エラーメッセージ用文章
     * @var array
     */
    protected $_arrMessageTemplates = array(
        "Invalid" => "%ColumnName%は正しい日付で入力してください。",
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
     * セットされた日付が完全か(年月日全てが入力されているか)チェックする。
     *
     * @access public
     * @return mixed
     */
    public function isValid() {
        $arrParam = $this->getParam();
        foreach ($arrParam as $value) {
            if($value == "") {
                $this->setMessage("Invalid");
                return $this->getMessage();
            }
        }
        return true;
    }
}