<?php
//ログアウト

//iframeによるサイト読み込み防止
header('X-FRAME-OPTIONS: DENY');

//定数ファイルを読み込み
require_once '../conf/const.php';
//汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

session_start();
$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
session_destroy();

//ログインページへリダイレクト
redirect_to(LOGIN_URL);

