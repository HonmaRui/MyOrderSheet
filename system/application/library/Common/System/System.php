<?php
/**
 * 共通ライブラリ（システム設定）
 *
 * @author     M-PIC鈴木
 * @version    v1.0
 */
class System {
    
    // クラス定数宣言
    // 権限設定値
    const SYSTEM_AUTHORITY_SYSTEMMANAGER = 1;     // システム管理者
    const SYSTEM_AUTHORITY_SITEMANAGER = 2;       // サイト管理者
    const SYSTEM_AUTHORITY_OPERATOR = 3;          // 一般オペレータ
    const SYSTEM_AUTHORITY_LIMITEDOPERATOR = 4;   // 制限オペレータ
    const SYSTEM_AUTHORITY_COUNTREADER = 5;       // 売上集計閲覧者
    const SYSTEM_AUTHORITY_SYSTEMDEVELOPER = 6;   // システム開発者

    // 稼動・非稼動
    const SYSTEM_RUN = 1;                         // 稼動
    const SYSTEM_NOTRUN = 2;                      // 非稼動

    
    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        

    }

    /**
     * フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheck_Member($arrForm) {
        
        try {
            $objValidate = new Validate();

            //入力チェック
            $stKey = "d_system_member_Name";
            $stColumnName = "名前";
            $objValidate->Execute(
                array($stKey => $stColumnName), 
                array("NotEmpty", $arrForm[$stKey]));
            $stKey = "d_system_member_LoginID";
            $stColumnName = "ログインID";
            $objValidate->Execute(
                array($stKey => $stColumnName), 
                array("NotEmpty", $arrForm[$stKey]));

            $stKey = "d_system_member_Password";
            $stColumnName = "パスワード";
            $objValidate->Execute(
                array($stKey => $stColumnName), 
                array("NotEmpty", $arrForm[$stKey]));

            $stKey = "d_system_member_Authority";
            $stColumnName = "権限";
            $objValidate->Execute(
                array($stKey => $stColumnName), 
                array("NotEmpty", $arrForm[$stKey]));

//            $stKey = "d_system_member_Run";
//            $stColumnName = "稼動/非稼動";
//            $objValidate->Execute(
//                array($stKey => $stColumnName), 
//                array("NotEmpty", $arrForm[$stKey]));

            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
