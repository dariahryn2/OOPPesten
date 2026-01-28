<?php
    require_once "deck.php";
    require_once "hand.php";
    require_once "spelleider.php";

    session_start(); 

    if(isset($_GET['reset'])){
        session_destroy();
        header("location:Index.php");
    }

    $aantalSpelers = 2; 

    if(isset($_SESSION['Game'])){
        $Game = $_SESSION['Game'];
    }
    else
    {
        $Game = new Spelleider($aantalSpelers); 
    }
        if(isset($_GET['Kaart'])){
            $Game->Klik($_GET['Kaart']);
        }
        
    
        $D = min(21, count($Game->Deck->Kaarten));
        $A = min(21, count($Game->Aflegstapel->Kaarten));

     ?>

<html>
<head>
<title> OOP Pesten </title>

<style type="text/css">

body {
    background-color:green;
}

kaart img { 
    height: 154px; 
}

hand {
    width: 300px;
    height: 200px;
    display: block;
    position: absolute;
}

hand kaart {
    display: block;
    position: inherit;
    bottom: 0px;
}

hand kaart:hover {
    top: 0px;
}

deck, aflegstapel {
    width: 125px;
    left: 250px;
    height: 175px;
    float: left;
    display: block;
}

deck kaart {
    left: <?php echo $D;?>px;
    bottom: <?php echo $D;?>px;
    position: absolute;
}

tafel {
    width: 250px;
    left: 250px;
    top: 250px;
    position: absolute;
}

<?php for($d=$D;$d>0;$d--){ ?>
deck kaart:nth-child(<?php echo $d;?>) {
    left: <?php echo $d;?>px;
    bottom: <?php echo $d;?>px;
}
<?php } ?>



.P0 { left: 250px; bottom: 20px; }
.P1 { left: 20px; top: 250px; }
.P2 { left: 250px; top: 20px; }
.P3 { left: 450px; top: 250px; }


 
<?php for ($s=0; $s < $aantalSpelers; $s++) {
    $kaartaantal = count($Game->Spelers[$s]->Kaarten); 
    if($kaartaantal>15){
        $graden=150;
        }
        else
        {
            $graden=80;
            }
            for ($k=1; $k <= $kaartaantal; $k++) { 
                echo ".P".$s." kaart:nth-child(".$k."){
                transform: rotate(".((($graden/$kaartaantal)*$k)-($graden/2))."deg);left:".(50+(($graden/$kaartaantal)*$k))."px;}";
                }
                }
                ?>

<?php for($a=$A;$a>0;$a--){?>
    aflegstapel kaart:nth-child(<?php echo $a;?>) {
    left:<?php echo $a+125;?>px;
    bottom:<?php echo $a;?>px;
}<?php } ?>

    

</style>

</head>


<body>

<a href="index.php?reset=">Reset Game</a>


<?php if ($Game->winnaar !== null): ?>
    <div style="
        color:white;
        font-size:32px;
        text-align:center;
        margin-top:20px;
        font-weight:bold;
    ">
        🎉 Speler <?php echo $Game->winnaar + 1; ?> heeft gewonnen!
        <br>
        <a href="index.php?reset=1" style="color:yellow;">Nieuw spel</a>
    </div>
<?php endif; ?>


    <?php
    $Game->Show();
    $_SESSION['Game'] = $Game;
    ?>



</body>

</html>