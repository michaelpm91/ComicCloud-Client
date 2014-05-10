<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 03/05/14
 * Time: 00:38
 */
require('../resources/include.php');

$fn = (isset($_SERVER['HTTP_FILE_NAME']) ? $_SERVER['HTTP_FILE_NAME'] : false);

if ($fn) {

    // AJAX call
    file_put_contents(
        '../uploads/' . $fn,
        file_get_contents('php://input')
    );
    //echo "$fn uploaded";
    //exit();

    //Title Match
    $titlePreg = ' Vol.\s?[0-9]+| V\s?[0-9]+| #[0-9]+|\(.*?\)|\.[a-z0-9A-Z]+$';
    $comicTitle = preg_replace('/'.$titlePreg.'/', "", $fn);
    $comicTitle = trim($comicTitle);
    //Year Match
    $volPreg = 'Vol.\s?[0-9]+| V\s?[0-9]+';//Needs to catch brackets as well eg. (2011)
    preg_match('/'.$volPreg.'/', $fn, $startYearArray);

    $start_year = '0000';
    if(count($startYearArray)>0){
        $start_year = preg_replace("/[^0-9]/", "", $startYearArray[0]);
    }

    //Issue Match
    $issue = '1';
    preg_match("/#[0-9]+/", $fn, $issueArray);

    if(count($issueArray)>0){
        $issue = preg_replace("/[^0-9]/", "", $issueArray[0]);
    }


    try {
        $stmt = $db->prepare("INSERT INTO comics VALUES(:user_id, :comic_id, :series_id, :series_name, :issue, :start_year, '' , '', FALSE, :file_size)");
        $stmt->execute(array(
            ':user_id' => '1',
            ':comic_id' => $_SERVER['HTTP_COMIC_ID'],
            ':series_id' => $_SERVER['HTTP_SERIES_ID'],
            ':series_name' => $comicTitle,
            ':issue' => $issue,
            ':start_year' => $start_year,
            ':file_size' => $_SERVER['HTTP_FILE_SIZE']
        ));
    } catch (PDOException $e){
        echo "Database Write Failed ";
        echo $e;
    }

    $client = new GearmanClient();
    $client->addServers("172.31.36.51:4730");
    $client->doBackground("process",serialize(array($fn, $_SERVER['HTTP_COMIC_ID'])));


}