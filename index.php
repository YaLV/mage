<?php
session_start();
require("vendor/autoload.php");

\App\Controller::init(new \App\Route(), new \App\Request());