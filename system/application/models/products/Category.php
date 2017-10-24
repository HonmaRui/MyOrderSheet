<?php

/**
 * カテゴリテーブル用モデル
 *
 * @author     M-PIC柿崎
 * @version    v1.0
 */
class Application_Model_Category extends Application_Model_Abstract {

    /**
     * テーブル名
     * @var
     */
    protected $_stTableName = "d_category";

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
            $arrParams["d_category_UpdatedTime"] = date("Y-m-d H:i:s");
            $arrParams["d_category_UpdatedByID"] = $this->objAdminSess->MemberID;
            $stWhere = $this->objMasterDb->quoteInto("d_category_CategoryID = ? ", $arrData["d_category_CategoryID"]);
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
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @return  array   $arrResult    検索結果
     */
    public function find($arrWhere, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_category_DelFlg = ? ", 0);
            
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
     * 第一引数に紐づくデータを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrWhere     検索条件のキーと値の連想配列
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @param   array   $arrOrder     ソートカラム名を格納した配列
     * @param   bool    $bIsFront     フロントでの呼び出しかどうか
     * @return  array   $arrResult    検索結果
     */
    public function findAll($arrWhere, $arrColumn = "", $arrOrder = "", $bIsFront = false) {

        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_category_DelFlg = ? ", 0);
            
            // フロントでは隠しカテゴリを検索対象としない
            if ($bIsFront) {
                $objSelect->where("d_category_FrontNoDisp IS NULL");
            }
            
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
                $objSelect->order("d_category_Rank ASC");
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
     * @param   array   $arrColumn   取得対象のカラム名を格納した配列
     * @param   array   $arrOrder    ソートカラム名配列
     * @return  array   $arrResult   検索結果
     */
    public function fetchAll($arrColumn = "", $arrOrder = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            $objSelect->where("d_category_DelFlg = ? ", 0);
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_category_Rank ASC");
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
     * 全て取得する。
     * 第一引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   array   $arrColumn   取得対象のカラム名を格納した配列
     * @param   array   $arrOrder    ソートカラム名配列
     * @return  array   $arrResult   検索結果
     */
    public function fetchAllincludeDel($arrColumn = "", $arrOrder = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(array($this->getTableName()), $arrColumn);
            
            // ソート順
            if (!empty($arrOrder)) {
                if (!is_array($arrOrder)) {
                    $arrOrder = array($arrOrder);
                }
                foreach ($arrOrder as $value) {
                    $objSelect->order($value);
                }
            } else {
                $objSelect->order("d_category_Rank ASC");
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
     * 第一引数に紐づく最大値データを取得する。
     * 第二引数が指定されている場合、その配列を取得対象カラムとする。
     * 
     * @param   string  $stMaxColumn  最大値を取得するカラム
     * @param   array   $arrColumn    取得対象のカラム名を格納した配列
     * @return  array   $arrResult    検索結果
     */
    public function max($stMaxColumn, $arrColumn = "") {
        
        try {
            // 取得カラムの指定が無ければ全てのカラムを取得する。
            if (!is_array($arrColumn)) {
                $arrColumn = array("*");
            }
            
            $objSelect = &$this->objSlaveDb->select()->from(
                array($this->getTableName()), array(new Zend_Db_Expr("max(" . $stMaxColumn . ")")));
            $objSelect->where("d_category_DelFlg = ? ", 0);
            
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
            $arrParams["d_category_CreatedTime"] = date("Y.m.d H:i:s");
            $arrParams["d_category_CreatedByID"] = $this->objAdminSess->MemberID;
            $arrParams["d_category_UpdatedByID"] = $this->objAdminSess->MemberID;
            $arrParams["d_category_DelFlg"] = "0";
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
            $arrParams["d_category_DelFlg"] = 1;
            $arrParams["d_category_UpdatedTime"] = date("Y-m-d H:i:s");
            $arrParams["d_category_UpdatedByID"] = $this->objAdminSess->MemberID;
            // 更新条件
            $arrWhere["d_category_CategoryID = ?"] = $iID;
            $arrWhere["d_category_DelFlg = ?"]  = 0;
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
     * 指定されたカテゴリIDを親に持つカテゴリID群を抽出する
     * 
     * @param   array   $iCategotyID    カテゴリID
     * @param   string  $stIDs          カテゴリID群(カンマ区切り)
     * @return  string  $stIDs          カテゴリID群(カンマ区切り)
     */
    public function getChildCategoryIDs($iCategotyID, $stIDs = "") {
        
        try {
            if ($iCategotyID == "" || $iCategotyID < 0) {
                throw new Zend_Exception('$iCategoryIDを指定してください。');
            }
            
            if ($stIDs == "") {
                $stIDs = $iCategotyID . ",";
            }
            
            $arrResult = $this->findAll(array("d_category_ParentCategoryID" => $iCategotyID), array("d_category_CategoryID"));
            
            foreach ($arrResult as $value) {
                $stIDs .= $value["d_category_CategoryID"];
                $stIDs .= ",";
                $stIDs = $this->getChildCategoryIDs($value["d_category_CategoryID"], $stIDs);
            }
            return $stIDs;
            
        } catch (Zend_Exception $e) {
            throw new Zend_Exception($e->getMessage());
        }
    }     
}