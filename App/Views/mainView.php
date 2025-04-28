

<!DOCTYPE html>
<html class="h-100" lang='es'>
    <?= $head ?? '' ?>
    <body class="h-100 text-center text-bg-dark" style="padding: 7% 2% 2% 2%; display: flex; flex-direction: column; row-gap: 20px;">
            <?= $header ?? '' ?>
            <div style="display: flex; flex-direction: row; column-gap: 20px; width: 100%; height: 100%;">
                <?= $sidebar ?? '' ?>
                <?= $content ?? '' ?>
            </div>
    </body>
</html>