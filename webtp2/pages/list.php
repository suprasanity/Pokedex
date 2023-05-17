<?php
//si la session n'existe pas, on la crée
if (!isset($_SESSION['pokemons'])){
    $_SESSION['pokemons'] = [];
}
$pokemons = $_SESSION['pokemons'];
?>
<h2>Liste des pokemons</h2>
<table>
    <thead>
        <td>#</td>
        <td>Nom</td>
        <td>Actions</td>
    </thead>
    <tbody>
    <?php
    foreach ($pokemons as $p){
        $pokemon = unserialize($p);
        echo "<tr>";
        echo "<td>".$pokemon->getId()."</td>";
        echo "<td>".$pokemon->getNom()."</td>";
        echo "<td><a href=\"index.php?page=detail&id=".$pokemon->getId()."\">Détail</a> - <a href=\"index.php?page=list&action=remove&id=".$pokemon->getId()."\">Supprimer</a></td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
