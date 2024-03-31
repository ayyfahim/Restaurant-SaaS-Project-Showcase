<?php

namespace App\Http\Controllers;

use \Cache;
use App\Card;
use App\Payment;
use App\Application;
use App\Models\Order;
use App\Models\Store;
use App\Http\AdyenClient;
use App\Models\WaiterCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\API\DeliverectController;
use App\Models\Table;

class CheckoutController extends Controller
{
    protected $limonetikUrl;
    protected $limonetikApi;
    protected $limonetikMerchant;

    public function __construct()
    {
        if (config('app.env') == 'local') {
            $this->limonetikApi = config('app.limonetik_api_key_test');
            $this->limonetikUrl = 'https://api.limonetikqualif.com';
            $this->limonetikMerchant = config('app.limonetik_merchant_test');
        } else {
            $this->limonetikApi = config('app.limonetik_api_key_production');
            $this->limonetikUrl = 'https://api.limonetik.com';
            $this->limonetikMerchant = config('app.limonetik_merchant_production');
        }

        // $this->middleware('auth:customer')->except('paymentSuccessfull');
    }

    public function index()
    {
        $account_info = Application::all()->first();
        return view('Home.show_store', [
            'account_info' => $account_info,
        ]);
    }

    public function preview(Request $request)
    {
        $type = $request->type;
        return view('pages.preview')->with('type', $type);
    }

    public function checkout(Request $request)
    {
        $data = array(
            'type' => $request->type,
            'clientKey' => config('app.client_key')
        );

        return view('pages.payment')->with($data);
    }

    // Result pages
    public function result(Request $request)
    {
        $type = $request->type;
        return view('pages.result')->with('type', $type);
    }

    public function createPayment(array $data, $fromCart = false, $status = null)
    {
        try {
            $payment = Payment::create($data);

            foreach ($payment->order_ids as $order_id) {
                $order = Order::find($order_id);

                $orderTotal = $order->total;
                $orderPaid = $order->paid_amount;

                if ($orderPaid > $orderTotal) {
                    $payment->delete();

                    return response()->json([
                        'msg' => "Please select a smaller ammount to be paid.",
                        'orderTotal' => $orderTotal,
                        'orderPaid' => $orderPaid,
                    ], 422);
                }

                DB::beginTransaction();

                // $table = Table::findOrFail($order->table_no);

                // if ($table->unpaid_orders()->count() > 0) {
                //     $new_customers = $table->new_customers;
                //     $table->new_customers = [];
                //     $table->old_customers = $new_customers;
                //     $table->is_fetchable = 0;
                //     $table->save();
                // }

                if ($fromCart) {
                    $order->update([
                        'paid_amount' => $order->total,
                        'paid_at' => now(),
                        'status' => $status
                    ]);
                } else {
                    $order->update([
                        'paid_amount' => $order->total,
                        'paid_at' => now(),
                    ]);
                }

                if($status == 2){
                    try {
                        $deliverectController = new DeliverectController();
                        $order_status = $deliverectController->createDeliverectOrder($order);
                    } catch (\Throwable $e) {
                        dd($e);
                        // Log::error($e, ["store_id" => $store->id, "order_id" => $get_new_order->first()->id]);
                    }
                }


                $this->callWaiter($order);

                DB::commit();
            }


        } catch (\Throwable $th) {

            $payment->delete();

            DB::rollBack();

            return $th->getMessage();
        }

        return "Payment Created successfull.";
    }

    public function toFixed($number, $decimals)
    {
        return number_format($number, $decimals, '.', "");
    }

    public function callWaiter($order)
    {
        // $body = $order['table_no'] != null ? "Table #{$order['table_no']} {$order['customer_name']} ({$order['order_unique_id']}) has paid the full amount"
        //     : "#{$order['table_no']} {$order['customer_name']} ({$order['order_unique_id']}) has paid the full amount";
        $body = $order['table_no'] != null ? "Table #{$order['table_no']} has paid the full amount."
            : "Table #{$order['table_no']} has paid the full amount.";

        $title = "Waiter Call";

        $notification = new NotificationController();

        try {
            $notification->send_notification($title, $body, $order['store_id']);
        } catch (\Exception $e) {
        }
        $data['customer_name'] = $order['customer_name'];
        $data['customer_phone'] = $order['customer_phone'];
        $data['table_name'] = $order['table_no'];
        $data['comment'] = $body;
        $data['store_id'] = $order['store_id'];
        WaiterCall::create($data);
    }

    public function calculateSubTotal($orders)
    {
        $sum = 0;

        foreach ($orders as $order) {
            $sum = $sum + $order->total;
        }

        return $sum;
    }

    public function limonetikCreatePayment(Request $request)
    {
        $data = json_decode($request->getContent());

        $card = null;

        if ($data->card) {
            $card = Card::where([
                'customer_id' => $request->user()->id,
                'id' => $data->card
            ])->get()->first();
        }

        $hasTips = $data->tips != 0.00 && $data->tips != null;

        foreach ($data->orders as $order) {



            $originalOrder = Order::find($order->id);

            $orderTotal = $originalOrder->total;
            $orderPaid = $originalOrder->paid_amount;

            if ($orderPaid > $orderTotal) {

                // Refund

                return response()->json([
                    'msg' => "Please select a smaller ammount to be paid.",
                    "success" => false,
                    "status" => "error",
                    'orderTotal' => $orderTotal,
                    'orderPaid' => $orderPaid,
                ], 422);
            }

        }

        $array = [
            "PaymentOrder" => [
                "MerchantId" => $this->limonetikMerchant,
                "PaymentPageId" => "creditcard",
                'Amount' => $this->calculateSubTotal($data->orders) + $this->toFixed($data->tips, 2),
                'Currency' => 'EUR',
                'MerchantUrls' => [
                    'ReturnUrl' => route('paymentSuccessfull'),
                    'AbortedUrl' => 'http://www.citronrose.com/Payment_Cancelled.aspx',
                    'ErrorUrl' => 'http://www.citronrose.com/Payment_Error.aspx',
                    'ServerNotificationUrl' => 'http://www.citronrose.com/Payment_Notification.aspx',
                ],
                'MerchantOrder' => [
                    'Id' => time(),
                    'Customer' => [
                        'Email' => $request->user()->email ?? "testachat@limonetik.com",
                        "MobilePhone" => $request->user()->phone ?? "+12345678910",
                    ],
                    "SearchableCustom1" => $hasTips ? "Tips" : null,
                    "SearchableCustom2" => $hasTips ? $data->tips : null
                ],
            ]
        ];

        if ($card) {
            $array["Credentials"] = [
                [
                    "name" => "technicalTransactionId",
                    "credential" => $card->payment_order_id,
                ]
            ];
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->limonetikApi,
                'Content-Type' => 'application/json; charset=utf-8', // Post
                'Accept-Encoding' => 'gzip,deflate,sdch', // Get
                'Accept-Language' => 'en-US,en;q=0.8', // Get
                'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3', // Get
                'Accept' => 'application/json; charset=utf-8', // Get
            ])
                ->post(
                    $this->limonetikUrl . '/Rest/V40/PaymentOrder/Pay',
                    $array
                );

                $card->update([
                    'last_used_at' => now()
                ]);
        } else {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->limonetikApi,
                'Content-Type' => 'application/json; charset=utf-8', // Post
                'Accept-Encoding' => 'gzip,deflate,sdch', // Get
                'Accept-Language' => 'en-US,en;q=0.8', // Get
                'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3', // Get
                'Accept' => 'application/json; charset=utf-8', // Get
            ])
                ->post(
                    $this->limonetikUrl . '/Rest/V40/PaymentOrder/Create',
                    $array
                );
        }



        $body= json_decode($response->getBody());

        if ($response->getStatusCode() !== 200) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    'data' =>$body,
                ]
            ], 422);
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' =>$body,
            ]
        ], 200);
    }


    public function limonetikCreateOrderForCard(Request $request)
    {
        $array = [
            "PaymentOrder" => [
                "MerchantId" => $this->limonetikMerchant,
                "PaymentPageId" => "creditcard",
                'Amount' => 1.00,
                'Currency' => 'EUR',
                'MerchantUrls' => [
                    'ReturnUrl' => route('paymentSuccessfull'),
                    'AbortedUrl' => 'http://www.citronrose.com/Payment_Cancelled.aspx',
                    'ErrorUrl' => 'http://www.citronrose.com/Payment_Error.aspx',
                    'ServerNotificationUrl' => 'http://www.citronrose.com/Payment_Notification.aspx',
                ],
                'MerchantOrder' => [
                    'Id' => time(),
                    'Customer' => [
                        'Email' => $request->user()->email ?? "testachat@limonetik.com",
                        "MobilePhone" => $request->user()->phone ?? "+12345678910",
                    ]
                ],
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->limonetikApi,
            'Content-Type' => 'application/json; charset=utf-8', // Post
            'Accept-Encoding' => 'gzip,deflate,sdch', // Get
            'Accept-Language' => 'en-US,en;q=0.8', // Get
            'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3', // Get
            'Accept' => 'application/json; charset=utf-8', // Get
        ])
            ->post(
                $this->limonetikUrl . '/Rest/V40/PaymentOrder/Create',
                $array
            );

            $body= json_decode($response->getBody());

        if ($response->getStatusCode() !== 200) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    'data' =>$body,
                ]
            ], 422);
        }

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' =>$body,
            ]
        ], 200);
    }

    public function limonetikGetOrder(Request $request)
    {
        $data = json_decode($request->getContent());

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->limonetikApi,
            'Content-Type' => 'application/json; charset=utf-8', // Post
            'Accept-Encoding' => 'gzip,deflate,sdch', // Get
            'Accept-Language' => 'en-US,en;q=0.8', // Get
            'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3', // Get
            'Accept' => 'application/json; charset=utf-8', // Get
        ])
            ->get($this->limonetikUrl . '/Rest/V40/PaymentOrder/Detail?Id=' . $data->PaymentOrderId);

            $body= json_decode($response->getBody());

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $body,
            ]
        ], 200);
    }

    public function limonetikChargeOrder(Request $request)
    {
        $data = json_decode($request->getContent());

        $store = Store::findOrFail($data->orders[0]->store_id);

        $order_ids = [];

        // Check if the order is already paid

        foreach ($data->orders as $order) {
            $order_ids[] = $order->id;

            $newOrder = Order::find($order->id);
            $orderTotal = $newOrder->total;
            $orderPaid = $newOrder->paid_amount;

            if ($orderPaid > $orderTotal) {
                return response()->json([
                    'msg' => "Please select a smaller ammount to be paid.",
                    'orderTotal' => $orderTotal,
                    'orderPaid' => $orderPaid,
                ], 422);
            }
        }


        $array = [
            "PaymentOrderId" => $data->PaymentOrderId,
            "ChargeAmount" => $data->ChargeAmount,
            "Currency" => $data->Currency,
            "Fees" => [
                [
                    "Id" => "SandboxMarketplaceFees",
                    "Amount" => $store->getTotalFee($data->ChargeAmount)
                ]
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->limonetikApi,
            'Content-Type' => 'application/json; charset=utf-8', // Post
            'Accept-Encoding' => 'gzip,deflate,sdch', // Get
            'Accept-Language' => 'en-US,en;q=0.8', // Get
            'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3', // Get
            'Accept' => 'application/json; charset=utf-8', // Get
        ])
            ->post(
                $this->limonetikUrl . '/Rest/V40/PaymentOrder/Charge',
                $array
            );

        $body= json_decode($response->getBody());

        if ($response->getStatusCode() !== 200) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    'data' => $body,
                ]
            ], 422);
        }

        $createPaymentData = [
            'limonetik_order_id' => $data->PaymentOrderId,
            'order_ids' => $order_ids,
            'customer_id' => $data->customer_id,
            'amount' => $data->amount,
            'currency' => $data->currency,
            'status' => "Charged",
            'marketplace_fees' => $store->getTotalFee($data->ChargeAmount)
        ];

        $fromCart = $data->fromCart ?? false;
        $status = $store->auto_accept_order ? 2 : 1;

        if ($store->pay_first) {
            $status = 2;
        }

        $createPayment = $this->createPayment($createPaymentData, $fromCart, $status);

        return response()->json([
            "success" => true,
            "status" => "success",
            "message" => $createPayment,
            "payload" => [
                'data' => $body,
            ]
        ], 200);
    }

    public function limonetikChargeOrderForCard(Request $request)
    {
        $data = json_decode($request->getContent());


        $array = [
            "PaymentOrderId" => $data->PaymentOrderId,
            "ChargeAmount" => $data->ChargeAmount,
            "Currency" => $data->Currency,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->limonetikApi,
            'Content-Type' => 'application/json; charset=utf-8', // Post
            'Accept-Encoding' => 'gzip,deflate,sdch', // Get
            'Accept-Language' => 'en-US,en;q=0.8', // Get
            'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3', // Get
            'Accept' => 'application/json; charset=utf-8', // Get
        ])
            ->post(
                $this->limonetikUrl . '/Rest/V40/PaymentOrder/Charge',
                $array
            );

        $body= json_decode($response->getBody());

        if ($response->getStatusCode() !== 200) {
            return response()->json([
                "success" => false,
                "status" => "error",
                "payload" => [
                    'data' => $body,
                ]
            ], 422);
        }

        $createCardData = [
            'payment_order_id' => $data->PaymentOrderId,
            'card_name' => $data->cardName ?? "#" . ($request->user()->cards->count() + 1) . " Card",
            'card_number' => $data->card_number ?? null,
            'customer_id' => $request->user()->id,
        ];

        Card::create($createCardData);

        return response()->json([
            "success" => true,
            "status" => "success",
            "payload" => [
                'data' => $body,
            ]
        ], 200);
    }

    public function paymentSuccessfull(Request $request)
    {
        return view('payment.successfull');
    }
}
