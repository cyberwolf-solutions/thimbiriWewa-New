<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\MailConfig;
use App\Models\Settings;
use App\Traits\MailConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SettingsController extends Controller {
    use MailConfigTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $title = 'Settings';

        $breadcrumbs = [
            // ['label' => 'First Level', 'url' => '', 'active' => false],
            ['label' => $title, 'url' => '', 'active' => true],
        ];

        $data = Settings::first();
        $mail = MailConfig::first();

        return view('settings.index', compact('title', 'breadcrumbs', 'data', 'mail'));
    }

    public function updateSettings(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'site_currency' => 'required|string',
            'site_title' => 'required|string',
            'email' => 'required|email',
            'contact' => 'required|string',
            'logo_light' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust as needed for file validation
            'logo_dark' => 'image|mimes:jpeg,png,jpg,gif|max:2048',  // Adjust as needed for file validation
            'site_date_format' => ['required', Rule::in(['M j, Y', 'd-m-Y', 'm-d-Y', 'Y-m-d'])],
            'site_time_format' => ['required', Rule::in(['g:i A', 'g:i a', 'H:i'])],
            'invoice_prefix' => 'required|string',
            'bill_prefix' => 'required|string',
            'customer_prefix' => 'required|string',
            'supplier_prefix' => 'required|string',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            $data = [
                'currency' => $request->site_currency,
                'date_format' => $request->site_date_format,
                'time_format' => $request->site_time_format,
                'title' => $request->site_title,
                'email' => $request->email,
                'contact' => $request->contact,
                'invoice_prefix' => $request->invoice_prefix,
                'bill_prefix' => $request->bill_prefix,
                'customer_prefix' => $request->customer_prefix,
                'supplier_prefix' => $request->supplier_prefix,
                'updated_by' => Auth::user()->id
            ];

            // Handle file upload for logo_light
            if ($request->hasFile('logo_light')) {
                $logoLightPath = $request->file('logo_light')->store('logos', 'public');
                $data['logo_light'] = $logoLightPath;
            }

            // Handle file upload for logo_dark
            if ($request->hasFile('logo_dark')) {
                $logoDarkPath = $request->file('logo_dark')->store('logos', 'public');
                $data['logo_dark'] = $logoDarkPath;
            }

            Settings::first()->update($data);

            return json_encode(['success' => true, 'message' => 'System settings updated', 'url' => route('settings.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!' . $th]);
        }
    }
    public function updateMail(Request $request) {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ]);
        if ($validator->fails()) {
            $all_errors = null;

            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $all_errors .= $error . "<br>";
                }
            }

            return response()->json(['success' => false, 'message' => $all_errors]);
        }
        try {
            $data = [
                'driver' => $request->mail_driver,
                'host' => $request->mail_host,
                'port' => $request->mail_port,
                'username' => $request->mail_username,
                'password' => $request->mail_password,
                'encryption' => $request->mail_encryption,
                'from_address' => $request->mail_from_address,
                'from_name' => $request->mail_from_name,
                'updated_by' => Auth::user()->id
            ];

            Settings::first()->update($data);

            if ($request->sendTestMail == 'on') {
                $mail = MailConfig::create($data);

                $mailConfig = MailConfig::latest()->first();
                if ($mailConfig) {
                    $this->setMailConfig($mailConfig);
                    Mail::to($request->mail_from_address)->send(new TestMail());
                }
                return json_encode(['success' => true, 'message' => 'System settings updated. Test Mail has been sent to the inbox please check.', 'url' => route('settings.index')]);
            }
            return json_encode(['success' => true, 'message' => 'System settings updated', 'url' => route('settings.index')]);
        } catch (\Throwable $th) {
            //throw $th;
            return json_encode(['success' => false, 'message' => 'Something went wrong!']);
        }
    }
}
