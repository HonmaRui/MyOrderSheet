<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage DateTime
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     鈴木
 * @version    v1.0
 */
class Validate_DateTime extends Validate_Abstract {
    /**
     * エラーメッセージ用文章
     * @var array
     */
    protected $_arrMessageTemplates = array(
        "Invalid" => "%ColumnName%は正しい日時で入力して下さい。",
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
            // PreperDate
            $this->objPreperDate = Zend_Registry::get('objPreperDate');
            
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
     * セットされたパラメータが正しい日時であるかチェックする。
     *
     * @access public
     * @return mixed
     */
    public function isValid() {
        $arrDate = $this->objPreperDate->parseDatetime($this->getParam(), TRUE);
        
        $bErrorFlag = FALSE;
        if(checkdate($arrDate["Month"], $arrDate["Day"], $arrDate["Year"])) {
            if ($arrDate['Hour'] < 0 || $arrDate['Hour'] > 23 || !is_numeric($arrDate['Hour'])) {
                $bErrorFlag = TRUE;
            } if ($arrDate['Min'] < 0 || $arrDate['Min'] > 59 || !is_numeric($arrDate['Min'])) {
                $bErrorFlag = TRUE;
            } if ($arrDate['Sec'] < 0 || $arrDate['Sec'] > 59 || !is_numeric($arrDate['Sec'])) {
                $bErrorFlag = TRUE;
            }
        } else {
            $bErrorFlag = TRUE;
        }
        
        if ($bErrorFlag) {
            $this->setMessage("Invalid");
            return $this->getMessage();
        } else {
            return true;
        }
    }
}