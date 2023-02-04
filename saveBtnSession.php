<?php

session_start();

if(isset($_POST["btns"])){
    $_SESSION["btns"] = $_POST["btns"];
}