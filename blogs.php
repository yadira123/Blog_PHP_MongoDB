<?php

    require_once('vendor/autoload.php');
    try {
        $connection = new MongoDB\Client;
        $database = $connection->miblog;
        $collection = $database->articles;
    } catch (Exception $e) {
        die("Fallo en la conexión a la base de datos " . $e->getMessage());
    }
    $cursor = $collection->find(
        [],
        ['sort'=>['_id'=>-1]]
    );
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="style.css" />
        <title>Mi Blog personal</title>
    </head>
    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h1>Mi Blog personal</h1>
                <?php foreach($cursor as $doc){
                    ?>
                    <h2><?php echo $doc['title']; ?></h2>
                    <p>
    <?php echo substr($doc['content'], 0, 80) . '...'; ?>
                    </p>
                    <a href="blog.php?id=<?php echo $doc['_id']; ?>">Leer M&aacute;s</a>
                <?php } ?>
            </div>
        </div>
    </body>
</html>

<?php 
/* Ejemplo de consulta en el shell de Mongo que obtiene todos los documentos de
 * una colección llamada peliculas que tienen su campo genero configurado como
 * aventura:
 * >db.peliculas.find({"genero":"aventura"})
{ "_id" : ObjectId("4db439153ec7b6fd1c9093ec"), "nombre" : "guardianes de la noche", "genero" : "aventura", "año" : 2009 }
 */
?>