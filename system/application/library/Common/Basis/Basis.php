<?php
/**
 * 共通ライブラリ（基本情報管理）
 *
 * @author     
 * @version    v1.0
 */
class Basis {
    
    // クラス定数宣言
    // 課税規則設定値
    const BASIS_TAXRULE_CEIL = 1;      // 切上げ
    const BASIS_TAXRULE_ROUND = 2;     // 四捨五入
    const BASIS_TAXRULE_FLOOR = 3;     // 切捨て
    
    const BASIS_RESULT_NUM_MIN_PER_PAGE = 1;              // 1ページあたりの最小表示件数
    const BASIS_RESULT_NUM_MAX_PER_PAGE = 10;             // 1ページあたりの最大表示件数
    /**
     * コンストラクタ
     *
     * @access public
     * @return void
     */
    public function __construct() {
        
        // Library & Models
        $this->mdlBaseInfo = new Application_Model_BaseInfo();
        //Common
        $this->objCommon = new Common();
        //Session
        $this->objAdminSess = new Zend_Session_Namespace('Admin');
     }

   /**
     * 基本設定保存ボタン押下処理
     *
     * @param  array      $arrForm              フォーム値
     * @param  object     $objFormReq           リクエストオブジェクト
     * @return array
     */
     function createBaseInfo($arrForm, $objFormReq) {
        
        // ログ用の退避
        $arrLogFormData = $arrForm;
        $bTran = false;
        try {
            // begin
            $this->mdlBaseInfo->begin();
            $bTran = true;

            // SHOPマスタテーブルおよびそのリレーションテーブルの登録
            // d_baseinfo 登録
            $arrTable = array("d_baseinfo");
            $arrFormBaseInfo = CommonTools::getExtractTableData($arrTable, $arrForm);

            $this->mdlBaseInfo->save($arrFormBaseInfo);

            // commit
            $this->mdlBaseInfo->commit();
            $bTran = false;
            
            // ログ収集
            $this->objCommon->writeLog($this->objAdminSess, $objFormReq, $arrLogFormData, SET_LOG_LEVEL_ALL);
            
        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlBaseInfo->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * SHOPマスタフォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheckBaseInfo($arrForm) {
        
        try {
            $objValidate = new Validate();

            // 郵便番号
            $stKey = "d_baseinfo_Zip";
            $stColumnName = "郵便番号";
            $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "7"));
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            // 都道府県
            $stKey = "d_baseinfo_PrefCode";
            $stColumnName = "都道府県";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            // 住所1
            $stKey = "d_baseinfo_Address1";
            $stColumnName = "住所1";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));
 
            // 住所2
            $stKey = "d_baseinfo_Address2";
            $stColumnName = "住所2";
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));
            
            // 事業者名
            $stKey = "d_baseinfo_Name";
            $stColumnName = "事業者名";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            
            // メールアドレス
            $stKey = "d_baseinfo_MailAddress1";
            $stColumnName = "受注情報受付メールアドレス";
            $objValidate->Execute(array($stKey => $stColumnName), array("EMail", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "100"));
            // メールアドレス
            $stKey = "d_baseinfo_MailAddress2";
            $stColumnName = "問い合わせ受付メールアドレス";
            $objValidate->Execute(array($stKey => $stColumnName), array("EMail", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "100"));
            // メールアドレス
            $stKey = "d_baseinfo_MailAddress3";
            $stColumnName = "送信エラー受付メールアドレス";
            $objValidate->Execute(array($stKey => $stColumnName), array("EMail", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "100"));
            // メールアドレス
            $stKey = "d_baseinfo_MailAddress4";
            $stColumnName = "メール送信元メールアドレス";
            $objValidate->Execute(array($stKey => $stColumnName), array("EMail", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "100"));
            // メールアドレス
            $stKey = "d_baseinfo_MailAddress5";
            $stColumnName = "メルマガ送信元メールアドレス";
            $objValidate->Execute(array($stKey => $stColumnName), array("EMail", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "100"));
            
            // 電話番号
            $stKey = "d_baseinfo_TelNo";
            $stColumnName = "電話番号";
            $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "13"));
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            // FAX番号
            $stKey = "d_baseinfo_FaxNo";
            $stColumnName = "FAX番号";
            if (!empty($arrForm[$stKey])) {
                $objValidate->Execute(array($stKey => $stColumnName), array("MinLength", $arrForm[$stKey], "13"));
                $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            }
            
            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

   /**
     * メール設定フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheckMail($arrForm) {
        
        try {
            $objValidate = new Validate();

            $stKey = "d_mail_setting_Title";
            $stColumnName = "メールタイトル";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            $stKey = "d_mail_setting_Content";
            $stColumnName = "内容";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));

            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
//            $this->objMessage->getExceptionMessage(get_class(), __FUNCTION__, $e);
            throw new Zend_Exception($e->getMessage());
        }
    }

   /**
     * メール履歴フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheckMailHistory($arrForm) {
        
        try {
            $objValidate = new Validate();

            // 日付が設定されている場合は、その日付が完全か(年月日全てが入力されているか)チェックする。
            if ($arrForm["post_from_Year"] || $arrForm["post_from_Month"] || $arrForm["post_from_Day"]) {
                $arrDate = array($arrForm["post_from_Year"], $arrForm["post_from_Month"], $arrForm["post_from_Day"]);
                $stFormKey = "d_mail_history_SendDateStart";
                $stColumnName = "配信日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            if ($arrForm["post_to_Year"] || $arrForm["post_to_Month"] || $arrForm["post_to_Day"]) {
                $arrDate = array($arrForm["post_to_Year"], $arrForm["post_to_Month"], $arrForm["post_to_Day"]);
                $stFormKey = "d_mail_history_SendDateEnd";
                $stColumnName = "配信日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            
            // 実在する日付かチェック
            if ($arrForm["post_from_Year"] && $arrForm["post_from_Month"] && $arrForm["post_from_Day"]) {
                $stFromDate = $arrForm["post_from_Year"] . "-" . $arrForm["post_from_Month"] . "-" . $arrForm["post_from_Day"];
                $stFormKey = "d_mail_history_SendDateStart";
                $stColumnName = "配信日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stFromDate));
            }
            if ($arrForm["post_to_Year"] && $arrForm["post_to_Month"] && $arrForm["post_to_Day"]) {
                $stToDate = $arrForm["post_to_Year"] . "-" . $arrForm["post_to_Month"] . "-" . $arrForm["post_to_Day"];
                $stFormKey = "d_mail_history_SendDateEnd";
                $stColumnName = "配信日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stToDate));
            }

            // 日付の前後関係のチェックを行う
            if ($arrForm["post_from_Year"] && $arrForm["post_from_Month"] && $arrForm["post_from_Day"] &&
                $arrForm["post_to_Year"] && $arrForm["post_to_Month"] && $arrForm["post_to_Day"]) {
                $stFromDate = $arrForm["post_from_Year"] . "/" . $arrForm["post_from_Month"] . "/" . $arrForm["post_from_Day"];
                $stToDate = $arrForm["post_to_Year"] . "/" . $arrForm["post_to_Month"] . "/" . $arrForm["post_to_Day"];
                $stFormKey = "d_mail_history_SendDate";
                $stColumnName = "配信日";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("DateTimeCompare", array($stFromDate, $stToDate)));
            }

            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}
