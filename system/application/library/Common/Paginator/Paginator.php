<?php

// Zend_Paginator
require_once 'Zend/Paginator.php';
require_once 'Zend/Paginator/Adapter/Array.php';

class Paginator {
    
    public function Init(){
    }
    
    public function getPaginator($arrData, $iCountPerPage, $iPageNumber = 1) {
        
        try {
            
            $objPaginateData = Zend_Paginator::factory($arrData);
            $objPaginateData->setCurrentPageNumber($iPageNumber);
            $objPaginateData->setItemCountPerPage($iCountPerPage);

            // TODO 画面ごとの設定を取得できるロジック
            $objPaginateData->setPageRange(10);
            Zend_Paginator::setDefaultScrollingStyle("Sliding");
            Zend_View_Helper_PaginationControl::setDefaultViewPartial("pager.tpl");

            return $objPaginateData;
            
        } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
        }
    }
    
    public function getPaginateData($objPaginator) {
        
        try {
            
            // 現在のページ情報
            $objPages = $objPaginator->getCurrentItems();
            return $objPages;
            
         } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
         }
    }
    
    
    public function getPaginateInfo($objPaginateData) {
        
        try {
            
            $objPages = $objPaginateData->getPages();
            return $objPages;
            
         } catch(Zend_Exception $e) {
            $this->objMessage->getExceptionMessage(get_class($this), __FUNCTION__, $e);
         }
    }
}
