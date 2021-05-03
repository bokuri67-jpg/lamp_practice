<?php
//購入履歴ページ

//定数ファイルを読み込み
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
// DB情報を取得
$db = get_db_connect();
//ログインチェック
$user = get_login_user($db);

//購入履歴情報を取得
if($user['user_id'] === 4) {
    $historys = get_all_order_history($db);
} else {
    $historys = get_order_history($db, $user['user_id']);
}
include_once VIEW_PATH . '/history_view.php';
