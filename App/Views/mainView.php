<!DOCTYPE html>
<html class="h-100" lang='es'>
    <?= $head ?? '' ?>
    <body class="text-center text-bg-dark <?php if ($isAdmin) echo 'admin'; ?>">
            <?= $header ?? '' ?>
            <main>
                <?= $sidebar ?? '' ?>
                <?= $content ?? '' ?>
            </main>
            <?= $footer ?? '' ?>
    </body>
</html>