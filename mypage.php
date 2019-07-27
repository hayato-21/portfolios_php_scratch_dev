<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================
//ログイン認証
require('auth.php');

// 画面表示用データ取得
//================================
$u_id = $_SESSION['user_id'];
// DBから商品データを取得
$productData = getMyProducts($u_id);
// DBから連絡掲示板データを取得
$bordData = getMyMsgsAndBord($u_id);
// DBから自分の連絡先の相手

// DBからきちんとデータがすべて取れているかのチェックは行わず、取れなければ何も表示しないこととする

debug('取得した商品データ：'.print_r($productData,true));
debug('取得した掲示板データ：'.print_r($bordData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'マイページ';
require('head.php');
?>

<body class="page-home page-2colum">
   <style>
      #main{
        border: none !important;
      }
       .list.list-table{
        height: 300px;
        overflow-y: scroll;
      }
       
    </style>
  <!-- メニュー -->
  <?php
    require('header.php');
  ?>
    
  <p id="js-show-msg" style="display:none;" class="msg-slide">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>
  <!-- メインコンテンツ -->
  <div id="contents" class="site-width page-logined">
    <h1 class="page-title">MYPAGE</h1>
    <!-- Main -->
    <section id="main">
      <h2 class="title">
            記録一覧
           </h2>
           <div class="panel-list">
           <?php
             if(!empty($productData)):
                foreach($productData as $key => $val):
                $userInfo2 = getUser($val['user_id']); //新しく追加
           ?>
           
             
               <a href="registProduct.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">
                 <div class="panel-head">
                   <div class="panel-left">
                     <img src="<?php echo showImg(sanitize($userInfo2['pic'])); ?>" alt="ユーザー">
                     <p class="username" style="color:#333;"><?php echo $userInfo2['username']; ?></p>
                   </div>
                   <div class="panel-center">
                     <span class="studytime"><?php echo sanitize($val['price']); ?>時間</span>
                   </div>
                   <div class="panel-right">
                     <img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
                     <p class="contentname" style="color:#333;"><?php echo sanitize($val['name']); ?></p>
                   </div>
                 </div>
               <div class="panel-body">
                 <p style="color:#333;"><?php echo sanitize($val['comment']); ?></p>  
               </div>
             </a>
          <?php
            endforeach;
          endif;
          ?>
       </div>
        <style>
           .list{
               margin-top: 20px;
             margin-bottom: 30px;
           }
        </style>
       <section class="list list-table">
          <h2 class="title">
            連絡掲示板一覧
          </h2>
          <table class="table">
            <thead>
              <tr>
                <th>最新送信日時</th>
                <th>最後に送った人</th>
                <th>メッセージ</th>
              </tr>
            </thead>
            <tbody>
             <?php
                if(!empty($bordData)){
                    foreach($bordData as $key => $val){
                        if(!empty($val['msg'])){
                            $msg = array_shift($val['msg']); //これで、一番初めのメッセージを返す。
                            $userInfo = getUser($msg['from_user']); //新しく追加
             ?>
                    <tr>
                        <td><?php echo sanitize(date('Y.m.d H:i:s', strtotime($msg['send_date']))); ?></td>
                        <!-- ここを修正する -->
                        
                        <td><?php echo sanitize($userInfo['username']); ?></td>
                        <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>"><!-- 新しく追加 --><?php echo mb_substr(sanitize($msg['msg']),0,40); ?>...</a></td>
                    </tr>
             <?php
                        }else{
             ?>
                       <tr>
                         <td>--</td>
                         <td>◯◯ ◯◯</td>
                         <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>">まだメッセージはありません</a></td>
                       </tr>
                <?php
                        }
                    }
                }
               ?>
            </tbody>
          </table>
        </section>
        
        
    </section>

        
         
        
      
      <!-- サイドバー -->
      <?php
      require('sidebar_mypage.php');
      ?>
  </div>
<!-- footer -->
<?php
    require('footer.php');
?>