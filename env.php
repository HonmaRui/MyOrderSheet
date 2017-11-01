<?php

    // Model Path
    define('MODEL_PATH', APPLICATION_PATH . "/models/");
    // Library Path
    define('COMMON_LIB_PATH', APPLICATION_PATH . "/library/");

    define("LOCAL_BASE_PATH", dirname(dirname(dirname(__FILE__))));
    define("HTML_DIR", LOCAL_BASE_PATH . "/html/");

    require_once COMMON_LIB_PATH . "Common/Basis/Basis.php";
    require_once COMMON_LIB_PATH . "Common/Common.php";
    require_once COMMON_LIB_PATH . "Common/Image.php";
    require_once COMMON_LIB_PATH . "Common/Customer/Customer.php";
    require_once COMMON_LIB_PATH . "Common/Data/Format.php";
//    require_once COMMON_LIB_PATH . "Common/Order/Order.php";
    require_once COMMON_LIB_PATH . "Common/Paginator/Paginator.php";
    require_once COMMON_LIB_PATH . "Common/PreperDate/PreperDate.php";
    require_once COMMON_LIB_PATH . "Common/System/System.php";
    require_once COMMON_LIB_PATH . "CommonTools.php";
    require_once COMMON_LIB_PATH . "Front/Check/BasicCheck.php";
    require_once COMMON_LIB_PATH . "Front/CSV/Csv.php";
    require_once COMMON_LIB_PATH . "Front/HTTP/Http.php";
    require_once COMMON_LIB_PATH . "Front/Mypage/Mypage.php";
    require_once COMMON_LIB_PATH . "Message/Message.php";
    require_once COMMON_LIB_PATH . "Validate.php";

    define("DOMAIN", "yamagata01.php.xdomain.jp/MyOrderSheet");
//    define("DOMAIN_SSL", "yamagata01.php.xdomain.jp/MyOrderSheet");
    define("DOMAIN_SSL", "localhost:10011/MyOrderSheet");
    define("DOMAIN_NO_SEARCH", "http://yamagata01.php.xdomain.jp");

    define("ADMIN_URL", "yamagata01.php.xdomain.jp/MyOrderSheet/admin");

//    define("SSL_URL", "https://" . DOMAIN_SSL);
    define("URL", "http://" . DOMAIN_SSL);
    define("IMG_URL", URL . "/img/");
//    define("SHEET_IMG_URL", URL . "/img/upload/");
    define("SHEET_IMG_URL", dirname(__FILE__) . "/img/upload/");
    define("CSS_URL", URL . "/css/");
    define("JS_URL", URL . "/js/");
    define("TEMP_IMG_DIR", dirname(__FILE__) . "/img/upload/");
    define("UPLOAD_IMG_DIR", dirname(__FILE__) . "/img/upload/");
//    define("TEMP_IMG_DIR", URL . "/img/upload/");
//    define("UPLOAD_IMG_DIR", URL . "/img/upload/");
    
    define("SITE_TITLE", "マイオーダーシート - スタバや二郎のおすすめオーダーをゲット");
    define("SITE_DESCRIPTION", "今人気のオーダーがわかる！スタバや二郎によく行く人は必見！");
    define("SITE_IMAGE", IMG_URL . "Image.png");
    
    // Models
    require_once(APPLICATION_PATH . "/front/install/Models.php");
    
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

    require_once COMMON_LIB_PATH . "Front/HTTP/Http.php";

    // Smarty用日付フォーマット識別子
//    define("SMARTY_MONTH_FORMAT", "%#m");
    define("SMARTY_MONTH_FORMAT", "%-m");

    // 認証用 magic
    define ("AUTH_MAGIC", "1111");

    // パスワードリマインダーURL有効時間
    define("PASSWORD_REMINDER_URL_EXPIRE_HOURS", 12);

    // セッション有効期間(秒)
    define("SESSION_EXPIRE_SECOND", 3600);
    
    // クッキー有効期間(秒)
    define("COOKIE_EXPIRE_SECOND", 2592000); // 30日間

    // メンテナンスモード
    define("MAINTENANCE_MODE", 0);
    
    // メンテナンスモード時、管理者用クライアントIPアドレス
    define("ADMIN_CLIENT_IPADDR1", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR2", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR3", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR4", "202.239.250.23");
    define("ADMIN_CLIENT_IPADDR5", "202.239.250.23");
    define("ADMIN_CLIENT_IP_COUNT", 5);