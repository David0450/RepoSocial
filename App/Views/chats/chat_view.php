<?php ob_start(); ?>
<main>
    <aside>
        <div>
            <h2>Nombre</h2>
        </div>
        <div>
            <h2>Nombre</h2>
        </div>
        <div>
            <h2>Nombre</h2>
        </div>
    </aside>
</main>
<style>
    * {
        margin: 0;
        padding: 0;
    }
    aside {
        display: flex;
        flex-direction: column;
        background-color: aliceblue;
        width: 20vw;
        height: 100%;

        & > div {
            height: 10%;
            background-color: beige;
            border-top: 1px solid grey;
            border-bottom: 1px solid grey;
        }
    }
</style>