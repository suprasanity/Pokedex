<?php
//ini_set('error_reporting', E_ALL);
global $pdo;
$host = 'localhost';
$port = '3306';
$dbname = 'Pokedex';
$user = 'pokegame';
$password = 'pokegame';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
session_start();
require_once "pokemon.php";

function getFieldFromForm($name){
    return isset($_POST[$name])?$_POST[$name]:(isset($_GET[$name])?$_GET[$name]:null);
}

function getArrayFieldFromForm($name){
    return isset($_POST[$name])?$_POST[$name]:(isset($_GET[$name."[]"])?$_GET[$name."[]"]:null);
}


if(!isset($_SESSION['pokemons'])) {


    $sql = "SELECT p.id, p.nom, t.name AS type , e.nom AS evolution
FROM pokemon p
LEFT JOIN pokemon_type pt ON p.id = pt.id_pokemon
LEFT JOIN type t ON pt.id_type = t.id
LEFT JOIN pokemon e ON p.evolution = e.id
GROUP BY p.id, p.nom, e.nom;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pokemons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($pokemons as $pokemon) {
        $pokemonId = $pokemon['id'];
        $pokemonName = $pokemon['nom'];
        $pokemonEvolution = $pokemon['evolution'];

        // Retrieve the types for the current Pokémon
        $sqlTypes = "SELECT t.name AS type
                 FROM pokemon_type pt
                 JOIN type t ON pt.id_type = t.id
                 WHERE pt.id_pokemon = :pokemonId";
        $stmtTypes = $pdo->prepare($sqlTypes);
        $stmtTypes->bindParam(':pokemonId', $pokemonId, PDO::PARAM_INT);
        $stmtTypes->execute();
        $types = $stmtTypes->fetchAll(PDO::FETCH_COLUMN);
        //get the pokemon name of evolution if $pokemonEvolution is not null

        echo "<script>console.log('Debug Objects: " . $pokemonEvolution . "' );</script>";
        //console log evolution

        $p = new Pokemon($pokemonId, $pokemonName, $types, $pokemonEvolution);
        $_SESSION['pokemons'][] = serialize($p);
    }
}

$action = getFieldFromForm("action");

switch ($action){
    case "add" :

        $id = getFieldFromForm("id");
        $nom = getFieldFromForm("nom");
        $type = getArrayFieldFromForm("type");
        $evolution = getFieldFromForm("evolution");

        //add the pokemon to database
        $sql = "INSERT INTO pokemon (id, nom, evolution) VALUES (:id, :nom, :evolution)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':evolution', $evolution, PDO::PARAM_INT);
        $stmt->execute();

        //find the id of the type by name

    foreach ($type as $t) {
        $sql = "SELECT id FROM type WHERE name = :type";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type', $t, PDO::PARAM_STR);
        $stmt->execute();
        $typeId = $stmt->fetch(PDO::FETCH_ASSOC);
        $sql = "INSERT INTO pokemon_type (id_pokemon, id_type) VALUES (:id_pokemon, :id_type)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_pokemon', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_type', $typeId['id'], PDO::PARAM_INT);
        $stmt->execute();
        }


        $p = new Pokemon($id,$nom,$type,$evolution);
        $_SESSION['pokemons'][] = serialize($p);
        break;
    case "remove":
        $id = getFieldFromForm("id");

        // Remove the Pokémon from the session
        $t = [];
        foreach ($_SESSION['pokemons'] as $v) {
            $p = unserialize($v);
            if ($p->getId() != $id) {
                $t[] = serialize($p);
            } else {
                // Delete the Pokémon from the database
                $sql = "DELETE FROM pokemon WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

            }
        }
        $_SESSION['pokemons'] = $t;
        break;
    case "session-destroy":
        session_destroy();
        break;
}

//Recuperer les parametres
$page = getFieldFromForm("page");
if(!isset($page)){
    $page = "home";
}
include "header.php";
include "menu.php";
include "pages/$page.php";
include "footer.php";
