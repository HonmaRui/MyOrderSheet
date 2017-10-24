<?php
/**
 * 共通ライブラリ（マスタ管理）
 *
 * @author     
 * @version    v1.0
 */
class Master {
    
    // クラス定数宣言

    // 画面設定値

    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        // Library & Models
        $this->objBasis = new Basis();
        $this->objCommon = new Common();
        
        $this->mdlBaseInfo = new Application_Model_BaseInfo();

     }
    
    /**
     * 担当者マスタフォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @param  array   $stMode      モード
     * @return array
     */
    function errorCheckForMember($arrForm, $stMode) {
        
        try {
            $objValidate = new Validate();
            
            switch ($stMode) {
                case "create":
                    $stKey = "d_system_member_Name";
                    $stColumnName = "担当者名";
                    $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 20));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    
                    $stKey = "d_system_member_Department";
                    $stColumnName = "所属";
                    $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 20));
                    
                    $stKey = "d_system_member_LoginID";
                    $stColumnName = "ログインID";
                    $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 50));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    
                    $stKey = "d_system_member_Password";
                    $stColumnName = "パスワード";
                    $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 100));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    
                    $stKey = "d_system_member_Authority";
                    $stColumnName = "権限";
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    
                break;
                case "move":
                    $stKey = "posnum" . $arrForm["posnum"];
                    $stColumnName = "表示順";
                    $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                break;
            }
            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 新着情報フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @param  array   $stMode      モード
     * @return array
     */
    function errorCheckForNews($arrForm, $stMode) {
        
        try {
            $objValidate = new Validate();
            
            switch ($stMode) {
                case "add":
                case "save":
                    // 日付が完全か(年月日全てが入力されているか)チェックする。
                    $arrDate = array($arrForm["news_from_Year"], $arrForm["news_from_Month"], $arrForm["news_from_Day"]);
                    $stFormKey = "d_contents_newinfo_Date";
                    $stColumnName = "日付";
                    $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
                    // 実在する日付かチェック
                    if ($arrForm["news_from_Year"] && $arrForm["news_from_Month"] && $arrForm["news_from_Day"]) {
                        $stFromDate = $arrForm["news_from_Year"] . "-" . $arrForm["news_from_Month"] . "-" . $arrForm["news_from_Day"];
                        $stFormKey = "d_contents_newinfo_Date";
                        $stColumnName = "日付";
                        $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stFromDate));
                    }
                    
                    $stKey = "d_contents_newinfo_Title";
                    $stColumnName = "タイトル";
                    $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 200));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    
                    $stKey = "d_contents_newinfo_Text";
                    $stColumnName = "本文";
                    $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 4000));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                break;
                case "move":
                    $stKey = "posnum" . $arrForm["posnum"];
                    $stColumnName = "表示順";
                    $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                break;
            }
            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}