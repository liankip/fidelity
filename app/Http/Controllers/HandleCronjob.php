<?php

namespace App\Http\Controllers;

use App\Constants\PurchaseOrderStatus;
use App\Helpers\Exchangerateapi;
use App\Helpers\GetEmails;
use App\Helpers\Whatsapp;
use App\Mail\InvoiceUploaded;
use App\Mail\NearToPEstimate;
use App\Mail\NeedToPay;
use App\Mail\PaymentUpload;
use App\Mail\SendEmail;
use App\Mail\UploadedBarang;
use App\Mail\UploadedDo;
use App\Models\EmailSend;
use App\Models\Exchangerate;
use App\Models\NotificationTop;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\WaMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HandleCronjob extends Controller
{
    public function aprovealert(Request $request)
    {
        $request->header("filter");
        if ($request->header("filter") == "codebuatfiltercurlasing") {
        } else {
            return redirect('https://sne.bima.ai/');
        }
        $count = count(PurchaseOrder::where("status", "Wait For Approval")->get());
        $countnop = count(PurchaseOrder::where('status', PurchaseOrderStatus::NEED_TO_PAY)->get());
        $countwop = count(PurchaseOrder::where('status', "Waiting For Payment")->get());


        if ($count || $countnop || $countwop) {
            $messagewta = "";

            $messagenop = "";

            $messagewop = "";


            if ($count) {
                $messagewta = "(" . $count . " waiting to approve -> link: https://sne.bima.ai/aprv_waitinglists)\n";
            }
            if ($countnop) {
                $messagenop = "(" . $countnop . " need to Pay -> link: https://sne.bima.ai/payment_list)\n";
            }
            if ($countwop) {
                $messagewop = "(" . $countwop . " waiting for payment -> link: https://sne.bima.ai/payment_list_noncash or https://sne.bima.ai/payment_list_cash)\n";
            }

            $manager = User::where("type", 5)->orWhere("type", 2)->orWhere("type", 4)->get();
            foreach ($manager as $key => $value) {

                if ($value->type == "it" || $value->type == "manager") {
                    $data = [
                        'api_key' => env("API_KEY_WA"),
                        'sender' => env("PHONE_SENDER"),
                        'number' => $value->phone_number,
                        'message' => "You have \n" . $messagewta . $messagenop
                    ];
                } elseif ($value->type == "finance") {
                    $data = [
                        'api_key' => env("API_KEY_WA"),
                        'sender' => env("PHONE_SENDER"),
                        'number' => $value->phone_number,
                        'message' => "You have \n" . $messagewop . $messagenop
                    ];
                }
                // dd($data);
                // $data = [
                //     'api_key' => env("API_KEY_WA"),
                //     'sender' => env("PHONE_SENDER"),
                //     'number' => $value->phone_number,
                //     'message' => "You have \n" . $messagewta . $messagenop . $messagewop
                // ];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://waapi.lihat.webcam/send-message",
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
            }
        }
    }

    public function everyspecifictime(Request $request)
    {
        $request->header("filter");
        if ($request->header("filter") == "codebuatfiltercurlasing") {
        } else {
            return redirect()->to("/");
        }

        //check estimation h-3

        //get data included h-3
        $ntophsub3 = NotificationTop::where("paid_off_date", null)->where("est_pay_date", "!=", null)->whereRaw("DATE_ADD(est_pay_date, INTERVAL -3 DAY) <= NOW()")->get();

        if (count($ntophsub3)) {
            $arraypoid = [];
            foreach ($ntophsub3 as $notif) {
                array_push($arraypoid, $notif->purchase_order_id);
            }

            //add to queue database notif wa
            $posub3 =  PurchaseOrder::whereIn("id", $arraypoid)->get();
            foreach ($posub3 as $key => $powillntp) {
                if ($powillntp->status == "Approved") {
                    PurchaseOrder::where("id", $powillntp->id)->update([
                        "status" => "Need to Pay"
                    ]);
                }
            }

            $wamsg = "*Ada purchase order yang sudah dekat pembayaran*\nMohon untuk segera melakukan pembayaran\n\n";

            foreach ($posub3 as $key => $po) {
                $temp = ($key + 1) . ". " . $po->po_no . "upload bukti bayar " . url("/upload-payment/" . $po->id) . "\n";
                // dd($temp);
                $wamsg .= $temp;
            }
            $wamsg .= "\n\n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";

            // dd($wamsg);
            $recervedwa = User::whereIn("type", [2, 4, 5])->get();

            foreach ($recervedwa as $key => $user) {
                if ($user->phone_number) {
                    //dimatikan sementara
                    // WaMessage::create([
                    //     "number" => $user->phone_number,
                    //     "message" => $wamsg
                    // ]);
                }
            }

            foreach (GetEmails::get() as $value) {
                foreach ($posub3 as $key => $po2etop) {
                    EmailSend::create([
                        "email" => $value,
                        "type" => "near_est_top",
                        "po_id" => $po2etop->id,
                        "created_by" => 6
                    ]);
                }
            }
        }

        // $countalltop2 = 0;
        // $datapotonotif = [];
        // if (count($ntop2)) {
        //     foreach ($ntop2 as $value) {
        //         $finaldate = date_format(date_create($value->est_pay_date), "Y/m/d");
        //         $finaldate2 = date_create($finaldate);
        //         $datenow = date("Y/m/d");
        //         $dateh = date_sub($finaldate2, date_interval_create_from_date_string("3 days"));
        //         $dateh3 = date_format($dateh, "Y/m/d");

        //         if ($dateh3 <= $datenow) {
        //             $countalltop2 += 1;
        //             array_push($datapotonotif, ["po_id" => $value->id]);
        //         }
        //     }
        // }

        //estimation tiba sudah lewat barang belum sampe
        // $ariveitem = PurchaseRequestDetail::where("estimation_date", "!=", null)->has("podetail")->get();
        // $countestimasiarrvive = 0;
        // if (count($ariveitem)) {
        //     foreach ($ariveitem as $key => $sad) {
        //         if ($sad->podetail->po->status != "Completed") {
        //             $finaldate = date_format(date_create($sad->estimation_date), "Y/m/d");
        //             $datenow = date("Y/m/d");
        //             $dateh3 = date_format($dateh, "Y/m/d");

        //             if ($finaldate < $datenow) {
        //                 $countestimasiarrvive += 1;
        //             }
        //         }
        //     }
        // }

        // dd($countalltop2);
        // dd($countestimasiarrvive);
        // if ($countalltop2 || $countestimasiarrvive) {
        //     $messageh3 = "";
        //     $messageariveout = "";

        //     if ($countalltop2) {
        //         $messageh3 = "(" . $countalltop2 . " date of top is near)\n";
        //     }
        //     if ($countestimasiarrvive) {
        //         $messageariveout = "(" . $countestimasiarrvive . " Estimated arrived but the goods have not arrived)\n";
        //     }
        //     $manager = User::where("type", 5)->orWhere("type", 2)->orWhere("type", 3)->orWhere("type", 4)->get();

        //     foreach ($manager as $key => $val) {

        //         if ($val->type == "it" || $val->type == "manager" || $val->type == "purchasing") {
        //             $data = [
        //                 'api_key' => env("API_KEY_WA"),
        //                 'sender' => env("PHONE_SENDER"),
        //                 'number' => $val->phone_number,
        //                 'message' => "You have \n" . $messageariveout
        //             ];
        //         } elseif ($val->type == "finance") {
        //             $data = [
        //                 'api_key' => env("API_KEY_WA"),
        //                 'sender' => env("PHONE_SENDER"),
        //                 'number' => $val->phone_number,
        //                 'message' => "You have \n" . $messageh3
        //             ];
        //         }

        //         $curl = curl_init();

        //         curl_setopt_array($curl, array(
        //             CURLOPT_URL => "https://waapi.lihat.webcam/send-message",
        //             CURLOPT_RETURNTRANSFER => true,
        //             CURLOPT_ENCODING => '',
        //             CURLOPT_MAXREDIRS => 10,
        //             CURLOPT_TIMEOUT => 0,
        //             CURLOPT_FOLLOWLOCATION => true,
        //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //             CURLOPT_CUSTOMREQUEST => 'POST',
        //             CURLOPT_POSTFIELDS => json_encode($data),
        //             CURLOPT_HTTPHEADER => array(
        //                 'Content-Type: application/json'
        //             ),
        //         ));

        //         $response = curl_exec($curl);

        //         curl_close($curl);
        //     }
        // }
    }
    public function sendWhatsapp(Request $request)
    {
        $request->header("filter");

        if ($request->header("filter") == "codebuatfiltercurlasing") {
        } else {
            return redirect()->to("/");
        }
        $notif = WaMessage::where("status", 0)->take(10)->get();
        if (count($notif)) {

            foreach ($notif as $key => $value) {

                Whatsapp::SendMessage($value->number, $value->message);
                WaMessage::where("id", $value->id)->update([
                    "status" => 1
                ]);
            }
        } else {
            return;
        }
    }

    public function sendEmail(Request $request)
    {
        $request->header("filter");

        if ($request->header("filter") == "codebuatfiltercurlasing") {
        } else {
            return redirect()->to("/");
        }

        $notif = EmailSend::where("status", 0)->take(10)->get();

        if (count($notif)) {

            //approved email
            $approved = $notif->whereIn('type', ["Approved", null]);
            foreach ($approved as $app) {
                try {

                    Mail::to($app->email)->send(new SendEmail($app->po, $app->createdby));
                    EmailSend::where("id", $app->id)->update([
                        "status" => 1
                    ]);
                } catch (\Throwable $th) {
                    EmailSend::where("id", $app->id)->update([
                        "status" => 2
                    ]);
                    //throw $th;
                }
            }

            //top is near
            $topnears = $notif->whereIn('type', ["near_est_top", null]);
            foreach ($topnears as $topnear) {
                try {
                    Mail::to($topnear->email)->send(new NearToPEstimate($topnear->po, $topnear->createdby));
                    EmailSend::where("id", $topnear->id)->update([
                        "status" => 1
                    ]);
                } catch (\Throwable $th) {
                    EmailSend::where("id", $topnear->id)->update([
                        "status" => 2
                    ]);
                }
            }
            //foto invoice di upload
            $invoiceuploaded = $notif->where("type", "InvoiceUploaded");
            foreach ($invoiceuploaded as $invoiceup) {
                try {
                    Mail::to($invoiceup->email)->send(new InvoiceUploaded($invoiceup->po, $invoiceup->createdby, $invoiceup));
                    EmailSend::where("id", $invoiceup->id)->update([
                        "status" => 1
                    ]);
                } catch (\Throwable $th) {
                    EmailSend::where("id", $invoiceup->id)->update([
                        "status" => 2
                    ]);
                }
            }

            $paymentuploaded = $notif->where("type", "PaymentUploaded");

            foreach ($paymentuploaded as $paymentup) {
                try {
                    Mail::to($paymentup->email)->send(new PaymentUpload($paymentup->po, $paymentup->createdby, $paymentup));
                    EmailSend::where("id", $paymentup->id)->update([
                        "status" => 1
                    ]);
                } catch (\Throwable $th) {
                    EmailSend::where("id", $paymentup->id)->update([
                        "status" => 2
                    ]);
                }
            }

            $NewNeedToPay = $notif->where("type", "NeedToPay");

            foreach ($NewNeedToPay as $needTopay) {
                try {
                    Mail::to($needTopay->email)->send(new NeedToPay($needTopay->po, $needTopay->createdby, $needTopay));
                    EmailSend::where("id", $needTopay->id)->update([
                        "status" => 1
                    ]);
                } catch (\Throwable $th) {
                    EmailSend::where("id", $needTopay->id)->update([
                        "status" => 2
                    ]);
                }
            }

            //foto barang di upload
            $itemuploaded = $notif->where("type", "ItemUploaded");
            foreach ($itemuploaded as $iu) {
                try {
                    Mail::to($iu->email)->send(new UploadedBarang($iu->po, $iu->createdby, $iu));
                    EmailSend::where("id", $iu->id)->update([
                        "status" => 1
                    ]);
                } catch (\Throwable $th) {
                    EmailSend::where("id", $iu->id)->update([
                        "status" => 2
                    ]);
                }
            }

            //foto do di upload
            $douploaded = $notif->where("type", "DOUploaded");
            foreach ($douploaded as $do) {
                try {
                    Mail::to($do->email)->send(new UploadedDo($do->po, $do->createdby, $do));
                    EmailSend::where("id", $do->id)->update([
                        "status" => 1
                    ]);
                } catch (\Throwable $th) {
                    EmailSend::where("id", $do->id)->update([
                        "status" => 2
                    ]);
                }
            }

            return "{'status':'berhasil'}";
        } else {
            return "{'status':'no data'}";
        }
    }
    public function getexchangerate(Request $request)
    {
        $request->header("filter");

        if ($request->header("filter") == "codebuatfiltercurlasing") {
        } else {
            return redirect('https://sne.bima.ai/');
        }

        // $exchnage = Exchangerateapi::getlatestdolartoidr("USD", "idr%2Cjpy%2Cmyr%2Csgd");
        $exchnage = Exchangerateapi::getallexchnagebyusd();
        // dd($exchnage);
        $rates = collect($exchnage->conversion_rates);
        // dd($exchnage);
        // dd($rates);
        foreach ($rates as $key => $value) {
            $check = Exchangerate::where("base", $exchnage->base_code)->where("convert", $key)->first();
            if ($check) {
                Exchangerate::where("base", $exchnage->base_code)->where("convert", $key)->update([
                    "converted_value" => $value
                ]);
            } else {
                Exchangerate::create([
                    "base" => $exchnage->base_code,
                    "convert" => $key,
                    "base_value" => 1,
                    "converted_value" => $value
                ]);
            }
        }
        return "berhasil" . date("D, d M Y H:i:s");

        // contoh return
        // {
        //     "success": true
        //     "timestamp": 1671593283
        //     "base": "USD"
        //     "date": "2022-12-21"
        //     "rates": {
        //         "IDR": 15619.05
        //     }
        // }
    }
}
