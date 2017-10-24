<?php
/**
 * HTTP接続用クラス
 *
 * @category   ECSite
 * @package    Library
 * @subpackage Http
 * @copyright  Copyright (c) 2014 Majestic-PIC Co., Ltd.
 * @author     M-PIC鈴木
 * @version    v1.0
 */
$debug = 0;

if($debug == 1) {
    $Url = SITE_URL . "debug/get.php";
    $arrForm = array("test1" => "val1",
                     "test2" => "val2",
                     "date" => date("Y-m-d H:i:s"));
    $objHttp = new Http();
    $objHttp->Request($Url, $arrForm, $arrHeaders);
    $objHttp->Redirect("http://google.com");
}

class Http {
    // default.ini
    private $objIni;
    private $objMessage;
    private $objBasicCheck;
    
    // 初期化
    public function init() {
        $this->objMessage = new Message();
        $this->objBasicCheck = new BasicCheck();
    }
    
    public function setMethod($stMethod) {
        $this->stMethod = $stMethod;
    }
    
    public function getMethod() {
        if(isset($this->stMethod)) {
            return $this->stMethod;
        } else {
            return false;
        }
    }
    
    public function Redirect($stURL) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetUrl($stURL)) {
                header("Location: $stURL");
                exit;
            } else {
                throw new Zend_Exception('$stURLが空です。');
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function Request($stURL, $arrData = null, $arrHeaders = null, $stIniFileName = "default.ini") {
        try {
            $this->init();
            
            // 設定ファイル読み込み
            $stIniFile = $this->makeIniFilePath($stIniFileName);
            if($this->objBasicCheck->isExistFile($stIniFile)) {
                $arrConfig = new Zend_Config_Ini($stIniFile, 'http');
            } else {
                throw new Zend_Exception("指定された設定ファイルが存在しません。");
            }
            
            // HTTPリクエスト開始
            if($this->objBasicCheck->isSetUrl($stURL)) {
                if($this->objBasicCheck->isSetArray($arrConfig)) {
                    // 初期化
                    $objClient = new Zend_Http_Client();
                    $objClient->setUri($stURL);
                    $objClient->setConfig($arrConfig);
                    // 送信用データセット & 送信
                    if($this->getMethod()) {
                        $stMethod = $this->getMethod();
                    } else {
                        $stMethod = $arrConfig->method;
                    }
                    // メソッド別にパラメータセット
                    if($stMethod == "POST") {
                        $objClient->setParameterPost($arrData);
                        // 送信
                        $Response = $objClient->request($stMethod);
                    } elseif($stMethod == "GET") {
                        $Response = $objClient->request($stMethod);
                    } else {
                        throw new Zend_Exception("HTTPリクエスト用メソッドが指定されていません。");
                    }

                    return $Response->getBody();
                } else {
                    throw new Zend_Exception("HTTPリクエスト用の設定が渡されていません。");
                }
            } else {
                throw new Zend_Exception("HTTPリクエスト先のURLが渡されていません。");
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function makeIniFilePath($stIniFileName) {
        try {
            $this->init();
            if($this->objBasicCheck->isSetStrings($stIniFileName)) {
                $stIniFile = INI_FILE_PATH . "config/http/" . $stIniFileName;
                return $stIniFile;
            } else {
                throw new Zend_Exception("INIファイル名が指定されていません。");
            }
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
        
    public function makeBasicConfig() {
        $arrConfig = array('maxredirects' => 0,
                           'timeout'      => 60);
        return $arrConfig;
    }
    
    // キャッシュの有効化
    public function allowClientCache() {
        session_cache_limiter('private-no-expire');
    }

    // 古いキャッシュを使用せず、戻るボタンも有効に動作できるようにする
    public function allowClientCacheCurrent() {
        header("Cache-Control: no-cache, must-revalidate");
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    }

    // キャッシュの無効化
    public function denyClientCache() {
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    }
}
