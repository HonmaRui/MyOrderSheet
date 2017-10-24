<?php
/**
 * UserAgent取得関連クラス
 *
 * @author     M-PIC鈴木
 * @version    v1.0
 */

//require_once('Net/UserAgent/Mobile.php');

class UserAgent {

    // const
    const PC_DIR = "/";
    const SMART_PHONE_DIR = "/s/";
    const ADMIN_DIR = "/admin/";
    
    const PC = "Front/";
    const SMART_PHONE = "SmartPhone/";
    const ADMIN = "Admin/";
    
    // 初期化
    function __construct() {
//        // Net_UserAgent_Mobile
//        $this->objAgent = Net_UserAgent_Mobile::singleton();
        
        // UserAgent
        $this->UserAgent = $_SERVER["HTTP_USER_AGENT"];
        // RequestUri
        $this->RequestUri = $_SERVER["REQUEST_URI"];
        // QueryString
        $this->QueryString = $_SERVER["QUERY_STRING"];
        
//        // スマートフォン用配列
//        $this->arrSmartPhone = array('iPhone',         // Apple iPhone
//                                     'iPod',           // Apple iPod touch
//                                     'Android',        // 1.5+ Android
//                                     'dream',          // Pre 1.5 Android
//                                     'CUPCAKE',        // 1.5+ Android
//                                     'blackberry9500', // Storm
//                                     'blackberry9530', // Storm
//                                     'blackberry9520', // Storm v2
//                                     'blackberry9550', // Storm v2
//                                     'blackberry9800', // Torch
//                                     'webOS',          // Palm Pre Experimental
//                                     'incognito',      // Other iPhone browser
//                                     'webmate'         // Other iPhone browser
//                                    );
//        // タブレット用配列
//        $this->arrTablet = array("iPod",
//                                 "iPad",
//                                 "Android" 
//                                );
        
//        /** init **/
//        if(isset($_SERVER["HTTPS"])) {
//            $this->HTTPS = true;
//        } else {
//            $this->HTTPS = false;
//        }
//        // AplicationPath
//        $this->ApplicationPath = "";
//        // TemplatePath
//        $this->TemplatePath = "";
//        // RootPath
//        $this->RootPath = APPLICATION_PATH;
//        // ClientPath
//       $this->ClientPath = CLIENT_PATH;
//        // SiteUrl
//        $this->SiteUrl = SITE_URL;
//        // SSLUrl
//        $this->SSLUrl = SSL_URL;
//        // UrlDir
//        $this->UrlDir = $this->getUrlDir();
//        // BaseUrl
//        if($this->HTTPS) {
//            $this->BaseUrl = $this->SSLUrl;
//        } else {
//            $this->BaseUrl = $this->SiteUrl;
//        }
        
    }
    
//    public function useMobileForward() {
//        if(defined("USE_MOBILE_FORWARD")) {
//            return USE_MOBILE_FORWARD;
//        } else {
//            return true;
//        }
//    }
    
//    public function useSmartPhoneForward() {
//        if(defined("USE_SMART_PHONE_FORWARD")) {
//            return USE_SMART_PHONE_FORWARD;
//        } else {
//            return true;
//        }
//    }
    
//    public function setTerminalID() {
//        if($this->isMobile()) {
//            if(!isset($_SESSION["TerminalID"])) {
//                if($this->objAgent->getUID() != null) {
//                    $_SESSION["TerminalID"] = $this->objAgent->getUID();
//                }
//            }
//        }
//    }
    
//    public function getTerminalID() {
//        if(isset($_SESSION["TerminalID"])) {
//            return $_SESSION["TerminalID"];
//        } else {
//            if($this->isMobile()) {
//                return $this->objAgent->getUID();
//            } else {
//                return false;
//            }
//        }
//    }
    
//    public function makeUrl() {
//        /**
//         * QUERY_STRING整形
//         * -- ドコモ用にguid=ONを付与する --
//         */
//        if($this->isMobile() == "DoCoMo") {
//            $stQueryString = "?guid=ON&" . $this->QueryString;
//        } else {
//            $stQueryString = "?" . $this->QueryString;
//        }
//        
//        // REQUEST_URI整形
//        $stRequestUri = $this->remakeUri($this->RequestUri);
//        if(preg_match("/^\/(.*)/", $stRequestUri, $matches)) {
//            $stRequestUri = $matches[1];
//        }
//        // UrlDir整形
//        $stUrlDir = $this->UrlDir;
//        // URL生成
//        //echo "[makeUrl]" . "\n";
//        //echo "[BaseUrl] ". $this->BaseUrl . "\n";
//        //echo "[UrlDir] ". $stUrlDir . "\n";
//        //echo "[RequestUri] ". $stRequestUri . "\n";
//        //echo "[RedirectUrl] ". $this->BaseUrl . $stUrlDir . $stRequestUri . "\n";
//        //echo "\n";
//        
//        if(empty($stUrl)) {
//            return $this->BaseUrl . $stUrlDir . $stRequestUri;
//        } elseif(!empty($stQueryString)) {
//            return $this->BaseUrl . $stUrlDir . $stRequestUri. $stQueryString;
//        } else {
//            return $this->BaseUrl . $stUrlDir . $stRequestUri;
//        }
//        
//        return false;
//    }
    
//    public function Normalization() {
//        // 正規表現
//        $stMobilePattern = "/^\/m\//";
//        $stSmartPhonePattern = "/^\/s\//";
//        $stAdminPattern = "/^\/admin\//";
//        
//        // 転送先URL
//        $stRedirectUrl = $this->makeUrl();
//        //echo "[Normalization]" . "\n";
//        //echo "[stRedirectUrl] " . $this->makeUrl() . "\n";
//        // アクセス元URL
//        $stRequestUri = $this->RequestUri;
//        $stUrl = $this->BaseUrl . $stRequestUri;
//        //echo "[BaseUrl] ". $this->BaseUrl . "\n";
//        //echo "[UrlDir] ". $this->UrlDir . "\n";
//        //echo "[RequestUri] ". $this->RequestUri . "\n";
//        //echo "[stUrl] ". $stUrl . "\n";
//        
//        // 転送処理実行
//        if($stRedirectUrl == $stUrl) {
//            return true;
//        } else {
//            // 転送処理
//            header("Location: " . $stRedirectUrl);
//            exit;
//        }
//    }
    
//    public function remakeUri($stRequestUri) {
//        // REQUEST_URI整形
//        $stMobileDir = self::MOBILE_DIR;
//        $stSmartPhoneDir = self::SMART_PHONE_DIR;
//        $stRequestUri = $this->RequestUri;
//        $stRequestUri = str_replace($stMobileDir, "", $stRequestUri);
//        $stRequestUri = str_replace($stSmartPhoneDir, "", $stRequestUri);
//        //var_dump("remakeUri",$stRequestUri);
//        return $stRequestUri;
//    }
    
//    public function getApplicationPath() {
//        if($this->isMobile() && $this->useMobileForward()) {
//            $this->ApplicationPath = $this->ClientPath . self::MOBILE;
//        } elseif($this->isSmartPhone() && $this->useSmartPhoneForward()) {
//            $this->ApplicationPath = $this->ClientPath . self::SMART_PHONE;
//        } elseif($this->isAdmin()) {
//            $this->ApplicationPath = $this->ClientPath . self::ADMIN;
//        } else {
//            $this->ApplicationPath = $this->ClientPath . self::PC;
//        }
//        
//        return $this->ApplicationPath;
//    }
    
//    public function getTemplatePath() {
//        if($this->isMobile() && $this->useMobileForward()) {
//            $this->TemplatePath = $this->ClientPath . self::MOBILE;
//        } elseif($this->isSmartPhone() && $this->useSmartPhoneForward()) {
//            $this->TemplatePath = $this->ClientPath . self::SMART_PHONE;
//        } elseif($this->isAdmin()) {
//            $this->TemplatePath = $this->ClientPath . self::ADMIN;
//        } else {
//            $this->TemplatePath = $this->ClientPath . self::PC;
//        }
//        
//        return $this->TemplatePath;
//    }
    
//    public function getBlockPath() {
//        if($this->isMobile() && $this->useMobileForward()) {
//            $this->BlockPath = $this->ClientPath . self::MOBILE_BLOCK;
//        } elseif($this->isSmartPhone() && $this->useSmartPhoneForward()) {
//            $this->BlockPath = $this->ClientPath . self::SMART_PHONE_BLOCK;
//        } elseif($this->isAdmin()) {
//            $this->BlockPath = $this->ClientPath . self::ADMIN_BLOCK;
//        } else {
//            $this->BlockPath = $this->ClientPath . self::PC_BLOCK;
//        }
//        
//        return $this->BlockPath;
//    }
    
//    public function getUrlDir() {
//        if($this->isMobile() && $this->useMobileForward()) {
//            $stUrlDir = self::MOBILE_DIR;
//        } elseif($this->isSmartPhone() && $this->useSmartPhoneForward()) {
//            $stUrlDir = self::SMART_PHONE_DIR;
//        } else {
//            $stUrlDir = self::PC_DIR;
//        }
//        
//        return $stUrlDir;
//    }
    
//    public function isMobile() {
//        switch(true) {
//            case ($this->objAgent->isDoCoMo()):   // DoCoMoかどうか
//                if( $this->objAgent->isFOMA() ) return "DoCoMo";
//            break;
//            case ($this->objAgent->isVodafone()): // softbankかどうか
//                if( $this->objAgent->isType3GC() ) return "Softbank";
//            break;
//            case ($this->objAgent->isEZweb()):    // ezwebかどうか
//                if( $this->objAgent->isWIN() ) return "EZweb";
//            break;
//            default:
//              return false;
//            break;
//        }
//    }
    
//    // FIX ME
//    public function isSmartPhone() {
//        // スマートフォンチェック
//        foreach( $this->arrSmartPhone as $key => $val ){
//            // 該当端末にキャリアが含まれているか判定
//            if(preg_match("/".$val."/", $this->UserAgent)){
//                return $val;
//            }
//        }
//        
//        // タブレットチェック
//        foreach( $this->arrTablet as $key => $val ){
//            // 該当端末にキャリアが含まれているか判定
//            if(preg_match("/".$val."/", $this->UserAgent)){
//                return $val;
//            }
//        }
//        
//        // それ以外
//        return false;
//    }

    public function isAdmin() {
        if(preg_match("/^\/admin/", $this->RequestUri)) {
            return true;
        }
        
        // それ以外
        return false;
    }
}
