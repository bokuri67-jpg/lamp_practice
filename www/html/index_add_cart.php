<?php
//カート追加のcontrollファイル

//iframeによるサイト読み込み防止
header('X-FRAME-OPTIONS: DENY');

// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'item.php';
// cartデータに関する関数ファイルを読み込み。
require_once MODEL_PATH . 'cart.php';

session_start();

//ログインユーザーでない場合、ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
//DB接続
$db = get_db_connect();
//ログインチェック
$user = get_login_user($db);

//ポストで受け取った値
$item_id = get_post('item_id');

//カートに商品を追加したとき、メッセージを表示する
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
} else {
  set_error('カートの更新に失敗しました。');
}

//商品一覧ページへリダイレクトする
redirect_to(HOME_URL);