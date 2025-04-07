<?php

namespace App\Helpers;

class Whatsapp
{
    public static function getApiKey()
    {
        return env("API_KEY_WA");
    }
    public static function getSender()
    {
        return env("PHONE_SENDER");
    }

    public static function SendMessage($to, $message)
    {
        $curl = curl_init();
        $data = [
            'sessions' => "purchasing",
            'target' => $to,
            'message' => $message
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "localhost:8080/api/sendtext",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function rfqMessage($supplier, $rfq, $link)
    {
        $itemList = "";

        foreach ($rfq->itemDetail as $index => $item) {
            $num = $index + 1;
            $itemName = $item->item->name;
            $itemQty = $item->qty;
            $itemUnit = $item->unit;

            $itemList .= "
                $num. *$itemName* - $itemQty $itemUnit
            ";
        }


        $companyName = config('app.company_full_name');
        $text = "RFQ
            *$supplier->name*

            Dengan ini kami memohon penawaran harga untuk item sebagai berikut:
            $itemList
            Mohon agar dapat mengirimkan penawaran dengan harga terbaik pada link dibawah ini :

            $link

            sebelum :  *$rfq->expired_at*

            Hormat kami,
            *$companyName*
            www.satrianusa.group

            *_pesan ini dibuat secara otomatis dalam sistem procurement PT. Satria Nusa Enjinering, untuk pertanyaan dan verifikasi dapat menghubungi: 081534617975 / purchasing@satrianusa.group_";

        $text = preg_replace('/^\\h+/m', '', $text);

        return $text;
    }


    public static function POApprovedMessage($requester, $po)
    {
        $itemList = "";

        foreach ($po->podetail as $index => $item) {
            $num = $index + 1;
            $itemName = $item->item->name;
            $itemQty = (int)$item->qty;
            $itemUnit = $item->unit;

            $itemList .= "
                $num. *$itemName* - $itemQty $itemUnit
            ";
        }

        $approver = $po->approvedby?->name;
        $text = "Halo *$requester*,

            Permintaan barang anda dengan nomor *$po->pr_no* telah disetujui oleh *$approver*.
            Berikut adalah detail barang yang telah disetujui:
            $itemList

            *_pesan ini dibuat secara otomatis_";

        $text = preg_replace('/^\\h+/m', '', $text);

        return $text;
    }

    public static function convertPhoneNumber($phoneNumber)
    {
        if (str_starts_with($phoneNumber, '08')) {
            $phoneNumber = '628' . substr($phoneNumber, 2);
        } elseif (str_starts_with($phoneNumber, '8')) {
            $phoneNumber = '628' . substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }
}
