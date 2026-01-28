<?php
Class Kaart { 
    private $waarde; 
    private $teken; 

    function __construct($waarde,$teken){ 
        $this->waarde = $waarde; 
        $this->teken = $teken; 
    } 


public function GetWaarde(){
    return $this->waarde;
}

public function GetTeken(){
    return $this->teken;
}

public function ShowKaart($Zichtbaar = false) {
    if($Zichtbaar){    
        $file = $this->teken . $this->waarde  . ".svg";
        echo "<img src='img/$file' alt='{$this->waarde} {$this->teken}' style='width:80px;' />";
    } else {
        echo "<img src='img/back.png' alt='Card Back' style='width:80px;' />";
    }
}

}