<?php

namespace App\Helpers\TermOfPayment;

use App\Models\PaymentMetode;
use DateInterval;
use DateTime;
use Illuminate\Support\Carbon;

class GenerateEstimate
{
    public static function GetEstimate7hari(DateTime $date)
    {
        $date->add(new DateInterval('P7D'));
        return $date->format('Y-m-d');
    }
    public static function GetEstimate30hari(DateTime $date)
    {
        $date->add(new DateInterval('P30D'));
        return $date->format('Y-m-d');
    }
    public static function GetEstimateCash($date)
    {
        return $date;
    }

    public static function GetEstimate60hari(DateTime $date)
    {
        $date->add(new DateInterval('P60D'));
        return $date->format('Y-m-d');
    }

    public static function GetEstimate($date, $term_of_payment)
    {
        $date = new DateTime($date);

        if (in_array($term_of_payment, PaymentMetode::D7)) {
            return self::GetEstimate7hari($date);
        } elseif (in_array($term_of_payment, PaymentMetode::D30)) {
            return self::GetEstimate30hari($date);
        } elseif (in_array($term_of_payment, PaymentMetode::D60)) {
            return self::GetEstimate60hari($date);
        }

        return null;
    }

    public static function isNotifDue($date, $term_of_payment)
    {
        $today = Carbon::now();
        // Tambahkan 5 hari ke tanggal hari ini
        $targetDate = Carbon::parse($date);
        $dayNotif = Carbon::now();

        if ($today->greaterThan($targetDate) || $date == null) {
            return false;
        }

        if (in_array($term_of_payment, PaymentMetode::D7)) {
            $dayNotif = $targetDate->subDays(3);
        } elseif (in_array($term_of_payment, PaymentMetode::D30)) {
            $dayNotif = $targetDate->subDays(5);
        } elseif (in_array($term_of_payment, PaymentMetode::D60)) {
            $dayNotif = $targetDate->subDays(7);
        }

        return $today->greaterThanOrEqualTo($dayNotif);
    }

    public static function isDueToday($date)
    {
        $today = Carbon::now();
        $targetDate = Carbon::parse($date);

        // Check if today is same as target date
        if ($today->isSameDay($targetDate)) {
            return true;
        }

        return false;
    }
}
