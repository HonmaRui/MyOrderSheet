<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Controller/Plugin/ErrorHandler.php';

//エラーコントローラー定義
class ErrorController extends Zend_Controller_Action {

//エラーアクション定義
    public function errorAction() {
        //蓄積された例外の種類をキーに処理を分岐
        $errors = $this->_getParam('error_handler');
        $stRequestURI = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        
        switch($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $stErrorMessage1 = "ページが見つかりません。";
                $stErrorMessage2 = "このページは削除された可能性があります";
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $stErrorMessage1 = "アプリケーションエラーが発生しました。";
                $stErrorMessage2 = "恐れ入りますがもう一度ページを読み込み直してください<br>(" . date("Y年m月d日 H時i分s秒") . ")";

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
                $fp = fopen(LOG_PATH . "/system/log/error" . date("YmdGis"), 'w');
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
        $layout->nav_tpl = "nav.tpl";
        $layout->footer_tpl = "footer.tpl";
        
        $this->objCommon = new Common();
        $this->objAdminSess = new Zend_Session_Namespace('Admin');
        $this->view->assign("arrMenu", $this->objAdminSess->arrMenu);
        $this->view->assign("arrGlobalNavPos", $this->objCommon->getGlobalNavCurrentPos($this->objAdminSess->arrMenu));
        
        $this->view->assign("layout", $layout);
        $this->view->assign("errorMessage1", $stErrorMessage1);
        $this->view->assign("errorMessage2", $stErrorMessage2);
        $this->view->assign("requestURI", $stRequestURI);
        
    }
}
