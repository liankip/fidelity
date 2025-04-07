<?php

namespace App\Helpers;

class Exchangerateapi
{
    public static function getbaseurl()
    {
        return "https://api.apilayer.com/exchangerates_data";
    }

    public static function getApiKey()
    {
        return env("API_KEY_EXCHANGE");
    }

    public static function getlatestdolartoidr($from, $to)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => self::getbaseurl() . "/latest?symbols=" . $to . "&base=" . $from,
            CURLOPT_HTTPHEADER => array(
                "apikey: " . self::getApiKey(),
                'Content-Type: application/json'
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
    public static function getallexchnagebyusd()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://exchangerate-api.p.rapidapi.com/rapid/latest/USD",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: exchangerate-api.p.rapidapi.com",
                "X-RapidAPI-Key: 58c4dec385msh1fe4234aa86a3d3p1c3887jsnaed2c6c385c6"
            ],
        ]);

        $response = curl_exec($curl);
        // $err = curl_error($curl);

        curl_close($curl);
        return json_decode($response);
    }
    // public static function getallexchnagebyusd()
    // {
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://v6.exchangerate-api.com/v6/eaf6a25a701c735868723f9d/latest/USD',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'GET',
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);

    //     return json_decode($response);

    // }
}
