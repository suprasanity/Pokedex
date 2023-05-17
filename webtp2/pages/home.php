<?php
if (!isset($_SESSION['pokemons'])){
    $_SESSION['pokemons'] = [];
}
$pokemons = $_SESSION['pokemons'];
$nb = sizeof($pokemons);
$nbEvo = 0;
$nbPerType = [];

foreach ($pokemons as $p){
    $poke = unserialize($p);
    if($poke->getEvolution()){
        $nbEvo++;
    }
    //si poke non null
    if ($poke != null){
        //si le type n'est pas dans le tableau
        foreach ($poke->getType() as $type){
            if(!isset($nbPerType[$type])){
                $nbPerType[$type] = 0;
            }
            $nbPerType[$type]++;
        }
    }
}

?>
<h2>Bienvenue sur ton Pokedex</h2>

<?php echo "Il y a actuellement $nb Pokemon dans ton Pokedex. Ajoutes-en vite de nouveaux !";?>

<h3>Nb Pokemon par type </h3>
<?php print_r($nbPerType) ?>

Nb base : <?php echo ($nb - $nbEvo); ?>
Nb evolution : <?php echo ($nbEvo); ?>
