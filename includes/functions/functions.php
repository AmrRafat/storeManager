<?php

/*
 ** Title function V1.0
 */
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Default';
    }
}

/*
 ** Redirect Function V1.0
 ** Parameters:
 ** $errorMsg   : Msg to be shown
 ** $seconds    : Seconds before redirecting
 **************************
 ** Redirect Function V1.1
 ** $theMsg     : $errorMsg but more general
 ** $seconds    : Seconds before redirecting
 ** $url        : link to be redirected to
 **************************
 ** TODO:
 ** with url if you typed: 'main' you go to the main page of the section
[example: edit item => go to items page]
main => main page
home => dashboard
index => index
 */
function redirectHome($theMsg, $seconds = 3, $url = null)
{
    if ($url === null) {
        $url = 'index.php';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = 'index.php';
        }
    }
    echo $theMsg;
    echo '<div class="alert alert-info">You will be redirected in ' . $seconds . ' seconds</div>';
    header("refresh:$seconds url=$url");
    exit();
}

/*
 ** Check all items
 */

function checkItems()
{
    global $con;
    $stmt = $con->prepare("SELECT SUM(amount) AS totalAmount,SUM(amount_sold) AS totalSold, least_amount FROM items GROUP BY item_code");
    $stmt->execute();
    $items = $stmt->fetchAll();
    foreach ($items as $item) {
        $remaining = $item['totalAmount'] - $item['totalSold'];
        $least = $item['least_amount'];
        $remaining = floatval($remaining);
        $least = floatval($least);
        if ($least == 0) {
            continue;
        } elseif ($remaining <= $least) {
            return 1;
            break;
        }
    }
}
