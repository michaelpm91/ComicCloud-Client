<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 01/05/14
 * Time: 22:12
 */
require('../resources/include.php');


$viewSeries = new Page();
$seriesArray = $viewSeries->getComicSeries($_GET['series'],0,true);
$title = $seriesArray['comic_series']." (".$seriesArray['comic_start_year'].")";
echo $viewSeries->documentHead($title);
echo $viewSeries->menu();

echo "<div style='display:none;' id='library'></div>";

echo "<div id='series'>";

echo $viewSeries->getComicSeries($_GET['series']);

echo "</div>";

echo "<div style='display:none;' id='reader'></div>";