<?php
    require 'vendor/autoload.php';
//$action almacena si se pulsa en el boton salvar en caso de que se haga click sobre el boton salvar guardar 'save_article' si no 'show_form'
$action = (!empty($_POST['btn_submit']) && ($_POST['btn_submit'] === 'Salvar')) ? 'save_article' : 'show_form';
//verificando lo que contiene article
switch ($action) {
    //si contiene 'save_article'
    case 'save_article':
        try {
            $connection = new MongoDB\Client;//conecta con un cliente
            $database = $connection->miblog;//selecciona la bd miblog
            $collection = $database->articles;//s.. la coleccion
             /* método alternativo de selección base datos colección:
             * $connection = new Mongo();
             * $collection = $connection->miblog->articles;
             */
            /*$article = array();//crenado un onj tipo array el cual contendrá todos los datos del articulo
            $article['title'] = $_POST['title'];
            $article['content'] = $_POST['content'];
            //$article['saved_at'] = new mongodate();
            $collection->insertOne($article);*/
            
            //crenado variable spara almacenar los datos
            $title=$_POST['title'];
            $content=$_POST['content'];
            $saved_at=new MongoDB\BSON\UTCDatetime();
            
            $insertOneResult=$collection->insertOne(
                ['title'=>$title, 'content'=>$content, 'saved_at'=>$saved_at]
            );
            
        } catch (Exception $e) {
            die("No se ha podido conectar a la base de datos " . $e->getMessage());
        } catch (Exception $e) {
            die('No se han podido insertar los datos ' . $e->getMessage());
        }
        /*código alternativo si queremos que el método insert espere resputesta de MongoDB:
         * try {
         * $status = $connection->insert(array('title' => 'Titulo Blog', 'content' => 'Contenido Blog'), array('safe' => True));
         * echo "Operación de inserción completada";
         * } catch (MongoCursorException $e) {
         * die("Insert ha fallado ".$e->getMessage());
         * }
         */
        
         /* Cuando hacemos un insert 'safe' podemos utilizar un parámetro timeout opcional:
         * try {
         * $collection->insert($document, array('safe' => True, 'timeout' => True));
         * } catch (MongoCursorTimeoutException $e) {
         * die('El tiempo de espera para Insert ha finalizado '.$e->getMessage());
         */
        
         /* Podemos añadir un _id personalizado con un insert:
          * $username = 'Juan';
          * try{
          * $document = array('_id' => hash('sha1', $username.time()),
          * 'user' => $username, 'visited' => 'homepage.php');
          * $collection->insert($document, array('safe' => True));
          * } catch(MongoCursorException $e) {
          * die('Failed to insert '.$e->getMessage());
          * }
          */
        break;
    case 'show_form':
    default:
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="style.css"/>
        <title>Creador de Posts</title>
    </head>
    <body>
        <div id="contentarea">
            <div id="innercontentarea">
                <h1>Creador de Posts</h1>
                <!--si la variable action es 'show_form' muestra el formulario-->
                <?php if ($action === 'show_form'): ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <h3>Título</h3>
                        <p>
                            <input type="text" name="title" id="title">
                        </p>
                        <h3>Contenido</h3>
                        <textarea name="content" rows="20"></textarea>
                        <p>
                            <input type="submit" name="btn_submit" value="Salvar"/>
                        </p>
                    </form>
                <!--Si no muestra q se ha insertado un nuevo articulo-->
                <?php else: ?>
                    <p>
                        Artículo salvado. _id:<?php echo $insertOneResult->getInsertedId(); ?>.
                        <a href="blogpost.php"> &iquest;Escribir otro?</a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>

