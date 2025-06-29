<?php
session_start(); if(!isset($_SESSION['user_id'])){header('Location: login.php');exit;}
require_once 'config.php'; $title='Dashboard'; include 'header.php';
if($_SESSION['user_type']==='INSTRUCTOR'){include 'instructor_dashboard.php';}
elseif($_SESSION['user_type']==='TO'){include 'to_dashboard.php';}
else{echo '<p>Unknown role.</p>';}
include 'footer.php';?>