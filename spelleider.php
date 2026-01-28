<?php
require_once "deck.php";
require_once "aflegstapel.php";
require_once "hand.php";


class Spelleider{
    public $Deck;
    public $Aflegstapel; 
    public $Spelers = []; 
    private $beurt; 
    private $LR = true; 
    public $winnaar = null;
    
    function __construct($SpelersAantal){
        $this->beurt = 0;
        $this->Spelers = [];

        $this->Deck = new Deck();
        $this->Aflegstapel = new Aflegstapel();
        
        for ($i=0; $i < $SpelersAantal; $i++) { 
            $this->Spelers[] = new Hand($i); //speller hand
        } 

        for ($i = 0; $i < 7; $i++) {
            foreach ($this->Spelers as $hand) {
                $kaart = $this->Deck->Rapen();
                if ($kaart !== null) {
                    $hand->ToevoegenAanHand($kaart);
                }
            }
        }
        
        $eersteKaart = $this->Deck->Rapen();
        $this->Aflegstapel->PlaatsKaart($eersteKaart);
        
        $this->beurt = rand(0, $SpelersAantal - 1);
    }



    private function volgendeSpeler(){
        if($this->LR){
            $this->beurt++;
        }
        else
        {
            $this->beurt--;
        }
         if($this->beurt==count($this->Spelers)){
            $this->beurt=0;
        } if($this->beurt==-1){
            $this->beurt=count($this->Spelers)-1;
        } 
    } 
        function Show(){
            for ($i=0; $i < count($this->Spelers); $i++) { 
                $this->Spelers[$i]->ShowHand($this->beurt); 
            } 
            echo "<tafel>"; 
            $this->Deck->ShowDeck(); 
            $this->Aflegstapel->ShowAflegstapel(); 
            echo "</tafel>"; 
        } 

        private function winnen($laatsteKaart){
            // hoeveel kaarten heeft huidige speler?
            $aantal = count($this->Spelers[$this->beurt]->Kaarten);

            // speler heeft GEEN kaarten meer → mogelijk gewonnen
            if ($aantal === 0) {

            // pestkaarten
            $pestkaarten = ['2', '8', '10', 'J', 'K', 'A', 'X'];

            // laatste kaart was pestkaart → straf
            if (in_array($laatsteKaart->GetWaarde(), $pestkaarten)) {
                $this->kaartPakken();
                $this->kaartPakken();
                return false;
            }

            // echte winnaar
            $this->winnaar = $this->beurt;
            return true;
        }

        return false;
    }
    
    private function speelKaart($kaartid){
        
        $kaart = $this->Spelers[$this->beurt]->Kaarten[$kaartid];
        $bovenste = end($this->Aflegstapel->Kaarten);
        
        $magSpelen =
        $bovenste->GetWaarde() === 'X' ||
        $kaart->GetWaarde() === 'J' ||
        $kaart->GetWaarde() === 'X' ||
        $kaart->GetWaarde() === $bovenste->GetWaarde() ||
        $kaart->GetTeken() === $bovenste->GetTeken();
        
        if(!$magSpelen){
            return;
        }
        
        $kaart = $this->Spelers[$this->beurt]->VerwijderVanHand($kaartid);
        // check win immediately
        if ($this->winnen($kaart)) {
            $this->Aflegstapel->PlaatsKaart($kaart);
            return;
        }
        
        switch ($kaart->GetWaarde()) {
            case '2':
                $this->volgendeSpeler();
                $this->kaartPakken();
                $this->kaartPakken();
                break;
            case '8':
                $this->volgendeSpeler();
                $this->volgendeSpeler();
                break;
            case '10':
                // tien-wasmachine
                $aantalSpelers = count($this->Spelers);
                $doorgegevenKaarten = [];
                
                // take one random card from each player
                for ($i = 0; $i < $aantalSpelers; $i++) {
                    if (count($this->Spelers[$i]->Kaarten) > 0) {
                        $rand = array_rand($this->Spelers[$i]->Kaarten);
                        $doorgegevenKaarten[$i] = $this->Spelers[$i]->VerwijderVanHand($rand);
                    }
                }
                // give the card to the player on the right
                foreach ($doorgegevenKaarten as $van => $kaart) {
                    $naar = $this->spelerRechtsVan($van);
                    $this->Spelers[$naar]->ToevoegenAanHand($kaart);
                }
                $this->volgendeSpeler();
                break;
            case 'A':
                $this->LR = !$this->LR; // reverse direction
                $this->volgendeSpeler();
                break;
            case 'K':
                // same player again, do nothing
                break;
            case 'X':
                $this->volgendeSpeler();
                for($i=0; $i<5; $i++){
                    $this->kaartPakken();
                }
                break;
                
                default:
                $this->volgendeSpeler();
                break;
            }
            
            $this->Aflegstapel->PlaatsKaart($kaart);
        }
    
    private function spelerRechtsVan($index) {
        if ($this->LR) {
            $index--;
        } else {
            $index++;
        }
        if ($index < 0) {
            $index = count($this->Spelers) - 1;
        }
        if ($index >= count($this->Spelers)) {
            $index = 0;
        }

        return $index;
    }
    
    public function Klik($waarde){
        if ($this->winnaar !== null) {
            return; // game is over
            }
            
            if ($waarde === "pakken") {
                $this->Spelers[$this->beurt]->ToevoegenAanHand($this->Deck->Rapen());
                
                if (count($this->Deck->Kaarten) < 3) {
                    $kaarten = $this->Aflegstapel->GeefAlleKaarten();
                    foreach ($kaarten as $kaart) {
                        $this->Deck->Kaarten[] = $kaart;
                    }
                    $this->Deck->Schudden();
                }
                
                $this->volgendeSpeler();
                return;
            }
            
            // card click
            if (!isset($this->Spelers[$this->beurt]->Kaarten[$waarde])) {
                return; // invalid click
                }
                
                $this->speelKaart($waarde);
            
            }
    private function kaartPakken() {
        //Pak een kaart van de stapel en voeg de kaart toe aan de speler 
        if(count($this->Deck->Kaarten) == 0){ 
            $nieuweKaarten = $this->Aflegstapel->GeefAlleKaarten();
            foreach($nieuweKaarten as $kaart){ 
                $this->Deck->Kaarten[] = $kaart; 
            } 
            $this->Deck->Schudden(); 
        }
        $kaart = $this->Deck->Rapen(); 
        if ($kaart !== null) {
            $this->Spelers[$this->beurt]->ToevoegenAanHand($kaart);
        }
    }
}

        
