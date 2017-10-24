<?php

// アプリケーション・ディレクトリへのパスを定義します
defined("APPLICATION_PATH")
    || define("APPLICATION_PATH", realpath(dirname(__FILE__)) . "/../system/application");
// アプリケーション環境を定義します
defined("APPLICATION_ENV")
    || define("APPLICATION_ENV", (getenv("APPLICATION_ENV") ? getenv("APPLICATION_ENV") : "production"));

require_once "require.php";
require_once COMMON_LIB_PATH . "path.php";
/** Zend_Application */
require_once "Zend/Application.php";
$front = Zend_Controller_Front::getInstance();

$front->setControllerDirectory(array(
    "default" => APPLICATION_PATH . "/admin/modules/default/controllers",
));
$front->addModuleDirectory(APPLICATION_PATH . "/admin/modules/");
$router = $front->getRouter();

// ルートオブジェクトの生成

// 管理者ログイン
$route = new Zend_Controller_Router_Route(
	"/login",
    array(
        "module"    => "default",
        "controller" => "login",
        "action"     => "index",
        )
);
$router->addRoute("admin_default_login_index", $route);

// 管理者ログイン認証
$route = new Zend_Controller_Router_Route(
	"/judgement",
    array(
        "module"    => "default",
        "controller" => "login",
        "action"     => "judgement",
        )
);
$router->addRoute("admin_default_login_judgement", $route);

//管理者ホーム表示アクション
$route = new Zend_Controller_Router_Route(
    "/home",
    array(
        "module"     => "default",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("admin_default_home_index", $route);

//管理者ログアウトアクション
$route = new Zend_Controller_Router_Route(
    "/logout",
    array(
        "module"     => "default",
        "controller" => "index",
        "action"     => "logout",
        )
);
$router->addRoute("admin_default_logout", $route);

// システム設定 追加/編集
$route = new Zend_Controller_Router_Route(
	"/system/add-popup",
    array(
        "module"    => "system",
        "controller" => "index",
        "action"     => "add-popup",
        )
);
$router->addRoute("admin_system_add-popup", $route);

$route = new Zend_Controller_Router_Route(
	"/system/waiting-process-for-ajax",
    array(
        "module"    => "system",
        "controller" => "index",
        "action"     => "waiting-process-for-ajax",
        )
);
$router->addRoute("admin_system_waiting_process_for_ajax", $route);

// 基本設定
$route = new Zend_Controller_Router_Route(
	"/basis",
    array(
        "module"     => "basis",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("admin_basis", $route);

//基本情報管理・メール履歴
$route = new Zend_Controller_Router_Route(
    "/basis/mail_history",
    array(
        "module"     => "basis",
        "controller" => "mail-history",
        "action"     => "index",
        )
);
$router->addRoute("admin_basis_mail_history_index", $route);

//基本情報管理・メール履歴　確認ポップアップ
$route = new Zend_Controller_Router_Route(
    "/basis/mail_history/confirm-popup",
    array(
        "module"     => "basis",
        "controller" => "mail-history",
        "action"     => "confirm-popup",
        )
);
$router->addRoute("admin_basis_mail_history_confirm-popup", $route);

//基本情報管理・郵便番号DB登録
$route = new Zend_Controller_Router_Route(
    "/basis/zip",
    array(
        "module"     => "basis",
        "controller" => "zip",
        "action"     => "index",
        )
);
$router->addRoute("admin_basis_zip", $route);

//基本情報管理・郵便番号DB登録 ダウンロードアクション
$route = new Zend_Controller_Router_Route(
    "/basis/zip/download",
    array(
        "module"     => "basis",
        "controller" => "zip",
        "action"     => "download",
        )
);
$router->addRoute("admin_basis_zip_download", $route);

//基本情報管理・郵便番号DB登録 アップロードアクション
$route = new Zend_Controller_Router_Route(
    "/basis/zip/upload",
    array(
        "module"     => "basis",
        "controller" => "zip",
        "action"     => "upload",
        )
);
$router->addRoute("admin_basis_zip_upload", $route);

//基本情報管理・自社営業日設定
$route = new Zend_Controller_Router_Route(
    "/basis/business_calendar",
    array(
        "module"     => "basis",
        "controller" => "holiday",
        "action"     => "index",
        )
);
$router->addRoute("admin_basis_holiday_business", $route);

// 基本設定　担当者マスタ
$route = new Zend_Controller_Router_Route(
    "/basis/member",
    array(
        "module"     => "basis",
        "controller" => "member",
        "action"     => "index",
        )
);
$router->addRoute("admin_basis_member", $route);

// 基本設定 担当者追加/編集
$route = new Zend_Controller_Router_Route(
	"/basis/add-popup",
    array(
        "module"    => "basis",
        "controller" => "member",
        "action"     => "add-popup",
        )
);
$router->addRoute("admin_basis_add-popup", $route);

// 顧客管理
$route = new Zend_Controller_Router_Route(
	"/customer",
    array(
        "module"    => "customer",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("admin_customer_index", $route);

$route = new Zend_Controller_Router_Route(
	"/customer/delete",
    array(
        "module"    => "customer",
        "controller" => "index",
        "action"     => "delete",
        )
);
$router->addRoute("admin_customer_delete", $route);

$route = new Zend_Controller_Router_Route(
	"customer/edit/:customerID",
    array(
        "module"    => "customer",
        "controller" => "index",
        "action"     => "add",
        "isEdit" => true,
        "customerID"     => null,
        )
);
$router->addRoute("admin_customer_edit", $route);

// 顧客検索ポップアップ
$route = new Zend_Controller_Router_Route(
	"/customer/search-popup",
    array(
        "module"    => "customer",
        "controller" => "search-popup",
        "action"     => "index",
        )
);
$router->addRoute("admin_customer_search_popup", $route);

// 受注
// 受注入力
$route = new Zend_Controller_Router_Route(
	"/order/add",
    array(
        "module"    => "order",
        "controller" => "index",
        "action"     => "add",
        "isEdit" => false,
        )
);
$router->addRoute("admin_order_index_add", $route);

// 受注編集
$route = new Zend_Controller_Router_Route(
	"/order/edit/:d_order_OrderID",
    array(
        "module"    => "order",
        "controller" => "index",
        "action"     => "add",
        "isEdit" => true,
        "d_order_OrderID" => null,
        )
);
$router->addRoute("admin_order_index_edit", $route);

// 受注削除
$route = new Zend_Controller_Router_Route(
	"/order/delete/:orderID",
    array(
        "module"    => "order",
        "controller" => "index",
        "action"     => "delete",
        "orderID" => null,
        )
);
$router->addRoute("admin_order_index_delete", $route);

$route = new Zend_Controller_Router_Route(
	"contents/index",
    array(
        "module"    => "contents",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("admin_contents_index", $route);

//開発者用
$route = new Zend_Controller_Router_Route(
    "/dev",
    array(
        "module"     => "dev",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("admin_dev_index", $route);

$route = new Zend_Controller_Router_Route(
    "/dev/popup",
    array(
        "module"     => "dev",
        "controller" => "popup",
        "action"     => "index",
        )
);
$router->addRoute("admin_dev_popup", $route);

$route = new Zend_Controller_Router_Route(
    "/dev/migration",
    array(
        "module"     => "dev",
        "controller" => "migration",
        "action"     => "index",
        )
);
$router->addRoute("admin_dev_migration", $route);

// 商品管理
$route = new Zend_Controller_Router_Route(
    "/product",
    array(
        "module"     => "product",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("admin_product_index", $route);

$route = new Zend_Controller_Router_Route(
    "/product/add",
    array(
        "module"     => "product",
        "controller" => "index",
        "action"     => "add",
        )
);
$router->addRoute("admin_product_add", $route);

$route = new Zend_Controller_Router_Route(
    "/product/edit/:productID",
    array(
        "module"     => "product",
        "controller" => "index",
        "action"     => "add",
        "isEdit"     => true,
        "productID"  => null,
        )
);
$router->addRoute("admin_product_edit", $route);

$route = new Zend_Controller_Router_Route(
    "/product/delete",
    array(
        "module"     => "product",
        "controller" => "index",
        "action"     => "delete",
        )
);
$router->addRoute("admin_product_delete", $route);

$route = new Zend_Controller_Router_Route(
    "/product/search-popup",
    array(
        "module"     => "product",
        "controller" => "search-popup",
        "action"     => "index",
        )
);
$router->addRoute("admin_product_search_popup", $route);

// アプリケーション及びブートストラップを作成して、実行します
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . "/admin/configs/application.ini"
);
$application->bootstrap()->run();
