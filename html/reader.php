<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 01/05/14
 * Time: 22:13
 */
    require('../resources/include.php');

    $viewComic = new ComicViewer();
    //$comicReturn = $viewComic->getPageCount($_GET['id']);
    $comicPageArray = $viewComic->getComicPages($_GET['id']);
    $script = '';
    /*if($comicPageArray){
        $script = "<script type='text/javascript'> var imageArray=".$comicPageArray.";</script>";
    }*/
    //print_r($comicPageArray);

    $title = '';//$seriesArray['comic_series']." (".$seriesArray['comic_start_year'].")";
    echo $viewComic->documentHead($title, $script);
    echo $viewComic->menu();

    echo "<div style='display:none;' id='library'></div>";

    echo "<div style='display:none;' id='series'></div>";

    echo "
    <div id='reader'>
        <div id='comicFrame'></div>
    </div>";