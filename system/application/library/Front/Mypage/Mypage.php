<?php
/**
 * ログイン・会員登録ライブラリ
 *
 * @author     M-PIC本間
 * @version    v1.0
 */
class Mypage {
    
    // クラス定数宣言

    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        // Library & Models
        $this->objCommon = new Common();
        
        $this->mdlCustomer = new Application_Model_Customer();
     }

    /**
     * フォームデータのチェック処理（登録、編集 両用）
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheck($arrForm) {
        
        try {
            $objValidate = new Validate();

            switch ($arrForm["mode"]) {
                case "reminder":
                    $stKey = "d_customer_EmailAddress";
                    $stColumnName = "メールアドレス";
                    $objValidate->Execute(array($stKey => $stColumnName), array("Email", $arrForm[$stKey]));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    break;
                case "entry":
                case "change":
                    $stKey = "d_customer_Name";
                    $stColumnName = "氏名";
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    
                    $stKey = "d_customer_EmailAddress";
                    $stColumnName = "メールアドレス";
                    $objValidate->Execute(array($stKey => $stColumnName), array("Email", $arrForm[$stKey]));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    
                    $stKey = "d_customer_Password";
                    $stColumnName = "パスワード";
                    $objValidate->Execute(array($stKey => $stColumnName), array("AlphaNumeric", $arrForm[$stKey]));
                    $objValidate->Execute(array($stKey => $stColumnName), array("MaxLength", $arrForm[$stKey], "4"));
                    $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "12"));
                    $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
                    break;              
                default:
                    break;
            }
            
            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     *
     * 各画面に応じた取得、対象カラムを取得する関数
     *
     * $stType = 画面タイプ( login, entry, edit のどれか )
     *
    */
    public function isJudgeLogin($stCustomerEmailAddress, $stCustomer_Password) {
        
        try {
                // 取得処理を実行する為の変数設定
                // 入力されたログインIDとパスワードから管理者データを取得
                $arrData = $this->getLoginCustomer($stCustomerEmailAddress, $stCustomer_Password );

                // 取得結果が0件の場合falseをreturnにセット
                if (empty($arrData) ){
                    $arrData = false;
                } else {
                    // ログインした顧客用のSessionをスタートする
                    $this->startFrontSession($arrData);
                }

                // 実行結果を判定
                if (is_null($arrData)){
                    return null;
                } else {
                    return $arrData;
                }
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /***
     *
     * 各画面に応じた取得、対象カラムを取得する関数
     *
     * $stType = 画面タイプ( login, entry, edit のどれか )
     *
    */
    public function getLoginCustomer($stCustomerEmailAddress, $stCustomerPassword) {
        
        try {

                // メンバ変数定義
                $arrData = array();

                // 取得処理を実行する為の変数設定
                $arrCondition = array();
                $arrCondition["d_customer_EmailAddress"] = $stCustomerEmailAddress;
                $this->mdlCustomer->setSearchConditionForFrontLogin($arrCondition);
                $arrData = $this->mdlCustomer->search();
                // 実行結果を判定
                if (!is_null($arrData)){
                    if ($this->objCommon->verificatePassword($stCustomerPassword, $arrData[0]["d_customer_Password"])) {
                        return $arrData;
                    } else {
                        unset($arrData);
                    }
                }
                
                return $arrData;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /***
     *
     * ログイン成功時にFrontSessionを格納する関数
     *
     * $arrData = ログインに成功し、取得された管理者データ
     *
    */
    function startFrontSession($arrData = null) {
        
        try {

            // メンバ変数定義
            // sessionId
            $stSessionId = "";

            // Session 定義（名前空間定義することでキーの衝突を防ぐ）
            $this->objFrontSess = new Zend_Session_Namespace("Front");

            // session_idをGlobal変数に格納
            $stSessionID = session_id();

            // Sessionへのデータ格納処理
            if(!is_null($arrData) ){
                foreach( $arrData[0] as $key => $val ){
                    // Sessionのkeyに共通する文字列部分を削る
                    $Name = str_replace("d_customer_", "", $key);
                    // Session内にkeyと値を格納する
                    $this->objFrontSess->$Name = $val;
                }
                $this->objFrontSess->memberID = $this->objFrontSess->CustomerID;
                $this->objFrontSess->ReserveCustomerID = $this->objFrontSess->CustomerID; //20170214追加 変更がありえない変数として扱う

                // ログイン状態のフラグをセット
                $this->objFrontSess->Login = true;

            } else {
                // objFrontSessにSession情報を格納
                $this->objFrontSess = $_SESSION['Front'];
            }

        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}