<h2>Ajouter un Pokemon</h2>
<form method="post" action="index.php" >
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="page" value="list">
    <label>Numero Pokedex</label>
    <input name="id" type="text">
    <label>Nom</label>
    <input name="nom" type="text">
    <label>Type</label>
    <select name="type[]" multiple>
        <option value="Plante">Plante</option>
        <option value="Eau">Eau</option>
        <option value="Feu">Feu</option>
        <option value="Electrique">Electrique</option>
    </select>
    <label>Son évolution</label>
    <select name="evolution">
    <?php
    $pokemons = $_SESSION['pokemons'];
    echo "<option value=\"\"></option>";
    foreach ($pokemons as $p){
        $t = unserialize($p);
        echo "<option value=\"".$t->getId()."\">".$t->getNom()."</option>";
    }
    ?>
    </select>
    <input type="submit" value="Ajouter">

</form>