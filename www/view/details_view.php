<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <meta charset="utf-8">
        <title>購入履歴明細</title>
        <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'details.css'); ?>">
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        <h1>購入履歴明細</h1>

        <div class="container">
            <a href="history.php">購入履歴へ戻る</a>
            <ul>
                <?php foreach($order_historys as $order_history) { ?>
                    <li>注文番号：<?php print $order_history['order_id']; ?></li>
                    <li>購入日時：<?php print $order_history['order_date']; ?></li>
                    <li>合計金額：<?php print number_format($order_history['total']); ?>円</li>
                <?php } ?>
                </ul>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>商品名</th>
                        <th>購入時の商品価格</th>
                        <th>購入数</th>
                        <th>小計</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($details as $detail) { ?>
                    <tr>
                        <td><?php print $detail['name']; ?></td>
                        <td><?php print number_format($detail['price']); ?> 円</td>
                        <td><?php print $detail['amount']; ?></td>
                        <td><?php print number_format($detail['subtotal']); ?> 円</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>