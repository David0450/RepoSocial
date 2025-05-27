<?php ob_start(); ?>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h4>Sobre nosotros</h4>
            <p>Breve descripción</p>
        </div>
        <div class="footer-section">
            <h4>Enlaces</h4>
            <ul>
                <li><a href="<?= $PATH ?>project/view">Inicio</a></li>
                <li><a href="#">Chat</a></li>
                <li><a href="#">Eventos</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Síguenos</h4>
            <div class="social-icons">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
                <a href="#"><i class="fab fa-discord"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2025 RepoSocial. Todos los derechos reservados.
    </div>
</footer>

<?php $footer = ob_get_clean(); ?>