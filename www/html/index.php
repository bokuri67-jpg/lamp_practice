<?php


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

//セッション開始
session_start();

// ログインしていない場合はログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//DB情報を取得
$db = get_db_connect();
//ユーザー情報を取得
$user = get_login_user($db);

//商品情報を取得
$items = get_open_items($db);

//ランキング用の情報取得
$rankings = get_ranking($db);

//index_viewファイルの読み込み
include_once VIEW_PATH . 'index_view.php';