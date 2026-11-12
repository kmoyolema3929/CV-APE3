<style>
    .login-caja { max-width: 380px; margin: 30px auto; background: #fff; padding: 25px; border-radius: 6px; border-left: 6px solid rgb(218, 150, 150); box-shadow: 0 2px 6px rgba(0,0,0,.12); font-family: Arial, Helvetica, sans-serif; }
    .login-caja h2 { margin-top: 0; }
    .login-caja label { display: block; margin: 12px 0 4px; font-weight: bold; }
    .login-caja input[type=text], .login-caja input[type=password] { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #bbb; border-radius: 4px; }
    .login-caja button { width: 100%; margin-top: 18px; padding: 10px; border: 0; border-radius: 4px; background: rgb(176, 98, 98); color: #fff; font-size: 16px; cursor: pointer; }
    .login-caja button:hover { background: rgb(150, 80, 80); }
    .login-error { background: #f8d7da; color: #842029; padding: 10px; border-radius: 4px; margin-bottom: 10px; }
</style>

<div class="login-caja">
    <h2>Iniciar sesión</h2>
    <p>Para ver la información de los estudiantes debes iniciar sesión.</p>
    <?php if ($error !== '') { ?>
        <div class="login-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>
    <form method="post" action="index.php?accion=login">
        <input type="hidden" name="destino" value="<?php echo htmlspecialchars($destino, ENT_QUOTES, 'UTF-8'); ?>">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required autofocus>
        <label for="clave">Contraseña</label>
        <input type="password" id="clave" name="clave" required>
        <button type="submit">Ingresar</button>
    </form>
</div>