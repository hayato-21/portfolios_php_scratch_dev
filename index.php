<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------------
// カレントページのGETパラメータを取得
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
// カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
// パラメータに不正な値が入っているかチェック 数値以外の値が入っているとき。intいるよ！
if(!is_int((int)$currentPageNum)){
    error_log('エラー発生：指定ページに不正な値が入りました');
    header("Location:index.php"); //トップページへ
}
// 表示件数
$listSpan = 5;
// 現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);
// DBから記録を取得
$dbProductData = getProductList($currentMinNum, $category, $sort);
// DBからカテゴリデータを取得
$dbCategoryData = getCategory();
//debug('現在のページ:'.$currentPageNum,true);
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));

debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'HOME';
require('head.php');
?>

<body class="page-home page-2colum">
   
  <!-- メニュー -->
  <?php
    require('header.php');
  ?>
   
   <!-- メインコンテンツ -->
   <diV id="contents" class="site-width">
       
     <!-- サイドバー -->
     <section id="sidebar">
       <form name="" method="get">
         <h1 class="title">カテゴリー</h1>
         <div class="selectbox">
           <span class="icn_select"></span>
           <select name="c_id" id="">
             <option value="0" <?php if(getFormData('c_id', true) == 0){echo 'selected'; } ?> >選択してください</option>
             
             <?php
               foreach($dbCategoryData as $key => $val){
             ?>
               <option value="<?php echo $val['id'] ?>"<?php if(getFormData('c_id', true) == $val['id'] ){echo 'selected'; } ?> >
                 <?php echo $val['name']; ?>
               </option>
            <?php
               }
            ?>
           </select>
         </div>
         <h1 class="title">表示順</h1>
         <div class="selectbox">
           <span class="icn_select"></span>
           <select name="sort">
             <option value="0" <?php if(getFormData('sort',true) == 0){echo 'selected'; } ?> >選択してください</option>
             <option value="1"<?php if(getFormData('sort',true) == 1){echo 'selected'; } ?> >時間が少ない順</option>
             <option value="2"<?php if(getFormData('sort',true) == 2){echo 'selected'; } ?> >時間が多い順</option>
           </select>
         </div>
         <input type="submit" value="検索">
       </form>
     </section>
     
     <!-- Main -->
     <section id="main">
       <div class="search-title">
         <div class="search-left">
           <span class="total-num"><?php echo sanitize($dbProductData['total']); ?></span>件の記録が見つかりました
         </div>
         <div class="search-right">
           <span class="num"><?php echo (!empty($dbProductData['data'])) ? $currentMinNum+1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum+count($dbProductData['data']); ?></span>件 / <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
         </div>
       </div>
       <!-- パネルリスト -->
       <div class="panel-list">
        <?php
           foreach($dbProductData['data'] as $key => $val):
             $userInfo = getUser($val['user_id']);
        ?>
         <a href="logDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">  <!-- $val['user_id] -> getUser('user_id) -->
           <div class="panel-head">
             <div class="panel-left">
               <!-- (getUser('user_id')))  -->
               <img src="<?php echo showImg(sanitize($userInfo['pic'])); ?>" alt="ユーザー">
               <p class="username" style="color:#333;"><?php if(!empty($userInfo['username'])) echo $userInfo['username']; ?></p>
             </div>
             <div class="panel-center">
               <span class="studytime"><?php echo sanitize($val['price']); ?>時間</span>
             </div>
             <div class="panel-right">
               <img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="コンテント">
               <p class="contentname" style="color:#333;"><?php echo sanitize($val['name']); ?></p>
             </div>
           </div>
           <div class="panel-body"> <!-- 新しく追加する -->
             <p style="color:#333;"><?php if(!empty($val['comment'])) echo sanitize($val['comment']); ?></p>  
           </div>
         </a>
        <?php
           endforeach;
        ?>
           
    
       </div>
    
       <!-- ページネーション -->
       <?php pagination($currentPageNum, $dbProductData['total_page']); ?>
       
       
     </section>
   </diV>
    
   <!-- footer -->
   <?php
     require('footer.php');
   ?>