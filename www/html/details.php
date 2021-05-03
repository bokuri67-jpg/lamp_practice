<?php
//購入詳細ページ

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
//order_idを取得
$order_id = get_post('order_id');
//購入詳細情報を取得
$details = get_order_details($db, $order_id);
//該当の購入履歴情報を取得
$order_historys = get_one_order_history($db, $order_id);

include_once VIEW_PATH . '/details_view.php';
