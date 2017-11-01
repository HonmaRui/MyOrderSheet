<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{$smarty.const.SITE_TITLE}</title>

        <meta property="og:title" content="{$smarty.const.SITE_TITLE}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{$smarty.const.URL}">
        <meta property="og:image" content="{$smarty.const.SITE_IMAGE}">
        <meta property="og:description" content="{$smarty.const.SITE_DESCRIPTION}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@MITLicense">
        <meta name="twitter:creator" content="@MITLicense">
        <meta name="twitter:title" content="{$smarty.const.SITE_TITLE}">
        <meta name="twitter:image" content="{$smarty.const.SITE_IMAGE}">
        <meta name="twitter:description" content="{$smarty.const.SITE_DESCRIPTION}">
        
        <link rel="shortcut icon" href="{$smarty.const.IMG_URL}favicon.png">
        <link rel="stylesheet" type="text/css" href="{$smarty.const.CSS_URL}bootstrap.css">
        <link rel="stylesheet" type="text/css" href="{$smarty.const.CSS_URL}overwrite.css">
        <script src="{$smarty.const.JS_URL}jquery-2.1.1.min.js"></script>
        <script src="{$smarty.const.JS_URL}bootstrap.min.js"></script>
        <script src="{$smarty.const.JS_URL}common.js"></script>
    </head>
    <body>
        <header>
            <div class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{$smarty.const.URL}" class="navbar-brand sp_pr0" id="sp">
                            <img src="{$smarty.const.IMG_URL}logo.png" style="top: -8px;position: relative;" width="234" height="40"></a>
                        <a href="{$smarty.const.URL}" class="navbar-brand sp_pr0" id="pc">
                            <img src="{$smarty.const.IMG_URL}logo.png" style="top: -8px;position: relative;" width="205" height="35"></a>
                        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="navbar-collapse collapse" id="navbar-main">
                        <ul class="nav navbar-nav">
                            {if $bIsLogin}
                            <li> <a href="{$smarty.const.URL}/mypage"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {$stCustomerName}</a></li>
                            <li><a href="javascript: void(0)" data-toggle="modal" data-target="#new"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 新規作成</a></li>
                            <li><a href="{$smarty.const.URL}/logout"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> ログアウト</a></li>
                            {else}
                            <li><a href="{$smarty.const.URL}/entry"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> 新規登録</a></li>
                            <li><a href="{$smarty.const.URL}/login"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> ログイン</a></li>
                            <li><a href="javascript: void(0)" data-toggle="modal" data-target="#new"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> オーダーシート作成</a></li>
                            {/if}
                        </ul>
                        <form class="navbar-form navbar-left" role="search" action="{$smarty.const.URL}/ordersheet" method="get" enctype="multipart/form-data" name="search_form">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="オーダーシート名で検索" id="search-form" name="keyword" value="{$arrForm["keyword"]}">
                                <button type="submit" class="btn btn-default" id="search-btn"><img src="{$smarty.const.IMG_URL}search.png" width="21" height="21"></button>
                            </div>
                        </form>
                        <div class="nav navbar-form navbar-right">
                            <div class="form-group">
                                <a target="_blank" href="https://twitter.com/intent/tweet?text={$smarty.const.SITE_TITLE}&amp;url={$smarty.const.URL}"><img src="{$smarty.const.IMG_URL}Twitter.png" alt="twitterアイコン" width="32" height="32"></a>
                                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={$smarty.const.URL}"><img src="{$smarty.const.IMG_URL}Facebook.png" alt="facebookアイコン" width="32" height="32"></a>
                                <a target="_blank" href="https://plus.google.com/share?url={$smarty.const.URL}"><img src="{$smarty.const.IMG_URL}Google+.png" alt="g+アイコン" width="32" height="32"></a>
                                <a target="_blank" href="http://line.me/R/msg/text/?{$smarty.const.URL}" id="pc"><img src="{$smarty.const.IMG_URL}Line.png" alt="LINEアイコン" width="32" height="32"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
                            
<!-- モーダル・ダイアログ -->
<div class="modal fade" id="new" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form class="form-horizontal" action="{$smarty.const.URL}/" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="csrf" value="{$stCsrf}">
                  <input type="hidden" name="mode" value="add">
                  <fieldset>
                      {if !$bIsLogin}
                      <p><a href="{$smarty.const.URL}/login">会員登録が済んでいる場合はログインしてください</a></p>
                      <p><a href="{$smarty.const.URL}/entry">新規会員登録はこちら</a></p>
                      {/if}
                    <div class="form-group">
                      <label for="select" class="col-lg-3 control-label">カテゴリー</label>
                      <div class="col-lg-9">
                        {assign var="key" value="d_order_sheet_CategoryID"}
                        <select class="form-control" id="select" name="{$key}">
                          {html_options options=$arrCategory selected=$arrForm[$key]}
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="textArea" class="col-lg-3 control-label" id="no-pc">タイトル<br><span class="text-danger"><small>(最大50文字)</small></span></label>
                        <label for="textArea" class="col-lg-3 control-label" id="yes-pc">タイトル&nbsp;&nbsp;<span class="text-danger"><small>(最大50文字)</small></span></label>
                      <div class="col-lg-9">
                        {assign var="key" value="d_order_sheet_Title"}
                        <input name="{$key}" value="{$arrForm[$key]}" class="form-control" placeholder="タイトルを入力してください" maxlength="50">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="textArea" class="col-lg-3 control-label" id="no-pc">オーダー内容<br><span class="text-danger"><small>(最大200文字)</small></span></label>
                      <label for="textArea" class="col-lg-3 control-label" id="yes-pc">オーダー内容&nbsp;&nbsp;<span class="text-danger"><small>(最大200文字)</small></span></label>
                      <div class="col-lg-9">
                        {assign var="key" value="d_order_sheet_Contents"}
                        <textarea name="{$key}" id="{$key}" class="form-control" rows="5" id="textArea" placeholder="オーダー内容を入力してください" maxlength="200">{$arrForm[$key]}</textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="InputFile" class="col-lg-3 control-label" id="no-pc"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> 画像<br><span class="text-danger"><small>(最大2MB jpg,png)</small></span></label>
                      <label for="InputFile" class="col-lg-3 control-label" id="yes-pc"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> 画像&nbsp;&nbsp;<span class="text-danger"><small>(最大2MB jpg,png)</small></span></label>
                      <div class="col-lg-9">
                        {assign var="key" value="d_order_sheet_ImageFileName1"}
                        <input type="file" name="{$key}" id="InputFile" accept=".jpg,.png,image/jpeg,image/png">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="" style="text-align: center;">
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 280px;" id="add-button">オーダーシートを登録</button>
                      </div>
                    </div>
                  </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>