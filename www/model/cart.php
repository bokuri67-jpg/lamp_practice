<?php 
//カート操作に関する、関数定義

//汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
//DB操作ファイルを読み込み
require_once MODEL_PATH . 'db.php';

//カート内の商品を表示
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql,array($user_id));
}

//カートへ追加された商品を表示
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql, array($user_id, $item_id));
}

//カート内に同じ商品がなければカートへ追加、同じ商品があれば数量を更新
function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//商品をカートへ追加
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?,?,?)
  ";

  return execute_query($db, $sql, array($item_id, $user_id, $amount));
}

//カート内の数量を更新
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  return execute_query($db, $sql, array($amount, $cart_id));
}

//カートから商品を削除する
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, array($cart_id));
}

//商品購入
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  //在庫数より購入数が多い場合「購入失敗」にする
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  } 
  foreach($carts as $cart) {
    $user_id = $cart['user_id'];
  }
  //トランザクション処理
  try{
    $db->beginTransaction();
    //購入履歴テーブルへ追加
      insert_order_history($db, $user_id);
    //lastinsertidを使って$order_idを取得する
    $order_id = $db->lastinsertId('order_id');
    //商品詳細テーブルへ追加
    foreach($carts as $cart){
      insert_order_details($db, $order_id, $cart['item_id'], $cart['price'], $cart['amount']);
    }
    //購入可能であれば、カートテーブルから商品を削除する
    delete_user_carts($db, $carts[0]['user_id']);
    $db->commit();
  } catch (PDOException $e) {
    $db->rollback();
  }
}

//カートから商品を削除
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  execute_query($db, $sql, array($user_id));
}

//カート内の合計金額を返す
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

//カート内の商品が購入可能か検証
function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}


//下記は　order.php　を作成してそこへ記載すべき？

//購入履歴テーブル(order_history)へ追加
function insert_order_history($db, $user_id){
  $sql = "
    INSERT INTO order_history (
      user_id
    )
    VALUES(?)
  ";
  return execute_query($db, $sql, array($user_id));
}

//購入明細テーブル(order_details)へ追加
function insert_order_details($db, $order_id, $item_id, $price, $amount){
  // $price = 10000;
  $sql = "
    INSERT INTO order_details (
      order_id,
      item_id,
      price,
      amount
      )
    VALUES(?,?,?,?)
  ";
  return execute_query($db, $sql, array($order_id, $item_id, $price, $amount));
}

//購入詳細情報を取得
function get_order_details($db, $order_id){
  $sql = "
    SELECT 
      items.name,
      order_details.price,
      order_details.amount,
      order_details.order_id,
      order_details.price*order_details.amount as subtotal
    FROM
      order_details
    JOIN 
      items
    ON 
      order_details.item_id = items.item_id
    WHERE
      order_id = ?
  ";

  return fetch_all_query($db, $sql, array($order_id));
}

//購入履歴情報を取得
function get_order_history($db, $user_id){
  $sql = "
    SELECT 
      order_history. order_id, 
      order_history. order_date,
      SUM(order_details. price * order_details. amount) as total
    FROM 
      order_history
    JOIN 
      order_details
    ON 
      order_history. order_id = order_details. order_id
    WHERE
      user_id = ?
    GROUP BY 
      order_id
  ";

  return fetch_all_query($db, $sql, array($user_id));
}