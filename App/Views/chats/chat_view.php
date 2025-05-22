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
                    <li data-chat-id="<?= $chat['chat_id'] ?>">
                        <?= htmlspecialchars(implode(', ', array_filter(explode(',', $chat['miembros']), function($miembro) {
                            return trim($miembro) !== $_SESSION['user']['username'];
                        }))) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div>
        <input type="text" id="msg" placeholder="Escribe un mensaje">
        <button onclick="enviarMensaje()">Enviar</button>
        <ul id="messages"></ul>
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