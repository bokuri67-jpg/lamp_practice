<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <meta charset="utf-8">
        <title>購入履歴</title>
        <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'history.css'); ?>">
    </head>
    <body>
        <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        <h1>購入履歴</h1>
        <div class="container">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>注文番号</th>
                        <th>購入日時</th>
                        <th>注文の合計金額</th>
                        <th>購入明細</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($historys as $history) { ?>
                    <tr>
                        <td><?php print $history['order_id']; ?></td>
                        <td><?php print $history['order_date']; ?></td>
                        <td><?php print $history['total']; ?> 円</td>
                        <td>
                            <form method="POST" action="details.php">
                                <input type="hidden" name="order_id" value="<?php print $history['order_id']; ?>">
                                <input class="btn btn-block btn-primary" type="submit" value="購入明細表示">
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
</html>