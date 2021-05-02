<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <meta charset="utf-8">
        <title>購入履歴明細</title>
        <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'history.css'); ?>">
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        <h1>購入履歴明細</h1>

        <div class="container">
            <a href="history.php">購入履歴へ戻る</a>
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
                        <td><?php print $detail['price']; ?> 円</td>
                        <td><?php print $detail['amount']; ?></td>
                        <td><?php print $detail['subtotal']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>