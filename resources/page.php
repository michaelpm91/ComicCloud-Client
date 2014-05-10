<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 01/05/14
 * Time: 22:08
 */

class page {

    public function __construct(){

    }
    public function documentHead($title = 'Default', $script=''){
        $header = "
                <!DOCTYPE html>
                    <head>
                        <title>Comic Cloud - ".$title."</title>
                        <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'/>
                        <link href='/css/main.css' rel='stylesheet' type='text/css'/>
                        <link href='/css/reader.css' rel='stylesheet' type='text/css'/>
                        <script src='http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js'></script>
                        <link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css'/>
                        <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js'></script>
						<!--<link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css'/>
						<script src='http://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js'></script>-->
                        <script src='/scripts/ext/jquery.history.js'></script>
                        <script src='/scripts/ext/staterouter.js'></script>
                        <script src='/scripts/ext/imagesloaded.pkgd.min.js'></script>
                        <script src='/scripts/ext/modernizr.js'></script>
                        <!--<script src='/scripts/contextMenu/jquery.contextMenu.js'></script>
                        <link href='/scripts/contextMenu/jquery.contextMenu.css' rel='stylesheet' type='text/css'/>-->
                        <script src='/scripts/main.js'></script>

                        <script src='/scripts/upload.js'></script>".
                        $script.
                        "<script src='/scripts/reader.js'></script>
                        <meta name='viewport' content='initial-scale=1, maximum-scale=1, minimal-ui'>
                        <!--<base href='/'>-->
                    </head>
                    <body data-role='none'>
                        <div id='ajaxLoader'></div>
                        <div id='ajaxLoader2'></div>";

        return $header;

    }
    public function menu(){
        $menu = "
            <div id='menu'>
                <ul>
                    <li><a href='/library.php'>Library</a></li>
                    <li><a href='/signout.php'>Sign out</a></li>
                </ul>
            </div>
            <div id='menuContainer'>
                <div id='menuButton'></div>
                <div id='searchContainer'>
                    <input placeholder='Search' id='search' type='search'>
                </div>
            </div>";
        return $menu;
    }

    public function getLibrary($org ='',$offset = 0){
        global $db, $userid;
        $userid = 1;
        try {
            //$stmt = $db->prepare("SELECT * FROM comicSeries WHERE seriesName LIKE :value LIMIT 16 OFFSET :offsetValue");
            //$stmt = $db->prepare("SELECT comic_series, comic_start_year, comic_cover_image FROM comicsInfo WHERE uploader_id = :userid AND comic_series LIKE :value GROUP BY comic_series, comic_start_year ORDER BY comic_issue LIMIT 16 OFFSET :offsetValue");
            switch ($org){

                case "recentlyUploaded":

                    break;

                case "recentlyRead":

                    break;

                default:
                    $stmt = $db->prepare("SELECT comic_id,comic_series_id,comic_series,min(comic_issue),comic_start_year,comic_cover_image,pages,finished_processing,comic_size FROM comics WHERE user_id =:userid GROUP BY comic_series_id ORDER BY comic_series LIMIT 16 OFFSET :offsetValue");
                    $stmt->bindValue(':userid', $userid);
                    $stmt->bindValue(':offsetValue',intval($offset),PDO::PARAM_INT);
            }
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($result);
            if (!empty($result) ) {
                $comicResults = '';
                foreach($result as $element) {
                    $comicResults .= "<a title='".htmlspecialchars($element['comic_series'])." (".htmlspecialchars($element['comic_start_year']).")' data-series-name='".htmlspecialchars($element['comic_series'])."' data-series-id='".$element['comic_series_id']."' href='/s/".$element['comic_series_id']."'><div class='comicCard'><img src='/image.php?id=".$element['comic_id']."&page=1&thumb=TRUE'/><p>".htmlspecialchars($element['comic_series'])." (".htmlspecialchars($element['comic_start_year']).")</p></div></a>";
                }
                return $comicResults;
            } else {
                return $comicResults = "<p id='noResults'>No Comics added yet</p>";
            }
        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
    public function getComicSeries($seriesid, $offset = 0, $asArray = false){
        global $db, $userid;
        $userid = 1;//CHANGE
        try {
            //$stmt = $db->prepare("SELECT * FROM comics INNER JOIN comicSeries ON  WHERE seriesID = :value ORDER BY issue DESC LIMIT 16 OFFSET :offsetValue");
            //$stmt = $db->prepare("SELECT a.*, b.* FROM comics AS a INNER JOIN comicSeries AS b ON a.seriesID=b.id");
            $stmt = $db->prepare("SELECT * FROM comics WHERE user_id = :userid AND comic_series_id = :seriesid ORDER BY comic_issue LIMIT 16 OFFSET :offsetValue");
            $stmt->bindValue(':seriesid', $seriesid);
            $stmt->bindValue(':userid', intval($userid),PDO::PARAM_INT);
            $stmt->bindValue(':offsetValue', intval($offset),PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($result)){
                $comicResults = '';
                if($asArray == true){
                    return $result[0];
                }
                //print_r($result);
                foreach($result as $element) {
                    $process = '';
                    if($element['finished_processing'] == FALSE){
                        $process='isProcessing';
                    }
                    $comicResults .= "<a class='$process' data-comic-id='".$element['comic_id']."' data-series-name='".$element['comic_series_name']."' href='/c/".$element['comic_id']."'><div class='comicCard'><img src='/image.php?id=".$element['comic_id']."&page=1&thumb=TRUE'/><p> #".$element['comic_issue']."</p></div></a>";
                }
                return $comicResults;
            }else{
                return $comicResults = "<p id='noResults'>No Results Found</p>";
            }
        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }

}
class ComicViewer extends page{

    public function getPageCount($comicID,$asArray = false){
        global $db;
        try {
            $stmt = $db->prepare("SELECT * FROM comics WHERE comic_id= :value");
            $stmt->execute(array(':value' => $comicID));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty($result)){
                if($asArray == true){
                    return $result;
                }
                return $pagesArrayCount = count(json_decode($result['pages'],true)) + 1;
            }
        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
    public function getComicPages($comicID,$asArray = false){

        global $db;
        //$comicID = simpleSanitise($comicID);
        try {
            //$stmt = $db->prepare("SELECT a . * , b . * FROM comics AS a INNER JOIN comicsInfo AS b ON a.comic_id = b.comic_id WHERE b.comic_id= :value");
            $stmt = $db->prepare("SELECT * FROM comics WHERE comic_id= :value");
            $stmt->execute(array(':value' => $comicID));

            //$result = $stmt->fetchAll();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($result)){
                if($asArray == true){
                    return $result;
                }

                $pagesArray = json_decode($result['pages'],true);
                $proxyPagesArray = array();
                foreach($pagesArray as $index => $page){
                    $proxyPagesArray[] = "/image.php?id=".$comicID."&page=".($index + 1);
                }
                return json_encode($proxyPagesArray);



            } else {
                return $comicResults = "<p id='noResults'>No Comic Found</p>";
            }

        } catch(PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }

    public function currentPage(){

    }
}