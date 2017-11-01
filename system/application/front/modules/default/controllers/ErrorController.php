<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Controller/Plugin/ErrorHandler.php';

//エラーコントローラー定義
class ErrorController extends Zend_Controller_Action {

//エラーアクション定義
    public function errorAction() {
        $objFormReq = $this->getRequest();

        //蓄積された例外の種類をキーに処理を分岐
        $errors = $this->_getParam('error_handler');
        $stRequestURI = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        
        switch($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $this->getResponse()->setHttpResponseCode(404);
                $stErrorMessage1 = "ページが見つかりません。";
                $stErrorMessage2 = "このページは削除された可能性があります";
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $stErrorMessage1 = "アプリケーションエラーが発生しました。";
                $stErrorMessage2 = "恐れ入りますがもう一度ページを読み込み直してください<br>(" . date("Y年m月d日 H時i分s秒") . ")";

                $stSessionExpireMsg = $errors->exception->getMessage();
                if ($stSessionExpireMsg == "sendordermail failed") {
                    $stErrorMessage1 = "ご注文を承りました。ご購入ありがとうございます。";
                    $stErrorMessage2 = "但し、システムエラーが発生したため、ご注文確認メールを正常に送信できませんでした。続けて同じご注文を入力されますと「二重注文」となる可能性がございます。誠に恐れ入りますが、お問い合わせフォームからお問い合わせください。";
                } elseif (preg_match('/^pointcalc failed/',$stSessionExpireMsg)) {
                    $stErrorMessage1 = "ご注文を承りました。ご購入ありがとうございます。";
                    $stErrorMessage2 = "但し、システムエラーが発生したため、ポイント計算を正常に処理できませんでした。続けて同じご注文を入力されますと「二重注文」となる可能性がございます。誠に恐れ入りますが、お問い合わせフォームからお問い合わせください。";
                } elseif ($stSessionExpireMsg == "db sync failed") {
                    $stErrorMessage1 = "ご注文を承りました。ご購入ありがとうございます。";
                    $stErrorMessage2 = "但し、システムエラーが発生したため、注文情報を正常に処理できませんでした。続けて同じご注文を入力されますと「二重注文」となる可能性がございます。誠に恐れ入りますが、お問い合わせフォームからお問い合わせください。";
                } elseif ($stSessionExpireMsg == "Session Expired") {
                    $stErrorMessage1 = "同一画面で１時間経過によるエラーです。";
                    $stErrorMessage2 = "同じ画面で１時間以上経過したためエラーとなりました。<br>大変お手数ではございますが、再度メニュー画面より操作をお願いいたします。";
                } elseif ($stSessionExpireMsg == "Session Invalid") {
                    $stErrorMessage1 = "セッションが取得できませんでした。";
                    $stErrorMessage2 = "大変お手数ではございますが、再度メニュー画面より操作をお願いいたします。";
                }
                
                // 500エラーの場合はログをとる
                $trace = $errors->exception->getTrace();
                $errorMsg = sprintf("%s\n%s: %s in %s line %s.\n%s\nParams: %s",
                    $stRequestURI,
                    $errors->type,
                    $errors->exception->getMessage(),
                    $trace[0]['file'],
                    $trace[0]['line'],
                    $errors->exception->getTraceAsString(),
                    serialize($errors->request->getParams()));
                // ログ出力
                $fp = fopen(SYSTEM_PATH . "/log/error" . date("YmdHis"), 'w');
                fwrite($fp, $errorMsg);
                fclose($fp);
                
                // リクエストURIは画面には出さない
                $stRequestURI = "";
                break;
        }
        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;

        // 共通テンプレ生成の為のクラスを生成
        $layout = new Zend_Layout();
        
        // 共通レイアウトの読み込み
        $layout->header_tpl = "header.tpl";
        $layout->sidemenu_tpl = "";
        $layout->footer_tpl = "footer.tpl";
        $this->view->assign("layout", $layout);
        $this->view->assign("errorMessage1", $stErrorMessage1);
        $this->view->assign("errorMessage2", $stErrorMessage2);
        $this->view->assign("requestURI", $stRequestURI);
        
        // ログイン情報取得(名前)
        if ($this->objFrontSess->Login) {
            $this->view->assign("bIsLogin", true);
            $this->view->assign("stCustomerName", $this->objFrontSess->Name);
        }
        
        // セッション情報の保存
        $this->objCommon = new Common();
        $this->mdlCategory = new Application_Model_Category();
            
        // カテゴリ
        $this->arrCategory = CommonTools::changeDbArrayForFormTag($this->mdlCategory->fetchAll(array(
            "d_category_CategoryID", "d_category_CategoryName")));
        $this->view->assign("arrCategory", $this->arrCategory);
        
    }
}
