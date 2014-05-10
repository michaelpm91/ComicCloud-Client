<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 01/05/14
 * Time: 22:28
 */
require('../resources/include.php');
$comic_id = $_GET['id'];
$pageNo = ($_GET['page']-1);
if(isset($comic_id)){

    $stmt = $db->prepare("SELECT * FROM comics WHERE comic_id= :comic_id");
    $stmt->execute(array(':comic_id' => $comic_id));

    //$result = $stmt->fetchAll();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($result);
    $pages = json_decode($result['pages'], true);

    header('Content-type: image/jpeg');

    if(isset($_GET['thumb']) && $_GET['thumb']== TRUE ){

        $extension_pos = strrpos($pages[$pageNo], '.'); // find position of the last dot, so where the extension starts
        $pages[$pageNo] = $thumb = substr($pages[$pageNo], 0, $extension_pos) . '_thumb' . substr($pages[$pageNo], $extension_pos);

    }

    echo file_get_contents(COMICLOCATION.$pages[$pageNo]);


}
