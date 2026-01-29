<?php
class Hand {
    public $Kaarten = [];
    private $spelerNr;
    
    public function __construct($spelerNr) {
        $this->spelerNr = $spelerNr;
        $this->Kaarten = [];
    }
    public function ToevoegenAanHand($kaart){ 
        array_push($this->Kaarten,$kaart); 
    }

    //toon de hand van de speler(id)
    public function ShowHand($id) {
        echo "<hand class='P" . $this->spelerNr . "'>";

        foreach ($this->Kaarten as $key => $kaart) { 
            echo "<kaart onclick='window.location.href=`index.php?Kaart=".$key."`'>"; 

            if($this->spelerNr==$id){$kaart->ShowKaart(true);
            }
            else{
                $kaart->ShowKaart();
            } 
            echo "</kaart>"; 
        }

        echo "</hand>";
    }

    public function VerwijderVanHand($id){
        if(!isset($this->Kaarten[$id])) {
            return null;
        }

        $kaart = $this->Kaarten[$id];

        unset($this->Kaarten[$id]);

        $this->herschikHand(); 
        return $kaart;
    }
    
    //hand weer van 0 is genummerd
    private function herschikHand(){ 
        $nr=0; 
        $tijdelijkDeck = $this->Kaarten;
        $this->Kaarten = [];
        
        foreach($tijdelijkDeck as $kaart){ 
            $this->Kaarten[$nr] = $kaart;
            $nr++;
        }
    }


    public function heeftKaarten() {
        return count($this->Kaarten) > 0;
    }

    public function verwijderWillekeurigeKaart() {
        if (empty($this->Kaarten)) {
            return null;
        }
        $randomKey = array_rand($this->Kaarten);
        return $this->VerwijderVanHand($randomKey);
    }
    

}