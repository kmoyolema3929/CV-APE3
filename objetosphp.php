<?php
class auto{
    public $placa;
    public $marca;

    function __construct($placa,$marca)
    {
        $this->placa=$placa;
        $this->marca=$marca;
    }
    function mostrar()
    {
        echo($this->placa." ".$this->marca);
    }
}
    /*$automvil1=new auto();
    $automvil1->placa="ppp111";
    $automvil1->marca="mercedes";
    $automvil1->mostrar();*/

    $automvil1=new auto("ppp222", "mazda");
    $automvil1->mostrar();
?>    