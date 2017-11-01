<?php

/**
 * 受注テーブル用モデル
 *
 * @author     
 * @version    v1.0
 */
class Application_Model_OrderSheet extends Application_Model_Abstract {

    /**
     * テーブル名
     * @var
     */
    protected $_stTableName = "d_order_sheet";

    // クラス定数宣言
    
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
        $this->objCommon = new Common();
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
     * 第一引数の配列から主キーを抽出し、
     * 主キーに紐づくデータ第一引数の値で更新する。
     * 
     * @param   array   $arrData    更新対象のカラム名と値の連想配列
     * @return  self
     */
    public function save($arrData) {
        
        try {
            if (count($arrData) == 0) {
                throw new Zend_Exception('$arrDataが空です。');
            }

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams["d_order_sheet_UpdatedTime"] = date("Y-m-d H:i:s");

            $stWhere = $this->objMasterDb->quoteInto("d_order_sheet_OrderSheetID = ? ", $arrData["d_order_sheet_OrderSheetID"]);
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
            $objSelect->where("d_order_sheet_DelFlg = ? ", 0);
            $objSelect->where("d_order_sheet_OrderSheetID = ? ", $iID);

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
     * 第一引数に紐づくデータを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   integer $iID        ID(PK)
     * @param   array   $arrColumn  取得対象のカラム名を格納した配列
     * @return  array   $arrResult  検索結果
     */
    public function findFromMaster($iID, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objMasterDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_sheet_DelFlg = ? ", 0);
            $objSelect->where("d_order_sheet_OrderSheetID = ? ", $iID);

            // select文を実行する
            $objSql = $this->objMasterDb->query($objSelect);
            
            // 検索結果を $arrResultに格納
            $arrResult = $objSql->fetch();
            
            return $arrResult;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * 第一引数に検索条件を指定する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 第三引数が指定されている場合、その配列を並び順とする。
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
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_sheet_DelFlg = ? ", 0);
            
            if ($arrWhere) {
                foreach ($arrWhere as $key => $value) {
                    $objSelect->where($key . " = ? ", $value);
                }
            }
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_order_sheet_OrderSheetID DESC");
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
     * 第一引数に検索条件を指定する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 第三引数が指定されている場合、その配列を並び順とする。
     * 
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrOrder     ソートカラム名を格納した配列
     * @param   int     $iLimit       取得制限
     * @return  array   $arrResult    検索結果
     */
    public function findLimit($arrWhere, $arrColumn = "", $arrOrder = "", $iLimit = "") {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_sheet_DelFlg = ? ", 0);
            
            if ($arrWhere) {
                foreach ($arrWhere as $key => $value) {
                    $objSelect->where($key . " = ? ", $value);
                }
            }
            
            // 取得件数制限
            if ($iLimit) {
                $objSelect->limit($iLimit);
            }
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_order_sheet_OrderSheetID DESC");
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
     * 第一引数に検索条件を指定する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 第三引数が指定されている場合、その配列を並び順とする。
     * 紐づくデータを更新系から取得する
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
            
            $objSelect = &$this->objMasterDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_sheet_DelFlg = ? ", 0);
            
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
            } else {
                $objSelect->order("d_order_sheet_OrderSheetID DESC");
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
            $objSelect->where("d_order_sheet_DelFlg = ? ", 0);
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_order_sheet_OrderSheetID DESC");
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
     * @param   int     $iLimit     取得件数制限
     * @return  array   $arrResult  検索結果
     */
    public function fetchLimit($arrColumn = "", $arrOrder = "", $iLimit = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_order_sheet_DelFlg = ? ", 0);
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_order_sheet_OrderSheetID DESC");
            }
            
            // 取得件数制限
            if ($iLimit) {
                $objSelect->limit($iLimit);
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
     * @return  integer             最後に自動生成されたID
     */
    public function insert($arrData) {
        
        try {
            if (count($arrData) == 0) {
                throw new Zend_Exception('$arrDataが空です。');
            }

            $stTable = $this->getTableName();
            $arrParams = $arrData;
            $arrParams["d_order_sheet_CreatedTime"] = date("Y-m-d H:i:s");
            $arrParams["d_order_sheet_DelFlg"] = "0";
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
                throw new Zend_Exception('$IDを指定してください。');
            }

            $stTable = $this->getTableName();
            // 更新内容
            $arrParams["d_order_sheet_DelFlg"] = "1";
            $arrParams["d_order_sheet_UpdatedTime"] = date("Y-m-d H:i:s");
            // 更新条件
            $arrWhere["d_order_sheet_OrderSheetID = ?"] = $iID;
            $arrWhere["d_order_sheet_DelFlg = ?"]  = "0";
            $this->objMasterDb->update($stTable, $arrParams, $arrWhere);
           
            return $this;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }

    /**
     * setSearchCondition()でセットされた検索条件を元に、
     * 検索処理を実行して結果を返す。
     * 検索結果はsetSearchResult()によりメンバ変数に格納される。
     * 
     * @param   boolean $bLimit     1ページあたりの表示件数の適用有無 (true:適用する, false:適用しない)
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
            $objSelect->columns('COUNT(DISTINCT ' .  "`$stAlias`" . '.`d_order_sheet_OrderSheetID`) AS cnt');
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
            } else {
                throw new Zend_Exception("検索処理を先に実行してください。");
            }
            
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
     * @param   array   $arrGroup           GROUP BY 配列
     * @return  self
     */
    public function setSearchCondition($arrSearchCondition, $arrColumn = array("*"), $arrGroup = array()) {

        $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        // 顧客テーブルとの結合
        $objSelect = &$objSelect->joinLeft(array("c" => "d_customer"),
                "o.d_order_sheet_CustomerID = c.d_customer_CustomerID", array());
        
        // Where句セット
        // 顧客ID
        $stFormKey = "d_order_sheet_CustomerID";
        $stDBKey = "d_order_sheet_CustomerID";
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
                $objSelect->where("o.$stDBKey IN " . $stIDs);
            } elseif (strpos($arrSearchCondition[$stFormKey], "-") !== false) {
                // 検索条件にハイフン(-)が含まれている場合は、範囲検索とする
                $arrPregMatch = array();
                preg_match("/([0-9]+)?-([0-9]+)?/",$arrSearchCondition[$stFormKey], $arrPregMatch);
                if ($arrPregMatch[1]) {
                    $objSelect->where("o.$stDBKey >= ? ", $arrPregMatch[1]);
                }
                if ($arrPregMatch[2]) {
                    $objSelect->where("o.$stDBKey <= ? ", $arrPregMatch[2]);
                }
            } else {
                // 含まれていない場合
                $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
            }
        }
        
        // 検索ワード
        $stFormKey = "keyword";
        $arrSearchCondition[$stFormKey] = mb_convert_kana($arrSearchCondition[$stFormKey], 's'); // 全角スペースを半角スペースに置換
        $arrKeyword = explode(" ", $arrSearchCondition[$stFormKey]);
        if ($arrKeyword[0] != "") {
            $iKeywordCount = count($arrKeyword);

            // 検索キーワード用
            foreach ($arrKeyword as $key => $value) {
                if ($key == 0) {
                    if ($iKeywordCount == 1) {
                        $objSelect->where("o.d_order_sheet_Keyword collate utf8_unicode_ci LIKE ? ", "%" . $value . "%");
                    } else {
                        $objSelect->where("( o.d_order_sheet_Keyword collate utf8_unicode_ci LIKE ? ", "%" . $value . "%");
                    }
                } else if ($key == $iKeywordCount - 1) {
                    $objSelect->orwhere("o.d_order_sheet_Keyword collate utf8_unicode_ci LIKE ? )", "%" . $value . "%");
                } else {
                    $objSelect->orwhere("o.d_order_sheet_Keyword collate utf8_unicode_ci LIKE ? ", "%" . $value . "%");
                }
            }
        }

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_sheet_DelFlg = ? ", 0);

        // OrderSheetIDでGROUP BY
        if (empty($arrGroup)) {
            $objSelect->group("o.d_order_sheet_OrderSheetID");
        } else {
            foreach ($arrGroup as $key => $value) {
                $objSelect->group($value);
            }
        }

        // 表示順
        $objSelect->order("o.d_order_sheet_OrderSheetID DESC");

        $this->_objSelect = $objSelect;
        return $this;
    }
    
    /**
     * 第一引数の配列を元に、検索条件を格納したオブジェクトを生成し、
     * setSearchCondition()を実行する。
     * 
     * @param   array   $arrSearchCondition 検索条件フォーム配列
     * @param   array   $arrColumn          取得対象のカラム名を格納した配列
     * @param   array   $arrGroup           GROUP BY 配列
     * @return  self
     */
    public function setSearchConditionForMypage ($arrSearchCondition, $arrColumn = array("*"), $arrGroup = array()) {

       $objSelect = &$this->objSlaveDb->select()->from(array("o" => $this->getTableName()), $arrColumn);
        
        // Where句セット
        // 顧客ID
        $stFormKey = "d_order_sheet_CustomerID";
        $stDBKey = "d_order_sheet_CustomerID";
        if ($arrSearchCondition[$stFormKey] != "") {
            $objSelect->where("o.$stDBKey = ? ", $arrSearchCondition[$stFormKey]);
        }

        // 検索対象は削除フラグ = 0とする
        $objSelect->where("o.d_order_sheet_DelFlg = ? ", 0);
        
        // OrderSheetIDでGROUP BY
        if (empty($arrGroup)) {
            $objSelect->group("o.d_order_sheet_OrderSheetID");
        } else {
            foreach ($arrGroup as $key => $value) {
                $objSelect->group($value);
            }
        }

        // 表示順
        $objSelect->order("o.d_order_sheet_OrderSheetID DESC");
        
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
}