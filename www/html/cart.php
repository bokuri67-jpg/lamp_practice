<?php

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
// MySQL用のDSN文字列
$db = get_db_connect();
//ログイン済みユーザーかどうか
$user = get_login_user($db);
//カート内の商品情報を出力
$carts = get_user_carts($db, $user['user_id']);
//カート内の合計金額を返す。
$total_price = sum_carts($carts);

//cart_viewファイルを読み込む
include_once VIEW_PATH . 'cart_view.php';