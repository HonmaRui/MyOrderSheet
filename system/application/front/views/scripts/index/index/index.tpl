<div class="container">
  <div class="page-header" style="margin-top: 3% !important; margin-bottom: 0px !important;">
    <div class="row">
      <div class="col-lg-12">
        <div class="bs-component">
          <div class="jumbotron white">
            <h1 class="font1">最高のオーダーをあなたに</h1>
            <p class="font2 fs1em">スターバックス・コーヒー、サブウェイ、二郎系ラーメン...好みのトッピング・オーダーを選択する機会は増えましたが、いつも「ふつう」や「お店のおすすめ」を選んでしまってはいませんか？<br>そんなあなたに マイオーダーシート は最高のオーダーをご紹介致します！</p>
            <p><a class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></span> 人気オーダーシートを見る</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6">
      <h2><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> オーダーシート作成</h2>
        <div class="well bs-component">
          <form class="form-horizontal">
            <fieldset>
              <div class="form-group">
                <label for="select" class="col-lg-3 control-label">カテゴリー</label>
                <div class="col-lg-9">
                  <select class="form-control" id="select">
                    <option>スターバックス・コーヒー</option>
                    <option>サブウェイ</option>
                    <option>二郎系ラーメン</option>
                    <option>家系ラーメン</option>
                    <option>その他</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="textArea" class="col-lg-3 control-label">タイトル</label>
                <div class="col-lg-9">
                  <input name="title" class="form-control" placeholder="タイトルを入力してください">
                </div>
              </div>
              <div class="form-group">
                <label for="textArea" class="col-lg-3 control-label">オーダー内容</label>
                <div class="col-lg-9">
                  <textarea class="form-control" rows="5" id="textArea" placeholder="オーダー内容を入力してください"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label for="InputFile" class="col-lg-3 control-label"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> 画像</label>
                <div class="col-lg-9">
                  <input type="file" id="InputFile">
                </div>
              </div>
              <div class="form-group">
                <div class="" style="text-align: center;">
                  <button type="submit" class="btn btn-primary btn-lg" style="width: 280px;">作成</button>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
    </div>
    <div class="col-lg-6">
      <h2 id="nav-tabs"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> みんなのオーダーシート</h2>
      <div class="bs-component">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#popular" data-toggle="tab" aria-expanded="true">話題のオーダー</a></li>
          <li class=""><a href="#new" data-toggle="tab" aria-expanded="false">新着オーダー</a></li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              カテゴリ別オーダーの一覧 <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#category1" data-toggle="tab">スターバックス・コーヒー</a></li>
              <li><a href="#category2" data-toggle="tab">サブウェイ</a></li>
              <li><a href="#category3" data-toggle="tab">二郎系ラーメン</a></li>
              <li><a href="#category4" data-toggle="tab">家系ラーメン</a></li>
              <li><a href="#category5" data-toggle="tab">その他</a></li>
            </ul>
          </li>
        </ul>
        <div id="myTabContent" class="tab-content">
          <div class="tab-pane fade active in" id="popular">
            <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">みんな大好き！チョコ×抹茶フラペチーノ</h3>
              </div>
              <div class="panel-body">
                <div class="col-lg-3" style="padding: 0 !important;">
                  <img src="{$smarty.const.IMG_URL}sample1.jpg" width="125">
                </div>
                <div class="col-lg-9" style="margin-bottom: 20px !important;">
                  １．抹茶クリームフラペチーノを注文<br>
                  ２．チョコレートチップを追加［+50円］<br>
                  ３．チョコレートソースを追加［無料］<br>
                  （お好みで、抹茶パウダーを多めにすると抹茶味がアップ！）<br><br>
                  夏にしか飲むことができないと思っていたあなたに朗報です！フラペチーノの定番カスタマイズ。抹茶とチョコという王道の組み合わせは多くの人の心を掴んで離しません。
                </div>
                <div class="well well-sm" style="margin-bottom: 0 !important; clear: both; border: none !important; border-top: 1px solid #cbe7c7 !important; background-color: white !important; padding-top: 20px !important;">
                  投稿日時：2017年 10月30日　閲覧数：1000　　　　　いいね！(20)
                </div>
              </div>
            </div>            <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">みんな大好き！チョコ×抹茶フラペチーノ</h3>
              </div>
              <div class="panel-body">
                <div class="col-lg-3" style="padding: 0 !important;">
                  <img src="{$smarty.const.IMG_URL}sample1.jpg" width="125">
                </div>
                <div class="col-lg-9" style="margin-bottom: 20px !important;">
                  １．抹茶クリームフラペチーノを注文<br>
                  ２．チョコレートチップを追加［+50円］<br>
                  ３．チョコレートソースを追加［無料］<br>
                  （お好みで、抹茶パウダーを多めにすると抹茶味がアップ！）<br><br>
                  夏にしか飲むことができないと思っていたあなたに朗報です！フラペチーノの定番カスタマイズ。抹茶とチョコという王道の組み合わせは多くの人の心を掴んで離しません。
                </div>
                <div class="well well-sm" style="margin-bottom: 0 !important; clear: both; border: none !important; border-top: 1px solid #cbe7c7 !important; background-color: white !important; padding-top: 20px !important;">
                  投稿日時：2017年 10月30日　閲覧数：1000　　　　　いいね！(20)
                </div>
              </div>
            </div>            <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">みんな大好き！チョコ×抹茶フラペチーノ</h3>
              </div>
              <div class="panel-body">
                <div class="col-lg-3" style="padding: 0 !important;">
                  <img src="{$smarty.const.IMG_URL}sample1.jpg" width="125">
                </div>
                <div class="col-lg-9" style="margin-bottom: 20px !important;">
                  １．抹茶クリームフラペチーノを注文<br>
                  ２．チョコレートチップを追加［+50円］<br>
                  ３．チョコレートソースを追加［無料］<br>
                  （お好みで、抹茶パウダーを多めにすると抹茶味がアップ！）<br><br>
                  夏にしか飲むことができないと思っていたあなたに朗報です！フラペチーノの定番カスタマイズ。抹茶とチョコという王道の組み合わせは多くの人の心を掴んで離しません。
                </div>
                <div class="well well-sm" style="margin-bottom: 0 !important; clear: both; border: none !important; border-top: 1px solid #cbe7c7 !important; background-color: white !important; padding-top: 20px !important;">
                  投稿日時：2017年 10月30日　閲覧数：1000　　　　　いいね！(20)
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="new">
            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
          </div>
          <div class="tab-pane fade" id="category1">
            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.</p>
          </div>
          <div class="tab-pane fade" id="category2">
            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>