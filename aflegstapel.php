<?php
class Aflegstapel { 
    public $Kaarten; 
    
    function __construct(){
        $this->Kaarten = [];
         } 
    function PlaatsKaart($kaart){ 
        $this->Kaarten[] = $kaart;
    } 

    //wanneer het deck bijna leeg is
    function GeefAlleKaarten(){ 
        $alleKaarten = [];
    
        for ($i = 0; $i < count($this->Kaarten) - 1; $i++) {
            $alleKaarten[] = $this->Kaarten[$i];
            unset($this->Kaarten[$i]);
        }
        
        $this->Kaarten = array_values($this->Kaarten);
        return $alleKaarten;
    } 
    
    function ShowAflegstapel(){ 
        echo "<aflegstapel>"; 
        if (!empty($this->Kaarten)) {
            //toon alleen bovenste kaart
            $top = $this->Kaarten[count($this->Kaarten)-1];
            echo "<kaart>"; 
            $top->ShowKaart(true);
            echo "</kaart>";
        }
        echo "</aflegstapel>"; 
    }
}