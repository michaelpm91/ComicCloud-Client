<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 10/05/14
 * Time: 00:35
 */

//Add to body to disable right click //oncontextmenu='return false;'
class login {

    public function login(){
        $header = "
                <!DOCTYPE html>
                    <head>
                        <title>Comic Cloud - Login</title>
                        <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'/>
                        <link href='css/login.css' rel='stylesheet' type='text/css'/>
                        <script src='http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js'></script>
                        <script src='scripts/login.js'></script>
                        <meta name='viewport' content='initial-scale=1, maximum-scale=1, minimal-ui'>
                    </head>
                    <body>
                        <div id='loginForm'>
                            <input type='email' id='emailAddress' placeholder='Email Address'>
                            <input type='password' id='password' placeholder='Password'>
                            <input type='submit' id='login' value='Log in'>
                        </div>
                        <div id='loginFooter'></div>
                    </body>
                ";
        return $header;
    }

}