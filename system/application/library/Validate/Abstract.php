<?php
/**
 * バリデート処理抽象化クラス
 *
 * @category   ECSite
 * @package    Validate
 * @subpackage Abstract
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     鈴木
 * @version    v1.0
 */
abstract class Validate_Abstract {
    /**
     * カラム名をセットする。
     *
     * @access public
     * @param  string    $stColumn    カラム名
     * @return self
     */
    public function setColumn($stColumn) {
        $this->_stColumn = $mixParam;
        return $this;
    }
    
    /**
     * セットしたカラム名を取得する。
     *
     * @access public
     * @return string
     */
    public function getColumn() {
        return $this->_stColumn;
    }
    
    /**
     * 項目名をセットする。
     *
     * @access public
     * @param  string    $stColumnName    項目名
     * @return self
     */
    public function setColumnName($stColumnName) {
        $this->_stColumnName = $stColumnName;
        return $this;
    }
    
    /**
     * セットした項目名を取得する。
     *
     * @access public
     * @return string
     */
    public function getColumnName() {
        return $this->_stColumnName;
    }
    
    /**
     * オプション項目名をセットする。
     *
     * @access public
     * @param  string    $stColumnName    オプション項目名
     * @return self
     */
    public function setOptionColumnName($stOptionColumnName) {
        $this->_stOptionColumnName = $stOptionColumnName;
        return $this;
    }
    
    /**
     * セットしたオプション項目名を取得する。
     *
     * @access public
     * @return string
     */
    public function getOptionColumnName() {
        return $this->_stOptionColumnName;
    }    
    /**
     * パラメータをセットする。
     *
     * @access public
     * @param  mixed    $mixParam    パラメータ
     * @return self
     */
    public function setParam($mixParam) {
        $this->_mixParam = $mixParam;
        return $this;
    }
    
    /**
     * セットしたパラメータを取得する。
     *
     * @access public
     * @return mixed
     */
    public function getParam() {
        return $this->_mixParam;
    }
    
    /**
     * オプションパラメータをセットする。
     *
     * @access public
     * @param  mixed    $mixParam    パラメータ
     * @return self
     */
    public function setOptionParam($mixOptionParam) {
        $this->_mixOptionParam = $mixOptionParam;
        return $this;
    }
    
    /**
     * セットしたオプションパラメータを取得する。
     *
     * @access public
     * @return mixed
     */
    public function getOptionParam() {
        return $this->_mixOptionParam;
    }
        
    /**
     * 始点をセットする。
     *
     * @access public
     * @param  mixed    $mixFrom    始点
     * @return self
     */
    public function setFrom($mixFrom) {
        $this->_mixFrom = $mixFrom;
        return $this;
    }
    
    /**
     * セットした始点を取得する。
     *
     * @access public
     * @return mixed
     */
    public function getFrom() {
        return $this->_mixFrom;
    }
    
    /**
     * 終点をセットする。
     *
     * @access public
     * @param  mixed    $mixTo    終点
     * @return self
     */
    public function setTo($mixTo) {
        $this->_mixTo = $mixTo;
        return $this;
    }
    
    /**
     * セットした終点を取得する。
     *
     * @access public
     * @return mixed
     */
    public function getTo() {
        return $this->_mixTo;
    }
    
    /**
     * 閾値をセットする。
     *
     * @access public
     * @param  int    $iThreshold    閾値
     * @return self
     */
    public function setThreshold($iThreshold) {
        $this->_iThreshold = $iThreshold;
        return $this;
    }
    
    /**
     * セットした閾値を取得する。
     *
     * @access public
     * @return int
     */
    public function getThreshold() {
        return $this->_iThreshold;
    }
    
    /**
     * エラーメッセージをセットする。
     *
     * @access public
     * @param  string    $stKey    エラーメッセージ格納配列用添え字
     * @return self
     */
    public function setMessage($stKey) {
        $stMessage = $this->_arrMessageTemplates[$stKey];
        $stMessage = str_replace("%ColumnName%", $this->getColumnName(), $stMessage);
        $stMessage = str_replace("%OptionColumnName%", $this->getOptionColumnName(), $stMessage);
        $stMessage = str_replace("%From%", $this->getFrom(), $stMessage);
        $stMessage = str_replace("%To%", $this->getTo(), $stMessage);
        $stMessage = str_replace("%Threshold%", $this->getThreshold(), $stMessage);
        $stMessage = str_replace("%Param%", $this->getParam(), $stMessage);
        
        $this->_stMessage = $stMessage;
        
        return $this;
    }
    
    /**
     * セットしたエラーメッセージを取得する。
     *
     * @access public
     * @return string
     */
    public function getMessage() {
        return $this->_stMessage;
    }
    
    /**
     * エラーチェック処理(実装必須)
     *
     * @access public
     * @return mixed
     */
    abstract public function isValid();
}