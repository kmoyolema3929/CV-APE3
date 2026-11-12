<?php
class enlacesPagina{
    public static function enlacePaginaModel($enlacesModel){
        if($enlacesModel=="nosotros" ||
        $enlacesModel=="servicios" ||
        $enlacesModel=="contactanos")
        {
            $modulo="views/".$enlacesModel.".php";  
        }
        else {
        $modulo="views/inicio.php";
        }
        return $modulo;
    }
    
}
?>