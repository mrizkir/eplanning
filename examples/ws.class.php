<?php

$curl = curl_init();

$url="";
$client_id=''; //isi dengan client_id anda
$client_secret=''; //isi dengan client secret anda
$username=''; //isi dengan username
$password=''; //isi dengan password


curl_setopt_array($curl, array(
    CURLOPT_URL => "$url/oauth/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "grant_type=password&client_id=".$client_id ."&client_secret=".$client_secret ."&username=". $username . "&password=" . $password,
    CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "content-type: application/x-www-form-urlencoded",
    ),
));

$response = curl_exec($curl);

$responseInfo = curl_getinfo($curl);
curl_close($curl);

switch ($responseInfo['http_code'])
{
    case 200 :        
        $_SESSION['TOKEN'] = json_decode($response)->access_token;
        $_SESSION['TOKE_TYPE'] = json_decode($response)->token_type;
    break;
    case 400 :
        $data = json_decode($response,true);
        echo "<strong>Error:</strong> ". $data['error'] . ' <strong>message:</strong>'.$data['message'];
    break;
    default :
        echo "Koneksi gagal ";
}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "$url/api/v1/master/kelompokurusan",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET", 
    CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "authorization: " . $_SESSION['TOKE_TYPE'] . " " . $_SESSION['TOKEN'] . "",
        "cache-control: no-cache",
        "content-type: application/json",
    ),
));

$response = curl_exec($curl);

$responseInfo = curl_getinfo($curl);
curl_close($curl);

switch ($responseInfo['http_code'])
{
    case 200 :        
        $data = json_decode($response,true);
        print_R($data);
    break;
    case 400 :
        $data = json_decode($response,true);
        echo "<strong>Error:</strong> ". $data['error'] . ' <strong>message:</strong>'.$data['message'];
    break;
    default :
        echo "Koneksi gagal ";
}