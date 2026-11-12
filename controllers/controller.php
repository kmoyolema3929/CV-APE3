<?php
    class PaginasController{
        
        public function enlacesPaginasController(){
            if(isset($_GET['accion']))
                {
                    $enlacesController=$_GET['accion'];
                }
                else {
                    $enlacesController="inicio.php";
                }
                $respuesta=enlacesPagina::enlacePaginaModel($enlacesController);
                include $respuesta;
        }
        public function getTemplate(){
            include "views/template.php";
        }
    }
?>