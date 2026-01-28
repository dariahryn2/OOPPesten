<?php

require_once "card.php";
class Deck{
    public $Kaarten = [];
    private $tekens = ["H", "R", "K", "S"];
    private $waardes = ["2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A"];
    
    public function __construct(){
    $this ->maakNieuwDeck();
    $this->Schudden();
    }
    
    private function maakNieuwDeck(){ 
        $nr = 0; 
        for($T=0; $T < count($this->tekens); $T++){ 
            for($W=0; $W < count($this->waardes); $W++){
                $this->Kaarten[$nr] = new Kaart($this->waardes[$W], $this->tekens[$T]); 
                $nr++; 
            }
            //2 jokers
            $this->Kaarten[] = new Kaart("X", "X");
            $this->Kaarten[] = new Kaart("X", "X");
        } 
    }

    public function ShowDeck(){ 
        echo "<deck onclick='window.location.href=`index.php?Kaart=pakken`;'>"; 

        foreach ($this->Kaarten as $kaart) { 
            echo "<kaart>"; 
            $kaart->ShowKaart();
            echo "</kaart>"; 
        }
        echo "</deck>";
    }

    public function Schudden() {
        shuffle($this->Kaarten);
        shuffle($this->Kaarten);
    }
    
    public function Rapen(){
        if (count($this->Kaarten) == 0) {
            return null;
        }
        
        return array_pop($this->Kaarten);
    }

}