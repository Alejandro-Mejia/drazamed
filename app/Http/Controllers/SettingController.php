<?php
    namespace app\Http\Controllers;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Support\Facades\URL;
    use Request;
    use Response;
    use App\Setting;
    use App\PaymentGatewaySetting;
    use App\Admin;
    use App\AdminType;
    use App\User;
    use App\UserType;
    use App\UserStatus;
    use \Cache;
    use Hash;


    define('SYSTEM_SETTINGS_IMAGE', base_path() . '/public/assets/images/setting/');
    define('SYSTEM_IMAGE_URL', URL::to('/') . '/assets/images/setting/');
    define("BASE_PATH", dirname(__FILE__));

    // $imageLogoFolder = public_path("/assets/images/setting/");


    class SettingController extends BaseController
    {

        /**
         * Add Basic Settings
         */
        public function postBasic()
        {
            try {
                // if (!$this->isCsrfAccepted())
                //     throw new Exception('Invalid request parameters', 401);
                $app_name = Request::get('name', '');
                $email = Request::get('email', '');
                $location = Request::get('location', '');
                $website = Request::get('website', '');
                $phone = Request::get('phone', '');
                $timezone = Request::get('timezone', 'UTC');
                $currency = Request::get('currency', '');
                $curreny_position = Request::get('curreny_position', 'BEFORE');
                $discount = Request::get('discount', '0');
                $image = "";
                if (Request::hasFile('file')) {
                    $file = Request::file('file', '');
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (in_array($extension, ['png', 'jpg'])) {
                        $image = 'logo.' . $extension;
                        $file->move( base_path() . '/public/assets/images/setting/' , $image);
                    } else {
                        throw new Exception('Invalid File Uploaded ! Please upload either png or jpg file', 400);
                    }
                }
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'mail'], 'value' => $email];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'address'], 'value' => $location];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'app_name'], 'value' => $app_name];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'website'], 'value' => $website];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'phone'], 'value' => $phone];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'timezone'], 'value' => $timezone];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'currency'], 'value' => $currency];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'curr_position'], 'value' => $curreny_position];
                $conditions[] = ['column' => ['group' => 'site', 'key' => 'discount'], 'value' => $discount];
                if (!empty($image)) {
                    $conditions[] = ['column' => ['group' => 'site', 'key' => 'logo'], 'value' => $image];
                }
                foreach ($conditions as $condition) {
                    $this->updateSetting($condition);
                }
                Cache::forget('CACHE_PARAM_SETTINGS');
                $email = Setting::param('mail', 'username');
                $mail_password = Setting::param('mail', 'password');
                $mail_address = Setting::param('mail', 'address');
                $mail_name = Setting::param('mail', 'name');
                $port = Setting::param('mail', 'port');
                $host = Setting::param('mail', 'host');
                $driver = Setting::param('mail', 'driver');

                return Response::json(['status' => 'SUCCESS', 'code' => 200, 'data' => ['email' => $email, 'mail_password' => $mail_password, 'mail_address' => $mail_address, 'mail_name' => $mail_name, 'port' => $port, 'host' => $host, 'driver' => $driver,]]);
            } catch (Exception $e) {
                return Response::json(['status' => 'FAILURE', 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            }

        }

        /**
         * Add Basic Settings
         */
        public function postMail()
        {
            try {
                // if (!$this->isCsrfAccepted())
                //     throw new Exception('Invalid request parameters', 401);
                $email = Request::get('mail_id', '');
                $mail_password = Request::get('mail_password', '');
                $mail_address = Request::get('from_address', '');
                $mail_name = Request::get('from_name', '');
                $port = Request::get('port', '');
                $host = Request::get('host', '');
                $driver = Request::get('driver', '');
                if (!empty($email))
                    $conditions[] = ['column' => ['group' => 'mail', 'key' => 'username'], 'value' => $email];
                if (!empty($mail_password))
                    $conditions[] = ['column' => ['group' => 'mail', 'key' => 'password'], 'value' => $mail_password];
                if (!empty($mail_address))
                    $conditions[] = ['column' => ['group' => 'mail', 'key' => 'address'], 'value' => $mail_address];
                if (!empty($mail_name))
                    $conditions[] = ['column' => ['group' => 'mail', 'key' => 'name'], 'value' => $mail_name];
                if (!empty($port))
                    $conditions[] = ['column' => ['group' => 'mail', 'key' => 'port'], 'value' => $port];
                if (!empty($host))
                    $conditions[] = ['column' => ['group' => 'mail', 'key' => 'host'], 'value' => $host];
                if (!empty($driver))
                    $conditions[] = ['column' => ['group' => 'mail', 'key' => 'driver'], 'value' => $driver];
                if (!empty($conditions)) {
                    foreach ($conditions as $condition) {
                        $this->updateSetting($condition);
                    }
                }
                Cache::forget('CACHE_PARAM_SETTINGS');

                return Response::json(['status' => 'SUCCESS', 'code' => 200, 'data' => "Your Preferences are updated"]);
            } catch (Exception $e) {
                return Response::json(['status' => 'FAILURE', 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            }

        }

        /**
         * Update payment related details
         * @return mixed
         */
        public function postPayment()
        {
            try {
                // if (!$this->isCsrfAccepted())
                //     throw new Exception('Invalid request parameters', 401);
                $pay_mode = Request::get('payment', '');
                $transaction_mode = Request::get('transaction', 'TEST');
                $params = Request::get("params", []);
                if (!empty($pay_mode)) {
                    $conditions = [['column' => ['group' => 'payment', 'key' => 'mode'], 'value' => $pay_mode], ['column' => ['group' => 'payment', 'key' => 'type'], 'value' => $transaction_mode]];
                }
                if (!empty($conditions)) {
                    foreach ($conditions as $condition) {
                        $this->updateSetting($condition);
                    }
                }
                Cache::forget('CACHE_PARAM_SETTINGS');
                // Update Param Settings
                foreach ($params as $key => $param) {
                    $payment_setting = PaymentGatewaySetting::where('gateway_id', '=', $pay_mode)->where('key', '=', $key)->first();
                    $payment_setting->value = $param;
                    $payment_setting->save();
                }

                return Response::json(['status' => 'SUCCESS', 'code' => 200, 'data' => "Your Preferences are updated"]);
            } catch (Exception $e) {
                return Response::json(['status' => 'FAILURE', 'code' => $e->getCode(), 'msg' => $e->getMessage()]);
            }
        }

        /**
         * Add User as admin
         * @return mixed
         */
        public function postUser()
        {

            try {
                // if (!$this->isCsrfAccepted())
                //     throw new Exception('Invalid request parameters', 401);

                $name = Request::get('name', '');
                $username = Request::get('username', '');
                $email = Request::get('email', '');
                $password = Request::get('password', '');
                $admin = Admin::where('email', '=', $email)->first();
                $user = null;

                if (is_null($admin)) {
                    $admin = new Admin;
                    $admin->name = $name;
                    $admin->email = $email;
                    $admin->admin_type = AdminType::SUPER_ADMIN();
                    $admin->created_by = 1;
                    $admin->updated_by = 1;
                    $admin->created_at = date('Y-m-d H:i:s');
                } else {
                    $user = $admin->user_details()->first();
                }
                $status = $admin->save();


                if ($status && is_null($user)) {
                    $user = new User();
                    $user->email = $email;
                    $user->name = $name;
                    $user->password = Hash::make($password);
                    $user->user_type_id = UserType::ADMIN();
                    $user->user_status = UserStatus::ACTIVE();
                    $user->user_id = $admin->id;
                    $user->created_by = 1;
                    $user->created_at = date('Y-m-d H:i:s');
                } else {
                    $user->password = Hash::make($password);
                }
                $user->save();

                return Response::json(['status' => 'SUCCESS', 'code' => 200, 'data' => "User has been created"]);

            } catch (Exception $e) {
                return Response::json(['status' => 'FAILURE', 'code' => $e->getCode(), 'msg' => $e->getMessage()]);

            }
        }

        /**
         * Update Settings Table
         * @param $parameters
         */
        protected function updateSetting($parameters)
        {
            $setting = Setting::where('is_active', '=', 1);
            foreach ($parameters['column'] as $column => $value) {
                $setting->where($column, '=', $value);
            }
            $setting_first = $setting->first();
            $setting_first->value = $parameters['value'];
            $setting_first->save();

        }
    }
