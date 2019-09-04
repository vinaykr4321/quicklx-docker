<?php

//include_once 'config.php';
//
//if (isloggedin()) {
//    echo 'true';
//} else {
//    echo 'false';
//}

/* Updated code to handle all subdomains */
include_once 'config.php';

if (isloggedin()) {
    if (isset($_GET["company_id"])) {
        $company_id = $_GET["company_id"];
        $company_name = $_GET["company_name"];
        $_SESSION["company_id"] = $company_id;
        $_SESSION["company_name"] = $company_name;
    }
    echo 'true';
} else {
    if (isset($_GET["company_id"])) {
        $company_id = $_GET["company_id"];
        $company_name = $_GET["company_name"];
        $_SESSION["company_id"] = $company_id;
        $_SESSION["company_name"] = $company_name;
    }    
    if ($_SESSION["company_id"] == null && $_SESSION["company_name"] == null) {
        echo 'false';
    } else {
        $res = array($_SESSION["company_id"],$_SESSION["company_name"]);
        echo json_encode($res);
    }
}
