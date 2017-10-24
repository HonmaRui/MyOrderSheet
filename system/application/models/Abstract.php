<?php
/**
 * モデル抽象化用クラス
 */
abstract class Application_Model_Abstract {
    /**
     * テーブル名
     */
    protected $_stTableName;
    /**
     * 表示件数
     */
    protected $_iPageLimit;
    /**
     * ページ番号
     */
    protected $_iPageNumber;
    
    /**
     * テーブル名を取得する。
     *
     * @return string
     */
    public function getTableName() {
        return $this->_stTableName;
    }
    
    /**
     * 表示件数をセットする。
     *
     * @param  int    $iPageLimit    表示件数
     * @return self
     */
    public function setPageLimit($iPageLimit) {
        $this->_iPageLimit = $iPageLimit;
        return $this;
    }
    
    /**
     * 表示件数を取得する。
     *
     * @return int
     */
    public function getPageLimit() {
        return $this->_iPageLimit;
    }
    
    /**
     * ページ番号をセットする。
     *
     * @param  int    $iPageNumber    ページ番号
     * @return self
     */
    public function setPageNumber($iPageNumber) {
        $this->_iPageNumber = $iPageNumber;
        return $this;
    }
    
    /**
     * ページ番号を取得する。
     *
     * @return int
     */
    public function getPageNumber() {
        return $this->_iPageNumber;
    }
}