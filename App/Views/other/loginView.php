<?php ob_start(); ?>
<section class="login">
    <div class="login_container">
        <div class="login_form">
            <h1>Iniciar sesión</h1>
            <form action="?uri=user/login" method="POST">
                <div class="input_group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input_group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Iniciar sesión</button>
            </form>
            <p>¿No tienes una cuenta? <a href="/register">Regístrate aquí</a></p>
        </div>
    </div>
</section>
<style>
    .login {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #121212;

        & button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        & button:hover {
            background-color: #0056b3;
        }

        & p {
            color: #ffffff;
            margin-top: 15px;
        }

        & a {
            color: #007bff;
            text-decoration: none;
        }
        
        & a:hover {
            text-decoration: underline;
        }
    }
    .login_container {
        background-color: #1e1e1e;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .login_form h1 {
        color: #ffffff;
        margin-bottom: 20px;
    }
    .input_group {
        margin-bottom: 15px;
    }
    .input_group label {
        color: #ffffff;
        display: block;
        margin-bottom: 5px;
    }
    .input_group input {
        width: 100%;
        padding: 10px;
        border-radius: 4px;
        border: none;
        background-color: #2a2a2a;
        color: #ffffff;
    }

    @media (max-width: 600px) {
        .login_container {
            width: 90%;
            padding: 15px;
        }
        .login_form h1 {
            font-size: 24px;
        }
        button {
            font-size: 16px;
        }
    }
    @media (min-width: 601px) and (max-width: 1200px) {
        .login_container {
            width: 80%;
            padding: 20px;
        }
        .login_form h1 {
            font-size: 28px;
        }
        button {
            font-size: 18px;
        }
    }
    @media (min-width: 1201px) {
        .login_container {
            width: 50%;
            padding: 30px;
        }
        .login_form h1 {
            font-size: 32px;
        }
        button {
            font-size: 20px;
        }
    }
    @media (min-width: 1600px) {
        .login_container {
            width: 40%;
            padding: 40px;
        }
        .login_form h1 {
            font-size: 36px;
        }
        button {
            font-size: 22px;
        }
    }
    @media (min-width: 2000px) {
        .login_container {
            width: 30%;
            padding: 50px;
        }
        .login_form h1 {
            font-size: 40px;
        }
        button {
            font-size: 24px;
        }
    }

</style>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../mainView.php'; ?>