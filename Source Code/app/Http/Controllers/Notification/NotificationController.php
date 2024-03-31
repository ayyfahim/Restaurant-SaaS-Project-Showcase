<?php

namespace App\Http\Controllers\Notification;

use App\Application;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Twilio;
class NotificationController extends Controller
{
    public function WhatsAppOrderNotification($order){
        $isEnable = Setting::all()->where('key','IsSandBoxEnabled')->first();
        $isEnableStore = Setting::all()->where('key','IsStoreEnabled')->first();
        $sid= Setting::all()->where('key','SandBoxID')->first()->value;
        $token = Setting::all()->where('key','SandBoxToken')->first()->value;
        $sanboxNumber = Setting::all()->where('key','PhoneCode')->first()->value;
//        return Store::find($order[0]['store_id'])->phone;
        if($isEnable->value == "1" && $isEnableStore->value == "1") {

            $store = Store::find($order[0]['store_id']);
//        return $order[0]['order_unique_id'];
                $body = "";

                $account_info = Application::all()->first();
                foreach ($order as $order_data) {

                    foreach ($order_data['order_details'] as $key => $data) {

                        $body .= $data['name'] . " - " . $data['price'] . " x " . $data['quantity'] . " = " . ($data['quantity'] * $data['price'] . "\n");
                        foreach ($data['order_details_extra_addon'] as $key => $exra) {
                            $body .= "--" . $exra['addon_name'] . " - " . $exra['addon_price'] . " x " . $exra['addon_count'] . " = " . ($exra['addon_count'] * $exra['addon_price'] . "\n");
                        }
                    }
                    $body .= "Table:{$order_data['table_no']}\n";
                    $body .= "Total:{$order_data['total']}";

                }
                $client = new Twilio\Rest\Client($sid, $token);
            try {

                $phone=  str_replace(' ','',$store->phone);

                $message = $client->messages->create(
                    "whatsapp:".$phone, // Text this number
                    [
                        'from' => "whatsapp:{$sanboxNumber}", // From a valid Twilio number
                        'body' => "New Order - {$order[0]['order_unique_id']} \n {$body}"
                    ]
                );
//            return $store;
            } catch (\Exception $e) {

            }

        }
    }
    public function send_notification($title,$body,$shop_id){

        $temp = DB::table('fcm_notifications')->where('store_id','=',$shop_id)->get();
        $data = array();
        $token = array();
        foreach ($temp as $key) {
            $data[] = $key;
            $token[] =  $key->token;
        }
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens


        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

    }
    public function FcmStoreNotification(){

        $shop_id = 1;
        $title = "Waiter Call";
        $body = "Table #3 calling the waiter";
        $this->send_notification($title,$body, $shop_id);
    }

}
