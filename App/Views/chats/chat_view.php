<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
include __DIR__ . '/../layouts/header.php';
?>
<?php ob_start(); ?>
<section class="chat_section content_section">
    <div class="chat_list">
        <div class="chat_list_header">
            <span>Tus Chats</span>
        </div>
        <div class="chat_list_body">
            <ul id="listaChats">
            </ul>
        </div>
    </div>
    <div class="chat_window">
        <div class="chat_window_header" id="chatWindowHeader">

        </div>
        <div class="chat_window_body">
            <ul id="messages"></ul>
        </div>
    </div>
</section>
<script>
    const username = '<?= htmlspecialchars($_SESSION['user']['username']) ?>';
    const PATH = '<?= htmlspecialchars($PATH) ?>';
</script>
<script src="<?=$PATH?>Public/scripts/ChatScript.js"></script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../mainView.php';
?>