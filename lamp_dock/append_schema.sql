<?php


//購入履歴画面
CREATE TABLE oder_history (
  order_id INT AUTO_INCREMENT,
  user_id INT,
  order_date DATETIME,
  primary key(order_id)
);


//購入明細画面
CREATE TABLE item_details (
  order_id INT,
  item_id INT,
  price INT,
  amount INT DEFAULT 0
);
?>