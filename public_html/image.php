<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 01/05/14
 * Time: 22:28
 */
require('../resources/include.php');
$comic_id = $_GET['id'];
$pageNo = $_GET['page'];
if(isset($comic_id)){

    $stmt = $db->prepare("SELECT * FROM comicNew WHERE comic_id= :comic_id");
    $stmt->execute(array(':comic_id' => $comic_id));

    //$result = $stmt->fetchAll();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($result);
    $pages = json_decode($result['pages'], true);

    header('Content-type: image/jpeg');
    echo file_get_contents(COMICLOCATION.$pages[$pageNo]);

}
