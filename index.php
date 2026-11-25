<?php
require_once "models/sesion.php";
require_once "controllers/controller.php";
require_once "models/model.php";
require_once "models/usuario.php";
$mvc=new PaginasController();
$mvc->procesarSesion();
$mvc->getTemplate();
?>