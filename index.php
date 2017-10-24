<?php

// アプリケーション・ディレクトリへのパスを定義します
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__)) . '/system/application');
define('SYSTEM_PATH', realpath(dirname(__FILE__)) . '/system');
// アプリケーション環境を定義します
// production
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// libraryディレクトリーをinclude_pathに追加
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH) . '/../library',
    get_include_path(),
)));

require_once "require.php";
/** Zend_Application */
require_once 'Zend/Application.php';
$front = Zend_Controller_Front::getInstance();

$front->setControllerDirectory(array(
    'default' => APPLICATION_PATH . '/front/modules/default/controllers',
));
$front->addModuleDirectory(APPLICATION_PATH . '/front/modules/');
$router = $front->getRouter();

// ルートオブジェクトの生成
//デフォルトアクション
$route = new Zend_Controller_Router_Route(
    "/",
    array(
        "module"     => "index",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("root_index", $route);

// 汎用静的ページ用
$route = new Zend_Controller_Router_Route(
    "/contents/:depth1/:depth2/:depth3/:depth4/:depth5",
    array(
        "module"     => "contents",
        "controller" => "static",
        "action"     => "index",
        "depth1"     => null,
        "depth2"     => null,
        "depth3"     => null,
        "depth4"     => null,
        "depth5"     => null,
        )
);
$router->addRoute("root_static", $route);

// ログイン
$route = new Zend_Controller_Router_Route(
    "/login",
    array(
        "module"     => "login",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("root_index_login", $route);

// ログアウト
$route = new Zend_Controller_Router_Route(
    "/logout",
    array(
        "module"     => "index",
        "controller" => "index",
        "action"     => "logout",
        )
);
$router->addRoute("root_index_logout", $route);

// エラーページ(シングル)
$route = new Zend_Controller_Router_Route(
    "/error/",
    array(
        "module"     => "error",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("error_index", $route);

// 会員登録 > 利用規約
$route = new Zend_Controller_Router_Route(
    "/entry/",
    array(
        "module"     => "entry",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("entry_index", $route);

// 会員登録 > 情報入力
$route = new Zend_Controller_Router_Route(
    "/entry/regist",
    array(
        "module"     => "entry",
        "controller" => "index",
        "action"     => "regist",
        )
);
$router->addRoute("entry_regist", $route);

// 会員登録 > 入力内容確認
$route = new Zend_Controller_Router_Route(
    "/entry/confirm",
    array(
        "module"     => "entry",
        "controller" => "index",
        "action"     => "confirm",
        )
);
$router->addRoute("entry_confirm", $route);

// 会員登録 > 完了
$route = new Zend_Controller_Router_Route(
    "/entry/complete",
    array(
        "module"     => "entry",
        "controller" => "index",
        "action"     => "complete",
        )
);
$router->addRoute("entry_complete", $route);

// マイページ
$route = new Zend_Controller_Router_Route(
    "/mypage/",
    array(
        "module"     => "mypage",
        "controller" => "index",
        "action"     => "index",
        )
);
$router->addRoute("mypage_index", $route);

// マイページ > 会員情報変更
$route = new Zend_Controller_Router_Route(
    "/mypage/change/",
    array(
        "module"     => "mypage",
        "controller" => "change",
        "action"     => "index",
        )
);
$router->addRoute("mypage_change", $route);

// マイページ > 退会手続きトップ
$route = new Zend_Controller_Router_Route(
    "/mypage/refusal",
    array(
        "module"     => "mypage",
        "controller" => "refusal",
        "action"     => "index",
        )
);
$router->addRoute("mypage_refusal_index", $route);

// マイページ > 退会手続き最終決定
$route = new Zend_Controller_Router_Route(
    "/mypage/refusal/confirm",
    array(
        "module"     => "mypage",
        "controller" => "refusal",
        "action"     => "confirm",
        )
);
$router->addRoute("mypage_refusal_confirm", $route);

// マイページ > 退会手続き完了
$route = new Zend_Controller_Router_Route(
    "/mypage/refusal/complete",
    array(
        "module"     => "mypage",
        "controller" => "refusal",
        "action"     => "complete",
        )
);
$router->addRoute("mypage_refusal_complete", $route);

// アプリケーション及びブートストラップを作成して、実行します
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/front/configs/application.ini'
);
$application->bootstrap()->run();
