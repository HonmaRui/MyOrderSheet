<?php

/**
 * 顧客テーブル用モデル
 *
 * @author     M-PIC本間
 * @version    v1.0
 */
class Application_Model_Customer extends Application_Model_Abstract 
{
    /**
     * テーブル名
     * @var
     */
    protected $_stTableName = "d_customer";

    // クラス定数宣言
    const MAIL_MAGAZINE_TEXTMAIL = 1;       // テキストメール
    const MAIL_MAGAZINE_NOT_WANT = 2;       // 希望しない
    const ADDRESS_CLASS_PC = 1;             // PC
    const ADDRESS_CLASS_MOBILE = 2;         // モバイル
    
    // 請求締日
    const CLOSE_DATE_ANYTIME = 0;           // 随時
    const CLOSE_DATE_ENDMONTH = 99;         // 月末
    
    // 請求締日
    const CUSTOMER_CLOSE_ANYTIME = 1;       // 随時締
    const CUSTOMER_CLOSE_FIXED = 2;         // 指定締
    
    // 消費税区分
    const TAX_DIV_INCLUDED = 1;             // 税込み
    const TAX_DIV_NOT_INCLUDED = 2;         // 税別
    const TAX_DIV_EXEMPTION = 3;            // 非課税
    
    // 税計算区分
    const TAX_CALC_CLAIM = 1;               // 請求単位
    const TAX_CALC_ORDER_MNG = 2;           // 注文単位
    const TAX_CALC_ORDER = 3;               // 受注単位
    const TAX_CALC_PRODUCT = 4;             // 商品単位
    
    // 消費税端数区分
    const TAX_FRACTION_CEIL = 1;            // 切り上げ
    const TAX_FRACTION_ROUND = 2;           // 四捨五入
    const TAX_FRACTION_FLOOR = 3;           // 切捨て

    // 回収サイト（月）
    const EXPECTED_PAYMENT_CURRENT_MONTH = 1;   // 当月
    const EXPECTED_PAYMENT_NEXT_MONTH = 2;      // 翌月
    const EXPECTED_PAYMENT_NEXT_2MONTH = 3;     // 翌々月
    const EXPECTED_PAYMENT_AFTER_DAY = 4;       // 請求後○日
    
    // 回収サイト（日）
    const EXPECTED_PAYMENT_ENDDAY = 99;     // 月末
    
    // 配送伝票フラグ
    const DELIVER_SLIP_FLG_NECESSARY = 1;       // 必要
    const DELIVER_SLIP_FLG_UNNECESSARY = 2;     // 不要

    // メール配信フラグ
    const CUSTOMER_MAIL_FLG_NECESSARY = 1;       // 必要
    const CUSTOMER_MAIL_FLG_UNNECESSARY = 2;     // 不要
    
    // 送料区分
    const SHIPPING_DIV_NECESSARY = 1;       // 必要
    const SHIPPING_DIV_UNNECESSARY = 2;     // 不要
    
    // 売上判定区分
    const SALE_DECISION_DIV_SHIP = 1;       // 1=出荷日を売上日とする
    const SALE_DECISION_DIV_DELIVER = 2;    // 2=お届け日を売上日とする

    // 売価表示
    const SALE_PRICE_DISP_DISP = 2;         // 2=表示する
    
    // 代表取締役帳票印字フラグ
    const CUSTOMER_PRINT_EXECUTIVE_FLG_NECESSARY = 1;       // 印字する
    const CUSTOMER_PRINT_EXECUTIVE_FLG_UNNECESSARY = 2;     // 印字しない
    
    // ダウンロード
    const DOWNLOAD_CUSTOMER_CSV_CHECK = 1;  // 1=チェックされた顧客CSV
    const DOWNLOAD_CUSTOMER_CSV_ALL = 2;    // 2=全ての顧客CSV
    const DOWNLOAD_CUSTOMER_B2_CHECK = 3;   // 3=チェックされた配送伝票用B2連携データ
    const DOWNLOAD_CUSTOMER_B2_ALL = 4;     // 4=全ての配送伝票用B2連携データ
    const DOWNLOAD_CUSTOMER_TACK_CHECK = 5; // 5=チェックされたタックシール
    const DOWNLOAD_CUSTOMER_TACK_ALL = 6;   // 6=全てのタックシール
    const DOWNLOAD_CUSTOMER_LIST_CHECK = 7; // 7=チェックされた顧客一覧表
    const DOWNLOAD_CUSTOMER_LIST_ALL = 8;   // 8=全ての顧客一覧表
    const DOWNLOAD_CUSTOMER_REGULAR_CHECK = 9; // 9=チェックされた顧客の年間購入申込書
    const DOWNLOAD_CUSTOMER_REGULAR_ALL = 10;  // 10=全ての顧客の年間購入申込書
    
    // 一覧表・CSV出力設定
    const CONFIG_CUSTOMER_ID = 1;           // 1=顧客ID順
    const CONFIG_CUSTOMER_CODE = 2;         // 2=顧客コード順
    const CONFIG_ZIP = 3;                   // 3=郵便番号順
    const CONFIG_CUSTOMER_CATEGORY = 4;     // 4=顧客カテゴリー順
    const CONFIG_CUSTOMER_PREF = 5;         // 5=都道府県順
    
    /**
     * コンストラクタ
     *
     * @return  void
     */
    public function __construct() {
        // 更新系
        $this->objMasterDb = Zend_Registry::get("MASTER_DATABASE");
        // 参照系
        $this->objSlaveDb = Zend_Registry::get("SLAVE_DATABASE");
        // Session 定義（ログイン情報）
        $this->objAdminSess = new Zend_Session_Namespace("Admin");
        $this->objFrontSess = new Zend_Session_Namespace("Front");
        
        // 総件数
        $this->totalCount = 0;
        
       // Library & Models
        $this->mdlCategory = new Application_Model_Category();
    }
    
    /**
     * Zend_Db_Table::beginTransaction()
     *
     * @return  self
     */
    public function begin() {
        $this->objMasterDb->beginTransaction();
        return $this;
    }

    /**
     * Zend_Db_Table::commit()
     *
     * @return  self
     */
    public function commit() {
        $this->objMasterDb->commit();
        return $this;
    }

    /**
     * Zend_Db_Table::rollBack()
     *
     * @return  self
     */
    public function rollBack() {
        $this->objMasterDb->rollBack();
        return $this;
    }
    
    /**
     * 第一引数に紐づく最大値データを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     *
     * @param   array   $stMaxColumn  取得対象のカラム名
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @return  array   $arrResult    検索結果
     */
    public function max($stMaxColumn, $arrColumn = "", $arrWhere = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }

            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("max(" . $stMaxColumn . ")")));
            $objSelect->where("d_customer_DelFlg = ? ", 0);

            foreach ($arrWhere as $key => $value) {
                $objSelect->where($key . " = ? ", $value);
            }

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);

            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 第一引数に紐づく最大値データを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     *
     * @param   array   $stMaxColumn  取得対象のカラム名
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @return  array   $arrResult    検索結果
     */
    public function maxForString($stMaxColumn, $arrColumn = "", $arrWhere = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }

            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("max(CONVERT(`" . $stMaxColumn . "`, SIGNED))")));
            $objSelect->where("d_customer_DelFlg = ? ", 0);

            foreach ($arrWhere as $key => $value) {
                $objSelect->where($key . " = ? ", $value);
            }

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);

            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 第一引数の配列から主キーを抽出し、
     * 主キーに紐づくデータ第一引数の値で更新する。
     * 
     * @param   array   $arrData    更新対象のカラム名と値の連想配列
     * @param   bool    $bIsAdmin   管理画面からの登録
     * @return  self
     */
    public function save($arrData, $bIsAdmin = true) {
        
        try {
            if (count($arrData) == 0) {
                throw new Zend_Exception('$arrDataが空です。');
            }

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams["d_customer_UpdatedTime"] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams["d_customer_UpdatedByID"] = $this->objAdminSess->MemberID;
            } else {
                $arrParams["d_customer_UpdatedByID"] = 0;
            }
            $stWhere = $this->objMasterDb->quoteInto("d_customer_CustomerID = ? ", $arrData["d_customer_CustomerID"]);
            $this->objMasterDb->update($stTable, $arrParams, $stWhere);

            return $this;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数に紐づくデータを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   integer $iID        ID(PK)
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @return  array   $arrResult  検索結果
     */
    public function find($iID, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_customer_DelFlg = ? ", 0);
            $objSelect->where("d_customer_CustomerID = ? ", $iID);

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
  /**
     * 第一引数に検索条件カラムを指定する。
     * 第二引数に検索条件の値を指定する。 
     * 第三引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrOrder     ソートカラム名を格納した配列
     * @return  array   $arrResult    検索結果
     */
    public function findAll($arrWhere, $arrColumn = "", $arrOrder = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objMasterDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_customer_DelFlg = ? ", 0);
            
            foreach ($arrWhere as $key => $value) {
                $objSelect->where($key . " = ? ", $value);
            }
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            }
            
            // select文を実行する
            $objSql = $this->objMasterDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
  /**
     * 第一引数に検索条件カラムを指定する。
     * 第二引数に検索条件の値を指定する。 
     * 第三引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrOrder     ソートカラム名を格納した配列
     * @return  array   $arrResult    検索結果
     */
    public function findAllFromMaster($arrWhere, $arrColumn = "", $arrOrder = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_customer_DelFlg = ? ", 0);
            
            foreach ($arrWhere as $key => $value) {
                $objSelect->where($key . " = ? ", $value);
            }
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            }
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 削除フラグが0の行を全て取得する。
     * 第一引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @param   array   $arrOrder   ソートカラム名配列
     * @return  array   $arrResult  検索結果
     */
    public function fetchAll($arrColumn = "", $arrOrder = "") {
        
        try {
            
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_customer_DelFlg = ? ", 0);
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_customer_CustomerID ASC");
            }

            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数の配列から主キーを抽出し、
     * データ第一引数の値で登録する。
     * 
     * @param   array   $arrData    登録対象のカラム名と値の連想配列
     * @param   bool    $bIsAdmin   管理画面からの登録
     * @return  integer             最後に自動生成されたID
     */
    public function insert($arrData, $bIsAdmin = true) {
        
        try {
            if (count($arrData) == 0) {
                throw new Zend_Exception('$arrDataが空です。');
            }

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams["d_customer_CreatedTime"] = date("Y-m-d H:i:s");
            $arrParams["d_customer_UpdatedTime"] = date("Y-m-d H:i:s");
            if ($bIsAdmin) {
                $arrParams["d_customer_CreatedByID"] = $this->objAdminSess->MemberID;
                $arrParams["d_customer_UpdatedByID"] = $this->objAdminSess->MemberID;
            } else {
                $arrParams["d_customer_CreatedByID"] = 0;
                $arrParams["d_customer_UpdatedByID"] = 0;
            }
            $arrParams["d_customer_DelFlg"] = "0";
            $this->objMasterDb->insert($stTable, $arrParams);

            return $this->objMasterDb->lastInsertId();
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数に紐づくデータに対して削除フラグを立てる。
     * 
     * @param   integer $iID ID(PK)
     * @return  self
     */
    public function delete($iID) {

        try {
            if ($iID == "" || $iID < 0) {
                throw new Zend_Exception('$iIDを指定してください。');
            }

            $stTable = $this->getTableName();
            // 更新内容
            $arrParams["d_customer_DelFlg"] = "1";
            $arrParams["d_customer_UpdatedTime"] = date("Y-m-d H:i:s");
            $arrParams["d_customer_UpdatedByID"] = $this->objAdminSess->MemberID;
            // 更新条件
            $arrWhere["d_customer_CustomerID = ?"] = $iID;
            $arrWhere["d_customer_DelFlg = ?"]  = "0";
            $this->objMasterDb->update($stTable, $arrParams, $arrWhere);
           
            return $this;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * テーブル内のカラムを連想配列（値はnull）で取得する。
     * 第一引数が指定されている場合、その配列を取得対象カラムから除外する。
     * 
     * @param   array   $arrUnsetColumns    取得対象除外のカラム名を格納した配列
     * @return  array   $arrResult          取得対象のカラム名を格納した配列
     */
    public function getColumns($arrUnsetColumns = null) {
        
        try {
            // メンバ変数初期化
            $arrResult = array();
            $arrTemp = array();

            // select文を実行する
            $objDescribe = "DESCRIBE " . $this->getTableName();
            $objSql = $this->objSlaveDb->query($objDescribe);
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetchAll();
            // 取得した要素から「Field」のみを取得
            foreach ($arrResult as $key => $val) {
                foreach ($val as $k => $v) {
                    if ($k == "Field")
                        $arrTemp[ $v ] = null;
                }
            }
            // 整形した配列から除外カラムを取り除く
            if (is_array($arrUnsetColumns)) {
                foreach ($arrTemp as $key => $val) {
                    foreach ($arrUnsetColumns as $k => $v) {
                        if ($key == $v)
                            unset($arrTemp[ $key ]);
                    }
                }
            }
            $arrResult = $arrTemp;
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * setSearchCondition()でセットされた検索条件を元に、
     * 検索処理を実行して結果を返す。
     * 検索結果はsetSearchResult()によりメンバ変数に格納される。
     * 
     * @return  array               検索結果
     */
    public function search($bLimit = true) {
        
        try {
            if (is_object($this->getSearchCondition())) {
                $objSelect = $this->getSearchCondition();
            } else {
                throw new Zend_Exception("検索条件を指定してください。");
            }

            if ($bLimit) {
                $objSelect = &$objSelect->limitPage($this->getPageNumber(), $this->getPageLimit());
            }
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            $arrResult = $objSql->fetchAll();

            // 検索結果をメンバ変数に保持する
            $this->setSearchResult($arrResult);
            
            // 合計件数取得用にSQL文を再構築する
            $arrAlias = $objSelect->getPart(Zend_Db_Select::FROM);
            foreach ($arrAlias as $key =>$value) {
                $stAlias = $key;
                break;
            }
            $objSelect->columns('COUNT(DISTINCT ' .  "`$stAlias`" . '.`d_customer_CustomerID`) AS cnt');
            $objSelect->reset(Zend_Db_Select::ORDER);
            $objSelect->reset(Zend_Db_Select::GROUP);
            $objSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
            $objSelect->reset(Zend_Db_Select::LIMIT_COUNT);
            $objSelect->reset(Zend_Db_Select::HAVING);
            $objSqlCount = $this->objSlaveDb->query($objSelect);
            $arrCountTemp = $objSqlCount->fetch();
            $this->totalCount = intval($arrCountTemp["cnt"]);
            
            return $this->getSearchResult();
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
   /**
     * search()でセットされた検索結果に対しcount()を実行し、
     * 検索結果の件数を返す。
     * 事前にsearch()を実行していない場合、例外を返す。
     * 
     * @return  integer 検索結果件数
     */
    public function searchCount() {
        
        try {
            if (is_array($this->getSearchResult())) {
                $iCount = count($this->getSearchResult());
                return $iCount;
            } else
                throw new Zend_Exception("検索処理を先に実行してください。");
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrOrder           検索結果のソート対象カラム
     * @return  self
     */
    public function setSearchCondition($arrSearchCondition, $arrColumn = array("*"), $arrOrder = "") {

        // 初期設定
        $objSelect = &$this->objSlaveDb->select()->from(array("c" => $this->getTableName()), $arrColumn);

        // 顧客ID
        $stFormKey = "d_customer_CustomerID";
        $stDBKey = "d_customer_CustomerID";
        $stDBAlias = "c.";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where($stDBAlias . "$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("cast(" . $stDBAlias . "$stDBKey as SIGNED) >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("cast(" . $stDBAlias . "$stDBKey as SIGNED) <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where($stDBAlias . "$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 顧客名
        $stFormKey = "d_customer_Name";
        $stDBKey = "d_customer_Name";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 顧客名(カナ)
        $stFormKey = "d_customer_NameKana";
        $stDBKey = "d_customer_NameKana";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 会社名
        $stFormKey = "d_customer_CompanyName";
        $stDBKey = "d_customer_CompanyName";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // メールアドレス
        $stFormKey = "d_customer_EmailAddress";
        $stDBKey = "d_customer_EmailAddress";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 電話番号
        $stFormKey = "d_customer_TelNo";
        $stDBKey = "d_customer_TelNo";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }

        // 都道府県
        $stFormKey = "d_customer_PrefCode";
        $stDBKey = "d_customer_PrefCode";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }

        // 住所
        $stFormKey = "customerAddress";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("CONCAT(d_customer_Address1, d_customer_Address2) LIKE ? ", "%" . $arrSearchCondition[$stFormKey] . "%");
        }
        
        // 登録日
        $stDBKey = "d_customer_CreatedTime";
        if ($arrSearchCondition["create_from_Year"] != "" && $arrSearchCondition["create_from_Month"] != "" && 
            $arrSearchCondition["create_from_Day"] != "") {
            $objSelect->where("$stDBKey >= ?", $arrSearchCondition["create_from_Year"] . "-" . 
                $arrSearchCondition["create_from_Month"] . "-" . $arrSearchCondition["create_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["create_to_Year"] != "" && $arrSearchCondition["create_to_Month"] != "" && 
            $arrSearchCondition["create_to_Day"] != "") {
            $objSelect->where("$stDBKey <= ?", $arrSearchCondition["create_to_Year"] . "-" . 
                $arrSearchCondition["create_to_Month"] . "-" . $arrSearchCondition["create_to_Day"] . " 23:59:59");
        }

        // 更新日
        $stDBKey = "d_customer_UpdatedTime";
        if ($arrSearchCondition["update_from_Year"] != "" && $arrSearchCondition["update_from_Month"] != "" && 
            $arrSearchCondition["update_from_Day"] != "") {
            $objSelect->where("$stDBKey >= ?", $arrSearchCondition["update_from_Year"] . "-" . 
                $arrSearchCondition["update_from_Month"] . "-" . $arrSearchCondition["update_from_Day"] . " 00:00:00");
        }
        if ($arrSearchCondition["update_to_Year"] != "" && $arrSearchCondition["update_to_Month"] != "" && 
            $arrSearchCondition["update_to_Day"] != "") {
            $objSelect->where("$stDBKey <= ?", $arrSearchCondition["update_to_Year"] . "-" . 
                $arrSearchCondition["update_to_Month"] . "-" . $arrSearchCondition["update_to_Day"] . " 23:59:59");
        }
        
        // 職業
        $stFormKey = "d_customer_JobID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stFormKey IN (?) ", $arrSearchCondition[$stFormKey]);
        }
        
        // 顧客ランク
        $stFormKey = "d_customer_CustomerRankID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stFormKey IN (?) ", $arrSearchCondition[$stFormKey]);
        }
        
        $stFormKey = "d_customer_SignedOut";
        if ($arrSearchCondition[$stFormKey] == "") {
            $objSelect->where("$stFormKey = ? ", 0);
        }
        
        // ソート順
        if (!empty($arrOrder)) {
            if (!is_array($arrOrder)) {
                $arrOrder = array($arrOrder);
            }
        } else {
            $arrOrder = array("d_customer_CustomerID DESC");
        }
        foreach ($arrOrder as $value) {
            $objSelect->order($value);
        }
        
        // 顧客IDでGROUP BY
        $objSelect->group("d_customer_CustomerID");
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("d_customer_DelFlg = ? ", 0);

        $this->_objSelect = $objSelect;

        return $this;
    }
    
    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrOrder           検索結果のソート対象カラム
     * @return  self
     */
    public function setSearchConditionForFrontLogin($arrSearchCondition, $arrColumn = array("*")) {

        // 初期設定
        $objSelect = &$this->objSlaveDb->select()->from(array("c" => $this->getTableName()), $arrColumn);
        
        // メールアドレス
        $stFormKey = "d_customer_EmailAddress";
        $stDBKey = "d_customer_EmailAddress";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }
        
        // 退会者は除く
        $objSelect->where("d_customer_SignedOut != ? ", 1);
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("d_customer_DelFlg = ? ", 0);

        $this->_objSelect = $objSelect;
        
        return $this;
    }    

    /**
     * 顧客一覧表用
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrOrder           検索結果のソート対象カラム
     * @return  self
     */
    public function setSearchConditionForCustomerList($arrSearchCondition, $arrColumn = array("*"), $arrOrder = "") {

        // 初期設定
        $objSelect = &$this->objSlaveDb->select()->from(array("c" => $this->getTableName()), $arrColumn);
        
        // 都道府県マスタとの結合
        $objSelect = &$objSelect->joinLeft(array("mp" => "m_pref"),
            "c.d_customer_PrefCode = mp.m_pref_PrefCode", array(""));

        // 受注テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("o" => "d_order"),
                "c.d_customer_CustomerID = o.d_order_CustomerID", array());
        
        // 顧客ID
        $stFormKey = "d_customer_CustomerID";
        $stDBKey = "d_customer_CustomerID";
        $stDBAlias = "c.";
        if ($arrSearchCondition[$stFormKey] != "") {
            if (strpos($arrSearchCondition[$stFormKey], ",")) {
                // 検索条件にカンマ(,)が含まれている場合は、OR条件とする
                $arrIDs = array();
                $arrIDs = explode(",", $arrSearchCondition[$stFormKey]);
                $stIDs = "(";
                foreach ($arrIDs as $key => $value) {
                     $stIDs .= "'" . $value . "',";
                }
                $stIDs = rtrim($stIDs, ",");
                $stIDs .= ")";
                $objSelect->where($stDBAlias . "$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("cast(" . $stDBAlias . "$stDBKey as SIGNED) >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("cast(" . $stDBAlias . "$stDBKey as SIGNED) <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where($stDBAlias . "$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // ソート順
        if (!empty($arrOrder)) {
            if (!is_array($arrOrder)) {
                $arrOrder = array($arrOrder);
            }
        } else {
            $arrOrder = array("d_customer_CustomerID DESC");
        }
        foreach ($arrOrder as $value) {
            $objSelect->order($value);
        }
        
        // 顧客IDでGROUP BY
        $objSelect->group("d_customer_CustomerID");
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("d_customer_DelFlg = ? ", 0);

        $this->_objSelect = $objSelect;

        return $this;
    }
    
    /**
     * メンバ変数に格納された検索条件格納オブジェクトを返す。
     * 
     * @return  object
     */
    public function getSearchCondition() {
        return $this->_objSelect;
    }

    /**
     * 第一引数で指定された検索結果をメンバ変数に格納する。
     * 
     * @return  self
     */
    public function setSearchResult($arrSearchResult) {
        $this->_arrSearchResult = $arrSearchResult;
        return $this;
    }

    /**
     * setSearchResult()でメンバ変数に格納された検索結果を返す。
     * 
     * @return  object
     */
    public function getSearchResult() {
        return $this->_arrSearchResult;
    }
    
    /**
     * 所持ポイントを取得する
     * 
     * @param   array   $arrColumn              取得対象のカラム名を格納した配列
     * @return  array   $arrResult              検索結果
     */
    public function getTotalPoint($arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("sum(d_customer_Point) as d_customer_Point, sum(d_customer_LimitedPoint) as d_customer_LimitedPoint")));

            $objSelect->where("d_customer_DelFlg = ? ", 0);
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }
    
    /**
     * 第一引数に紐づく最大値データを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrSearchCondition     検索条件
     * @return  array   $arrResult              検索結果
     */
    public function getMemberCount($arrSearchCondition) {
        
        try {
            
            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("count(d_customer_CustomerID) AS memberCount")));
//            $objSelect->where("d_customer_CustomerClassID = ? ", Application_Model_CustomerClass::MEMBER);
            $objSelect->where("d_customer_DelFlg = ? ", 0);
            
            // 登録日
            $stDBKey = "d_customer_CreatedTime";
            if ($arrSearchCondition["d_customer_CreatedTime"] != "") {
                $objSelect->where("$stDBKey >= ?", $arrSearchCondition["d_customer_CreatedTime"] . " 00:00:00");
                $objSelect->where("$stDBKey <= ?", $arrSearchCondition["d_customer_CreatedTime"] . " 23:59:59");
            }
            
            // select文を実行する
            $objSql = $this->objSlaveDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @return  self
     */
    public function setSearchConditionForCustomerDupCheck() {

        // 初期設定
        $arrColumn = array(
            "c.d_customer_EmailAddress",
            "count(c.d_customer_EmailAddress) AS CustomerCount",
        );
        
        $objSelect = &$this->objSlaveDb->select()->from(array("c" => $this->getTableName()), $arrColumn);

        // メールアドレスでGROUP BY
        $objSelect->group("d_customer_EmailAddress");
        
        // 会員であること
//        $objSelect->where("d_customer_CustomerClassID = ? ", Application_Model_CustomerClass::MEMBER);

        // 退会者は対象外
        $objSelect->where("d_customer_SignedOut != ? ", 1);
        
        // 検索対象は削除フラグ = 0とする
        $objSelect->where("d_customer_DelFlg = ? ", 0);
        
        // メールアドレスが重複している
        $objSelect->having("count(c.d_customer_EmailAddress) > 1");
        
        $this->_objSelect = $objSelect;
        
        return $this;
    }
    
}
