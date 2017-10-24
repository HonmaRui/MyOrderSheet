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
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <style type="text/css">
            @import url(https://fonts.googleapis.com/earlyaccess/roundedmplus1c.css);
            @import url(https://fonts.googleapis.com/earlyaccess/mplus1p.css);
            body { padding-top: 80px; }
            @media ( min-width: 768px ) {
                #banner {
                    min-height: 300px;
                    border-bottom: none;
                }
                .bs-docs-section {
                    margin-top: 8em;
                }
                .bs-component {
                    position: relative;
                }
                .bs-component .modal {
                    position: relative;
                    top: auto;
                    right: auto;
                    left: auto;
                    bottom: auto;
                    z-index: 1;
                    display: block;
                }
                .bs-component .modal-dialog {
                    width: 90%;
                }
                .bs-component .popover {
                    position: relative;
                    display: inline-block;
                    width: 220px;
                    margin: 20px;
                }
                .nav-tabs {
                    margin-bottom: 15px;
                }
                .progress {
                    margin-bottom: 10px;
                }
                #pc { display: none; }
            }
            @media ( max-width: 768px ) {
                #search-form { display: inline; width: 80%;}
                #search-btn { margin-bottom: 3px; }
                .sp_pr0 { padding-right: 0px; }
            }
            .jumbotron { background:url(img/main.png) center no-repeat; background-size: cover;　color: white;}
            .white { color: white; }
            .font0 { font-size: 0.7em; }
            .font1 { font-family: 'Rounded Mplus 1c'; }
            .font2 { font-family: 'Mplus 1p'; font-weight: 400 !important;}
            .fs1em { font-size: 1em !important; }
            footer {
              padding: 40px 0;
              color: #eee;
              background-color: #333;
            }
            footer .copyright {
              padding-top: 10px;
              padding-bottom: 10px;
            }
            .social-button {
                position: relative;
                padding: 10px 0;
                margin: 0 auto !important;
                overflow: hidden;
            }
            .social-button > ul {
                position: relative;
                left: 50%;
                float: left;
                padding: 0;
                margin: 0;
                list-style: outside none none;
            }
            .social-button > ul > li {
                position: relative;
                left: -50%;
                float: left;
                padding: 0;
                margin: 0 10px;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="/" class="navbar-brand sp_pr0">
                            <img src="{$smarty.const.IMG_URL}logo.png" style="top: -8px;position: relative;" width="234" height="40"></a>
                        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="navbar-collapse collapse" id="navbar-main">
                        <ul class="nav navbar-nav">
                            <li><a href="/">新規登録</a></li>
                            <li><a href="/">ログイン</a></li>
                            <li><a href="/">オーダーシート作成</a></li>
                        </ul>
                        <form class="navbar-form navbar-left" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="オーダーシート名で検索" id="search-form">
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