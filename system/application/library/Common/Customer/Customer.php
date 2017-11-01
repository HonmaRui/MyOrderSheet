<?php
/**
 * 共通ライブラリ（顧客管理）
 *
 * @author     
 * @version    v1.0
 */
class Customer {
    
    // クラス定数宣言
    // 画面設定値
    const RESULT_NUM_MIN_PER_PAGE = 1;              // 1ページあたりの最小表示件数
    const RESULT_NUM_MAX_PER_PAGE = 10;             // 1ページあたりの最大表示件数

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
        $this->mdlCustomerRank = new Application_Model_CustomerRank();
        $this->mdlCustomerRankStandard = new Application_Model_CustomerRankStandard();
     }
    
    /**
     * 顧客検索用検索条件エラーチェック
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @return array
     */
    function errorCheckForCustomerSearch($arrForm) {
        
        try {
            
            $objValidate = new Validate();

            // 日付が設定されている場合は、その日付が完全か(年月日全てが入力されているか)チェックする。
            // 登録日
            if ($arrForm["create_from_Year"] || $arrForm["create_from_Month"] || $arrForm["create_from_Day"]) {
                $arrDate = array($arrForm["create_from_Year"], $arrForm["create_from_Month"], $arrForm["create_from_Day"]);
                $stFormKey = "d_customer_CreatedTimeStart";
                $stColumnName = "登録日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            if ($arrForm["create_to_Year"] || $arrForm["create_to_Month"] || $arrForm["create_to_Day"]) {
                $arrDate = array($arrForm["create_to_Year"], $arrForm["create_to_Month"], $arrForm["create_to_Day"]);
                $stFormKey = "d_customer_CreatedTimeEnd";
                $stColumnName = "登録日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            // 更新日
            if ($arrForm["update_from_Year"] || $arrForm["update_from_Month"] || $arrForm["update_from_Day"]) {
                $arrDate = array($arrForm["update_from_Year"], $arrForm["update_from_Month"], $arrForm["update_from_Day"]);
                $stFormKey = "d_customer_UpdatedTimeStart";
                $stColumnName = "更新日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            if ($arrForm["update_to_Year"] || $arrForm["update_to_Month"] || $arrForm["update_to_Day"]) {
                $arrDate = array($arrForm["update_to_Year"], $arrForm["update_to_Month"], $arrForm["update_to_Day"]);
                $stFormKey = "d_customer_UpdatedTimeEnd";
                $stColumnName = "更新日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("AllDate", $arrDate));
            }
            
            // 実在する日付かチェック
            // 登録日
            if ($arrForm["create_from_Year"] && $arrForm["create_from_Month"] && $arrForm["create_from_Day"]) {
                $stFromDate = $arrForm["create_from_Year"] . "-" . $arrForm["create_from_Month"] . "-" . $arrForm["create_from_Day"];
                $stFormKey = "d_customer_CreatedTimeStart";
                $stColumnName = "登録日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stFromDate));
            }
            if ($arrForm["create_to_Year"] && $arrForm["create_to_Month"] && $arrForm["create_to_Day"]) {
                $stToDate = $arrForm["create_to_Year"] . "-" . $arrForm["create_to_Month"] . "-" . $arrForm["create_to_Day"];
                $stFormKey = "d_customer_CreatedTimeEnd";
                $stColumnName = "登録日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stToDate));
            }
            // 更新日
            if ($arrForm["update_from_Year"] && $arrForm["update_from_Month"] && $arrForm["update_from_Day"]) {
                $stFromDate = $arrForm["update_from_Year"] . "-" . $arrForm["update_from_Month"] . "-" . $arrForm["update_from_Day"];
                $stFormKey = "d_customer_UpdatedTimeStart";
                $stColumnName = "更新日(開始)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stFromDate));
            }
            if ($arrForm["update_to_Year"] && $arrForm["update_to_Month"] && $arrForm["update_to_Day"]) {
                $stToDate = $arrForm["update_to_Year"] . "-" . $arrForm["update_to_Month"] . "-" . $arrForm["update_to_Day"];
                $stFormKey = "d_customer_UpdatedTimeEnd";
                $stColumnName = "更新日(終了)";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("Date", $stToDate));
            }

            // 日付の前後関係のチェックを行う
            // 登録日付の前後関係のチェックを行う
            if ($arrForm["create_from_Year"] && $arrForm["create_from_Month"] && $arrForm["create_from_Day"] &&
                $arrForm["create_to_Year"] && $arrForm["create_to_Month"] && $arrForm["create_to_Day"]) {
                $stFromDate = $arrForm["create_from_Year"] . "/" . $arrForm["create_from_Month"] . "/" . $arrForm["create_from_Day"];
                $stToDate = $arrForm["create_to_Year"] . "/" . $arrForm["create_to_Month"] . "/" . $arrForm["create_to_Day"];
                $stFormKey = "d_customer_CreatedTime";
                $stColumnName = "登録日";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("DateTimeCompare", array($stFromDate, $stToDate)));
            }
            // 更新日付の前後関係のチェックを行う
            if ($arrForm["update_from_Year"] && $arrForm["update_from_Month"] && $arrForm["update_from_Day"] &&
                $arrForm["update_to_Year"] && $arrForm["update_to_Month"] && $arrForm["update_to_Day"]) {

                $stFromDate = $arrForm["update_from_Year"] . "/" . $arrForm["update_from_Month"] . "/" . $arrForm["update_from_Day"];
                $stToDate = $arrForm["update_to_Year"] . "/" . $arrForm["update_to_Month"] . "/" . $arrForm["update_to_Day"];
                $stFormKey = "d_customer_UpdatedTime";
                $stColumnName = "更新日";
                $objValidate->Execute(array($stFormKey => $stColumnName), array("DateTimeCompare", array($stFromDate, $stToDate)));
            }

            $arrErrorMessage = $objValidate->getResult();
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 顧客テーブル及び関連テーブルの新規保存、更新を行う
     *
     * @param   array   $arrForm        入力フォームデータ
     * @param   int     $iCustomerID    顧客ID
     * @return  array
     */
    function createCustomer($arrForm, $iCustomerID = "") {
        
        $bTran = false;
        try {
            
            $arrCustomer = array();
            
            // フォーム値から顧客関連データを抽出
            foreach ($arrForm as $key => $value) {
                if (preg_match("/^d_customer/", $key)) {
                    $arrCustomer[$key] = $value;
                }
            }
            
            // パスワードを暗号化する
            if (!empty($arrCustomer["d_customer_Password"])) {
                $arrCustomer["d_customer_Password"] = $this->objCommon->makePassword($arrCustomer["d_customer_Password"]);
            }
            
            // DBに保存処理開始
            $this->mdlCustomer->begin();
            $bTran = true;

            if ($iCustomerID != "") {
                // 編集
                $arrCustomer["d_customer_CustomerID"] = $iCustomerID;
                $this->mdlCustomer->save($arrCustomer);
            } else {
                // 新規
                $iCustomerID = $this->mdlCustomer->insert($arrCustomer, false);
            }
            
            // commit
            $this->mdlCustomer->commit();
            
            return $iCustomerID;
            
        } catch (Zend_Exception $e) {
            if ($bTran) {
                $this->mdlCustomer->rollBack();
            }
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 顧客ランク決定
     *
     * @param  string   $stCompanyName    会社名
     * @return int      $iCustomerRankID  顧客ランクID
     */
    function decideCustomerRank($stCompanyName) {
        
        try {
            
            // 会社名を含む顧客ランク結果配列を取得
            $arrResult = $this->mdlCustomerRankStandard->findRank($stCompanyName);
            
            if ($arrResult) {
                // 顧客ランクキーワードは被ったりしていない前提なので、取得結果も最大1レコードの想定
                $iCustomerRankID = $arrResult[0]["d_customer_rank_standard_CustomerRankID"];
            } else {
                // 登録されたキーワードに当てはまらなかった場合は発注可能枚数が最小のランクに決定
                $arrResult = $this->mdlCustomerRank->min("d_customer_rank_OrderCount", array("d_customer_rank_CustomerRankID"));
                $iCustomerRankID = $arrResult["min(d_customer_rank_OrderCount)"];
            }

            return $iCustomerRankID;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 顧客登録フォームデータのチェック処理
     *
     * @param  array   $arrForm     フォームデータを格納した配列
     * @param  int     $iCustomerID 顧客ID(編集時のみ値が入ってくる)
     * @return array
     */
    function errorCheckForCustomer($arrForm, $iCustomerID = "") {
        
        try {
            $objValidate = new Validate();
            
            $stKey = "d_customer_TelNo";
            $stColumnName = "電話番号";
            if ($arrForm[$stKey] != "") {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 13));
                $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            }
            
            $stKey = "d_customer_Zip";
            $stColumnName = "郵便番号";
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 8));
            $objValidate->Execute(array($stKey => $stColumnName), array("Numeric", $arrForm[$stKey]));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            $stKey = "d_customer_PrefCode";
            $stColumnName = "都道府県";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            $stKey = "d_customer_Address1";
            $stColumnName = "住所1";
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            $stKey = "d_customer_Address2";
            $stColumnName = "住所2";
            if ($arrForm[$stKey] != "") {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 120));
            }
            
            $stKey = "d_customer_CompanyName";
            $stColumnName = "会社名";
            if ($arrForm[$stKey] != "") {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }
            
            $stKey = "d_customer_DepartmentName";
            $stColumnName = "部署名";
            if ($arrForm[$stKey] != "") {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }
            
            $stKey = "d_customer_EmailAddress";
            $stColumnName = "メールアドレス";
            if ($arrForm[$stKey] != "") {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 100));
                $objValidate->Execute(array($stKey => $stColumnName), array("Email", $arrForm[$stKey]));
            }
            
            $stKey = "d_customer_Password";
            $stColumnName = "パスワード";
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            $stKey = "d_customer_Name";
            $stColumnName = "顧客名";
            $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            $objValidate->Execute(array($stKey => $stColumnName), array("NotEmpty", $arrForm[$stKey]));
            
            $stKey = "d_customer_NameKana";
            $stColumnName = "顧客名カナ";
            if ($arrForm[$stKey] != "") {
                $objValidate->Execute(array($stKey => $stColumnName), array("MaxLengthMb", $arrForm[$stKey], 60));
            }
            
            $arrErrorMessage = $objValidate->getResult();
            
            return $arrErrorMessage;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 会員情報を取得する(フロント用)
     *
     * @param  int      $iCustomerID            顧客ID
     * @return
     */
    function getCustomerInfoForFront($iCustomerID) {
        
        try {
            
            // 顧客情報の取得
            $arrCustomer = $this->mdlCustomer->find($iCustomerID);
            
            // 顧客ランクによる、最大発注可能枚数の取得
            if ($arrCustomer["d_customer_CustomerRankID"] != "") {
                $arrRank = $this->mdlCustomerRank->find(array("d_customer_rank_CustomerRankID" => $arrCustomer["d_customer_CustomerRankID"]));
                $arrCustomer["SampleCartLimit"] = $arrRank["d_customer_rank_OrderCount"];
            }
            
            return $arrCustomer;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
}