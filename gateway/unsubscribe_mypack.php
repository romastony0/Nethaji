<?php
include("./configuration.php");
session_start();
if ($_SESSION['country_code'] == "971") {
    $url = "http://52.77.82.47/sm_dipl/gateway/astro_sub.php";
    $post_data = array(
        'msisdn' => $_SESSION['mobile_no'],
        'keyword' => 'UNSUB AM',
        'mode' => 'wap',
        'validity' => '1'
    );
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($result, true);
    if ($response['returncode'] == '200') {
        $url = API_URL;
        $post_data = array(
            'user_id' => $_POST['user_id'],
            'purchase_id' => $_POST['purchase_id'],
            'action' => 'unsub_purchase',
            'oauth' => '7ff7c3ed4e791da7e48e1fbd67dd5b72',
            // 'mode' => 'wap',
            // 'validity' => '1'
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $response1 = json_decode($result, true);
        echo $response['returncode'];
    }
    else{
        echo '201';
    }
}
else if($_SESSION['country_code']=='91'){
    $url = API_URL;
    $post_data = array(
        'user_id' => $_POST['user_id'],
        'purchase_id' => $_POST['purchase_id'],
        'action' => 'unsub_purchase',
        'oauth' => '7ff7c3ed4e791da7e48e1fbd67dd5b72',
        // 'mode' => 'wap',
        // 'validity' => '1'
    );
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $response1 = json_decode($result, true);
    echo $response1['returncode'];
}
?>