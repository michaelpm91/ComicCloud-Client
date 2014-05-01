<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 01/05/14
 * Time: 22:12
 */
require('../resources/include.php');

$home = new page();

echo $home->documentHead('Library');
echo $home->menu();

/*echo <<<'EOD'
    <div id='content'></div>
EOD;*/

echo "<div id='library'>";
echo "<div id='uploadMask'></div>";
//echo "<h1 style='margin-left:1%;'>Library</h1>";

/*echo "
    <div id='searchContainer'>
        <input placeholder='Search' id='search' type='search'></div>";*/

echo $home->getLibrary();
echo "</div>";
