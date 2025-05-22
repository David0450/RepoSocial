<?php
include __DIR__ . '/../layouts/footer.php';
include __DIR__ . '/../layouts/head.php';
include __DIR__ . '/../layouts/header.php';
?>
<?php ob_start(); ?>
<section class="chat_section content_section">
    <div class="chat_list text-bg-dark">
        <div class="chat_list_header">
            <h3>Lista de chats</h3>
        </div>
        <div class="chat_list_body">
            <ul id="listaChats">
                <?php foreach ($chats as $chat): ?>
                    <?php
                        $miembrosArray = array_filter(
                            explode(',', $chat['miembros']),
                            function($miembro) {
                                return trim($miembro) !== $_SESSION['user']['username'];
                            }
                        );
                        $miembros = implode(', ', array_map('trim', $miembrosArray));
                    ?>
                    <li data-chat-id="<?= $chat['chat_id'] ?>" data-chat-name="<?= htmlspecialchars($miembros) ?>">
                        <?= htmlspecialchars($miembros) ?>
                    </li>
                <?php endforeach; ?>
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