<?php

namespace App\Helpers;


class GetEmails
{
    public static function get()
    {
        if (env("APP_DEBUG")) {
            $penerimaemail = [
                "pilput31@gmail.com"
            ];
        } else {
            $penerimaemail = [
                "antony@satrianusa.group",
                // "tamuji.tan@satrianusa.group",
                // "Feli@satrianusa.group",
                "anton.suherman@satrianusa.group",
                // "sarim4614@gmail.com",
                "egitatam@gmail.com",
                "admin@satrianusa,group",
                // "retno.ayu@satrianusa.group"
            ];
        }
        return $penerimaemail;
    }
}
