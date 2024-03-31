<?php

namespace App\Http\Controllers\StoreAdmin;

use App\BankDetail;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\DeliverectController;
use Image;
use Str;

class AccountSettings extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth:store');
    }

    public function update_store_settings(Request $request)
    {
        $deliverectController = new DeliverectController();
        $data = request()->validate([
            'store_name' => 'required',
            'email' => '',
            'password' => '',
            'phone' => 'required',
            'logo_url' => '',
            'address' => '',
            'currency_symbol' => '',
            'service_charge' => '',
            'order_limit' => '',
            'tax' => '',
            'description' => '',

        ]);
        $data['is_accept_order'] = isset($request['is_accept_order']) ? 1 : 0;
        $data['auto_accept_order'] = isset($request['auto_accept_order']) ? 1 : 0;
        if(Auth::user()->can('update_takeaway')){
            $data['is_takeway_order'] = isset($request['is_takeway_order']) ? 1 : 0;
            $data['pay_first'] = isset($request['pay_first']) ? 1 : 0;
        }

        if (!empty($request->logo_url)) {
            $store_id = auth()->user()->id;
            $image = $request->logo_url;
            $image_normal = Image::make($image)->fit(1920, 1080);
            $resource = $image_normal->stream();
            $extension = $image->extension();
            $imageName = Str::random(20) . '.' . $extension;
            Storage::disk('s3')->put("/logo/$store_id/$imageName", $resource, 'public');
            $data['logo_url'] =  "logo/$store_id/" . $imageName;
        }

        if (!empty($request->logo_url_wide)) {
            $store_id = auth()->user()->id;
            $image = $request->logo_url_wide;
            $image_normal = Image::make($image)->fit(600, 250);
            $resource = $image_normal->stream();
            $extension = $image->extension();
            $imageName = Str::random(20) . '.' . $extension;
            Storage::disk('s3')->put("/logo/$store_id/$imageName", $resource, 'public');
            $data['logo_url_wide'] =  "logo/$store_id/" . $imageName;
        }

        if ($request->password == NULL)
            unset($data['password']);
        else
            $data['password'] = Hash::make($request['password']);

        // $bankDetail = BankDetail::updateOrCreate($bankDetails);

        if ($update_store = Store::whereId(auth()->id())->update($data)) {
            $store_status = $data['is_accept_order'] ? "open" : "closed";
            $deliverectController->updateUpdateChannelStatus(auth()->id(),[
                "status" => $store_status,
                "reason" => "Channel is $store_status"
            ]);
            // $bankDetails = request()->validate([
            //     'name_of_bank' => '',
            //     'iban' => 'required_with:name_of_bank',
            //     'bic' => 'required_with:iban',
            //     'account_holder_name' => 'required_with:account_holder_name',
            // ]);

            // if ($bankDetails['name_of_bank']) {
            //     Auth::user()->bank_details()->create($bankDetails);
            // }

            return back()->with(Toastr::success('Settings Updated Successfully.', 'Success'));
        }
    }

    public function update_bank_details(Request $request)
    {
        $bankDetails = request()->validate([
            'name_of_bank' => '',
            'iban' => 'required_with:name_of_bank',
            'bic' => 'required_with:iban',
            'account_holder_name' => 'required_with:account_holder_name',
        ]);

        Auth::user()->bank_details()->create($bankDetails);

        return back()->with(Toastr::success('Settings Updated Successfully.', 'Success'));
    }

    public function update_deliverect_details(Request $request)
    {
        $data = request()->validate([
            'deliverect_api_key' => 'required',
            'deliverect_api_secret_key' => 'required',
            'deliverect_webhook_url' => 'required',
            'deliverect_channel_link_id' => 'required',
        ]);

        Auth::user()->update($data);

        return back()->with(Toastr::success('Settings Updated Successfully.', 'Success'));
    }

    public function update_store_location(Request $request)
    {
        $data = request()->validate([
            'address' => 'required',
            'google_map_address' => 'required',
            'address_latitude' => 'required',
            'address_longitude' => 'required',
        ]);
        Auth::user()->update($data);

        return back()->with(Toastr::success('Settings Updated Successfully.', 'Success'));
    }

    public function update_open_hours(Request $request)
    {
        $request->validate([
            'monday_start_time' => 'required',
            'monday_end_time' => 'required',
            'tuesday_start_time' => 'required',
            'tuesday_end_time' => 'required',
            'wednesday_start_time' => 'required',
            'wednesday_end_time' => 'required',
            'thursday_start_time' => 'required',
            'thursday_end_time' => 'required',
            'friday_start_time' => 'required',
            'friday_end_time' => 'required',
            'saturday_start_time' => 'required',
            'saturday_end_time' => 'required',
            'sunday_start_time' => 'required',
            'sunday_end_time' => 'required',
        ]);

        $data = [
            'monday' => [
                'start_time' => $request->monday_start_time,
                'end_time' => $request->monday_end_time,
            ],
            'tuesday' => [
                'start_time' => $request->tuesday_start_time,
                'end_time' => $request->tuesday_end_time,
            ],
            'wednesday' => [
                'start_time' => $request->wednesday_start_time,
                'end_time' => $request->wednesday_end_time,
            ],
            'thursday' => [
                'start_time' => $request->thursday_start_time,
                'end_time' => $request->thursday_end_time,
            ],
            'friday' => [
                'start_time' => $request->friday_start_time,
                'end_time' => $request->friday_end_time,
            ],
            'saturday' => [
                'start_time' => $request->saturday_start_time,
                'end_time' => $request->saturday_end_time,
            ],
            'sunday' => [
                'start_time' => $request->sunday_start_time,
                'end_time' => $request->sunday_end_time,
            ],
        ];

        $open_hours = Auth::user()->open_hours;

        if (!$open_hours) {
            Auth::user()->open_hours()->create([
                'data' => $data
            ]);
        } else {
            $open_hours->data = $data;
            $open_hours->save();
        }

        return back()->with(Toastr::success('Settings Updated Successfully.', 'Success'));
    }
}
