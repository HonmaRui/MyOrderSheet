<?php

    // Model Path
    define('MODEL_PATH', APPLICATION_PATH . "/models/");
    // Library Path
    define('COMMON_LIB_PATH', APPLICATION_PATH . "/library/");

    define("LOCAL_BASE_PATH", dirname(dirname(dirname(__FILE__))));
    define("LOG_PATH", dirname(dirname(__FILE__)));
    define("HTML_DIR", LOCAL_BASE_PATH);

    define("DOMAIN", "yamagata01.php.xdomain.jp");
//    define("DOMAIN_SSL", "yamagata01.php.xdomain.jp/MyOrderSheet");
    define("DOMAIN_SSL", "localhost:10011/MyOrderSheet");

    //define("SSL_URL", "https://" . DOMAIN_SSL);
    define("SSL_URL", "http://" . DOMAIN);
    
    define("ADMIN_URL", SSL_URL . "/MyOrderSheet/admin/");
    define("ADMIN_IMG_DIR", ADMIN_URL . "img/");
    define("ADMIN_CSS_DIR", ADMIN_URL . "css/");
    define("ADMIN_JS_DIR", ADMIN_URL . "js/");
    define("URL_DIR", "/MyOrderSheet/");
    define("TEMP_IMG_DIR", URL_DIR . "upload/save_image/");
    define("UPLOAD_IMG_DIR", URL_DIR . "upload/save_image/");
//    define("CSV_DIR", HTML_DIR . URL_DIR . "admin/csv/");
//    define("ZIP_URL", "http://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip");
//    define("ZIP_CHECK_URL", "http://www.post.japanpost.jp/zipcode/index.html");

    define("FRONT_URL", "/MyOrderSheet/");
    
    require_once COMMON_LIB_PATH . "Common/Basis/Basis.php";
    require_once COMMON_LIB_PATH . "Common/Common.php";
    require_once COMMON_LIB_PATH . "Common/Customer/Customer.php";
    require_once COMMON_LIB_PATH . "Common/Data/Format.php";
    require_once COMMON_LIB_PATH . "Common/Master/Master.php";
//    require_once COMMON_LIB_PATH . "Common/Order/Order.php";
    require_once COMMON_LIB_PATH . "Common/Paginator/Paginator.php";
    require_once COMMON_LIB_PATH . "Common/PreperDate/PreperDate.php";
    require_once COMMON_LIB_PATH . "Common/System/System.php";
    require_once COMMON_LIB_PATH . "CommonTools.php";
    require_once COMMON_LIB_PATH . "Front/Check/BasicCheck.php";
    require_once COMMON_LIB_PATH . "Front/CSV/Csv.php";
    require_once COMMON_LIB_PATH . "Front/HTTP/Http.php";
    require_once COMMON_LIB_PATH . "Message/Message.php";
    require_once COMMON_LIB_PATH . "Validate.php";

    // Models
    require_once(APPLICATION_PATH . "/admin/install/Models.php");

    //HTTPHOST
    define('HTTPHOST', $_SERVER['HTTP_HOST']);
    
   /**
     * $objUserAgent呼び出し
     */
    /* -- UserAgent --*/
    require_once APPLICATION_PATH . "/library/Front/UserAgent/UserAgent.php";
    
    if(isset($_SERVER["HTTP_USER_AGENT"])) {
        $objUserAgent = new UserAgent();
        if($objUserAgent->isAdmin()) {
            define('SYSTEM_ROOT_PATH', APPLICATION_PATH . '/admin/');
        } else {
            define('SYSTEM_ROOT_PATH', APPLICATION_PATH . "/front/");
        }
    } else {
        define('SYSTEM_ROOT_PATH', APPLICATION_PATH . '/admin/');
        define('APP_PATH', APPLICATION_PATH . '/admin/');
    }
    
    // 現在のログレベル
    // 0:全てのログを残す
    // 1:一部のログを残す
    // 3:ログを残さない
    define("CURRENT_LOG_LEVEL_ALL", 0);
    define("CURRENT_LOG_LEVEL_LIMITED", 1);
    define("CURRENT_LOG_LEVEL_NONE", 3);

    // 指定ログレベル
    // 1:一部のログを残す
    // 2:全てのログを残す
    define("SET_LOG_LEVEL_LIMITED", 1);
    define("SET_LOG_LEVEL_ALL", 2);
    
    // 現在のログレベル
    define("CURRENT_LOG_LEVEL", CURRENT_LOG_LEVEL_LIMITED);

    // Smarty用日付フォーマット識別子
    define("SMARTY_MONTH_FORMAT", "%#m");
//    define("SMARTY_MONTH_FORMAT", "%-m");

    // カレンダー表示期間
    define("CALENDAR_START_YEAR", 2006);
    define("CALENDAR_END_YEAR", 2026);
    
    // 認証用 magic
    define ("AUTH_MAGIC", "1111");
    
    // メンテナンスモード
    define("MAINTENANCE_MODE", 0);
    
    // メンテナンスモード時、管理者用クライアントIPアドレス
    define("ADMIN_CLIENT_IPADDR1", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR2", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR3", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR4", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR5", "202.239.250.23");
    
    // 検索結果表示件数
    define("SEARCH_RESULT_NUMBER", "10,20,30,40,50,60,70,80,90,100");
    
    // コピーライト
    define("COPY_RIGHT", "Copyright©2017 Honma.");
    
    // メール配信機能の画面表示
    define("MAIL_DISP", 1); // 0=しない,1=する