<?php
/**
 * バリデート処理用クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage KanaMidpointBrackets
 * @copyright  Copyright (c) 2015 Majestic-PIC Co., Ltd.
 * @author     本間
 * @version    v1.0
 */
class Validate_KanaBrackets extends Validate_Abstract {
    /**
     * エラーメッセージ用文章
     * @var array
     */
    protected $_arrMessageTemplates = array(
        "Invalid" => "%ColumnName%はカタカナ、括弧で入力してください。",
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

            // カラム名、項目名をセット
            foreach($arrColumn as $key => $value) {
                $this->setColumn($key);
                $this->setColumnName($value);
            }
            
            // バリデート対象データが空の場合はバリデート処理を行わない
            if($arrParam[1] == "") return true;
            
            // 例外処理
            if(empty($arrColumn)) throw new Zend_Exception('カラム名を設定してください。');
            
            // パラメータセット
            $this->setParam($arrParam[1]);
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    /**
     * セットされたパラメータがカタカナ＋括弧＋濁音・半濁音(半角カナから全角カナへ変換時に発生)で構成されているかチェックする。
     *
     * @access public
     * @return mixed
     */
    public function isValid() {
        if (preg_match("/^[ァ-ヶー（()）゛゜]+$/u", $this->getParam())) {
            return true;
        } else {
            $this->setMessage("Invalid");
            return $this->getMessage();
        }
    }
}