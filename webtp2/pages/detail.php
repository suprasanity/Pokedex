<?php

$id = getFieldFromForm("id");
$pokemons = $_SESSION["pokemons"];
$pokemon = null;

foreach ($pokemons as $p){
    $t = unserialize($p);
    if($id == $t->getId()){
        $pokemon = $t;
    }
}
?>
<h2>Infos détaillées</h2>
<p>Numéro de Pokédex : <?php echo $pokemon->getId()?></p>
<p>Nom : <?php echo $pokemon->getNom()?></p>
<p>Types :</p>
<ul>
    <?php
    foreach($pokemon->getType() as $type){
        echo "<li>$type</li>";
    }
    ?>
</ul>
<p>Evolution :
<?php
//it has to be  a link to the pokemon password_get_info(hash)
if($pokemon->getEvolution()!=null){
	foreach ($pokemons as $p){
	    $t = unserialize($p);
	    if($pokemon->getEvolution() == $t->getNom()){
	        $evoPokemon = $t->getId();
	    }
	}
echo "<a href=\"index.php?page=detail&id=".$evoPokemon."\">".$pokemon->getEvolution()."</a></p>";

}else{
	echo "<p>".$pokemon->getEvolution()."</a></p>";

}


?>
</p>
<?php echo "<p><a href=\"index.php?page=list&action=remove&id=".$pokemon->getId()."\">Supprimer</a></p>"; ?>