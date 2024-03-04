<?php

namespace Server\Controllers;

use Server\Models\User;
use Server\Others\Sender;
use Server\Models\Setting;
use Server\Others\Services\Uploader;
use Server\Controllers\Traits\AuthTrait;
use Server\Others\Validators\UsersValidator;
use Server\Controllers\Base\SolidController;

class UsersController extends SolidController
{
    use AuthTrait;


    public function __construct()
    {
        $this->model = new User;
        $this->authKey = 'user';
        $this->sender = new Sender;
        $this->searchBy = 'first_name';
        $this->validator = new UsersValidator(new User);
    }


    public function updatePhoto($request, $response)
    {

        $user = $request->getAttribute('user');

        $user = $this->model->where('id', $user->id)->first();








        $uploaderResponse = Uploader::upload('photo');

        if (count($uploaderResponse['errors'])) {
            $this->data['errors'] = $uploaderResponse['errors'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $body['photo'] = $uploaderResponse['fullname'];


        $user->update(['photo_profile' => $body['photo']]);
        $user = $this->model->where('id', $user->id)->first();
        $user = $this->relationships($user);


        $this->data['data'] = $user;
        $this->data['message'] = "Photo Updated";
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function uploadUtilityBill($request, $response)
    {

        $user = $request->getAttribute('user');

        $user = User::where('id', $user->id)->first();




        $uploaderResponse = Uploader::upload('bill');

        if (count($uploaderResponse['errors'])) {
            $this->data['errors'] = $uploaderResponse['errors'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $body['bill'] = $uploaderResponse['fullname'];



        $user->update([
            'photo_utility_bill' => $body['bill'],
            'address_verification' => 'In Progress'
        ]);

        $user = $this->model->where('id', $user->id)->first();
        $user = $this->relationships($user);

        $this->data['data'] = $user;
        $this->data['message'] = "Upload Successful";

        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function uploadIdentityCard($request, $response)
    {

        $user = $request->getAttribute('user');

        $user = User::where('id', $user->id)->first();




        $frontid_file_name = $user->id . "-" . strtolower($user->first_name) . "-front-" . time() . ".jpg";

        $uploaderResponse = Uploader::upload('front', $frontid_file_name);

        if (count($uploaderResponse['errors'])) {
            $this->data['errors'] = $uploaderResponse['errors'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $body['front'] = $uploaderResponse['fullname'];





        $backid_file_name = $user->id . "-" . strtolower($user->first_name) . "-back-" . time() . ".jpg";

        $uploaderResponse = Uploader::upload('back', $backid_file_name);

        if (count($uploaderResponse['errors'])) {
            $this->data['errors'] = $uploaderResponse['errors'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $body['back'] = $uploaderResponse['fullname'];





        $user->update([
            'photo_back_view' => $body['back'],
            'photo_front_view' => $body['front'],
            'id_verification' => 'In Progress'
        ]);

        $user = $this->model->where('id', $user->id)->first();
        $user = $this->relationships($user);

        $this->data['data'] = $user;
        $this->data['message'] = "Upload Successful. Your identification card is currently being reviewed, if successful your account will be approved within 24 hours.";

        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function getListModel()
    {
        return $this->model->latest();
    }

    public function relationships($row)
    {
        return User::relationships($row);
    }

    public function signIn($request, $response)
    {

        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['email'])) {
            $errors[] = 'email is required';
        }

        if (!isset($body['password'])) {
            $errors[] = 'password is required';
        }

        if ($errors) {
            $this->data['errors'] =  $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $email = $body['email'] ?? '';
        $password = $body['password'] ?? '';

        $password = $this->encryptPassword($password);
        $user = $this->model->where('email', $email)->where('password', $password)->first();

        if (!$user) {
            $this->data['errors'] =  ['invalid email or password'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }




        session_regenerate_id();

        $session_id = session_id();

        $data = [
            'user_id' => $user->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'session_id' => $session_id,
            'date' => date("l jS \of F Y h:i:s A"),
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        $_SESSION['user'] = $data;




        $settings = Setting::where('id', 1)->first();

        $user_update = ['session_id' => $session_id, 'last_user_agent' => $_SERVER['HTTP_USER_AGENT']];

        if ($settings->login_verification == "enabled") {

            $pin = rand(111111, 999999);
            $user_update['pin'] = $pin;
            $user_update['login_verification'] = "Pending";
            $content = 'YOUR TWO FACTOR AUTH CODE IS ' . $pin;

            $sent = $this->sender->sendEmail([$user->email], $content, "AUTH CODE");
            if (!$sent) {
                $this->data['errors'] = ["failed to send email"];
                $response->getBody()->write(json_encode($this->data));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }









        $user->update($user_update);

        $user = $this->model->relationships($user);

        // $_SESSION[$this->authKey]['id'] = $user->id;

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function status($request, $response)
    {

        $this->data['data'] =  $this->model->getAuthState();

        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function filter($body, $safeArray)
    {
        $safeData = [];

        foreach ($safeArray as $key) {
            if (isset($body[$key])) {
                $safeData[$key] =  $body[$key];
            }
        }

        return $safeData;
    }

    public function sendPin($request, $response)
    {
        $body = $request->getParsedBody();
        $email = $body['email'] ?? '';

        $errors = [];

        if (!isset($body['email'])) {
            $errors[] = 'email is required';
        }

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user = $this->model->where('email', $email)->first();

        if (!$user) {
            $this->data['errors'] = ['email not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $pin = $user->pin;

        if ($user->pin == NULL) {
            $pin = rand(11111, 55555);
            $user->update(['pin' => $pin]);
        }

        $message = "Your verification PIN is " . $pin;

        $sent = $this->sender->sendEmail([$user->email], $message, "Verification Pin");

        if (!$sent) {
            $this->data['errors'] = ['Failed to send PIN. contact ' . getenv("MAIL_USERNAME")];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $this->data['message'] = 'PIN sent.';
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function sendPush($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['subject'])) {
            $errors[] = 'subject is required';
        }

        if (!isset($body['body'])) {
            $errors[] = 'body is required';
        }

        if (!isset($body['user_id'])) {
            $errors[] = 'user_id is required';
        }

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user_id = $body['user_id'] ?? "";
        $user = User::where('id', $user_id)->first();

        if (!$user) {
            $this->data['errors'] = ['not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $sent = $this->sender->sendPush([$user->push_subscription], $body['body'], $body['content']);

        if ($sent) {
            $this->data['message'] = "Sent";

            $response->getBody()->write(json_encode($this->data));

            return $response->withHeader('Content-Type', 'application/json');
        }

        $this->data['errors'] = ["Failed To Send"];

        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function sendCode($request, $response)
    {

        $user = $request->getAttribute('user');

        $user = User::where('id', $user->id)->first();

        $withdrawal_code = rand(111111, 999999);

        $user->update(['withdrawal_code' => $withdrawal_code]);




        $settings = Setting::where('id', 1)->first();

        if ($settings->withdrawal_code == "email") {

            $message = "YOUR WITHDRAWAL OTP IS " . $withdrawal_code;

            $sent = $this->sender->sendEmail([$user->email], $message, "WITHDRAWAL OTP");

            if (!$sent) {
                $this->data['errors'] = ["failed to send email"];
                $response->getBody()->write(json_encode($this->data));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }





        $user = User::where('id', $user->id)->first();

        $user = User::relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function userUpdate($request, $response)
    {

        $user = $request->getAttribute('user');
        $user = User::where('id', $user->id)->first();

        $body = $request->getParsedBody();
        $user->update($body);

        $user = User::where('id', $user->id)->first();
        $user = User::relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateEmail($request, $response)
    {
        $body = $request->getParsedBody();
        $user = $request->getAttribute("user");
        $user = User::where('id', $user->id)->first();

        $errors = [];

        if (!isset($body['email'])) {
            $this->data['errors'] = ['email is required'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // if same email and email is verified
        if ($body['email'] == $user->email && $user->email_verification == "Completed") {
            $this->data['errors'] = ['must be a different email'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // if different email or not verified

        // update email
        $pin = rand(11111, 99999);
        $user->update(['email' => $body['email'], 'email_verification' => "Pending", 'pin' => $pin]);


        // send pin
        $message = "Your verification PIN is " . $pin;
        $sent = $this->sender->sendEmail([$body['email']], $message, "Verification Pin");
        if (!$sent) {
            $this->data['errors'] = ['Failed to send PIN. contact ' . getenv("MAIL_USERNAME")];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // send response
        $user = User::where('id', $user->id)->first();
        $user = $this->relationships($user);
        $this->data['data'] = $user;
        $this->data['message'] = "sent to " . $body['email'];
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function verifyEmail($request, $response)
    {
        $body = $request->getParsedBody();
        $user = $request->getAttribute('user');
        $user_id = $user->id;

        if ($user->pin != $body['pin']) {
            $this->data['errors'] = ['Incorrect PIN'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        if ($user->pin == $body['pin']) {
            User::where('id', $user_id)->update(['email_verification' => "Completed", 'login_verification' => 'Completed', 'pin' => rand(11111, 99999)]);

            if ($user->welcome_email_sent === 'No') {
                if ($user->all_required_verifications === "Completed") {
                    $settings = Setting::where('id', 1)->first();
                    if (strlen($settings->welcome_mail)) {
                        $sent = $this->sender->sendEmail([$user->email], $settings->welcome_mail, "Welcome");
                        if ($sent) {
                            User::where('id', $user_id)->update(['welcome_email_sent' => 'Yes']);
                        }
                    }
                }
            }
        }

        $user = User::where('id', $user_id)->first();
        $user = User::relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function verifyAdmin($request, $response)
    {

        $body = $request->getParsedBody();

        $user_id = $body['id'];
        $id_verification = $body['id_verification'] ?? '';
        $address_verification = $body['address_verification'] ?? '';

        $user = User::where('id', $user_id)->first();

        if (!$user) {
            $this->data['errors'] = ['not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user->update(['id_verification' => $id_verification, 'address_verification' => $address_verification]);
        $user = $this->model->where('id', $user_id)->first();


        if ($user->welcome_email_sent === 'No') {
            if ($user->all_required_verifications === "Completed") {
                $settings = Setting::where('id', 1)->first();
                if (strlen($settings->welcome_mail)) {
                    $sent = $this->sender->sendEmail([$user->email], $settings->welcome_mail, "Welcome");
                    if ($sent) {
                        $user->update(['welcome_email_sent' => 'Yes']);
                    }
                }
            }
        }


        $user = $this->relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function adminUpdate($request, $response)
    {

        $body = $request->getParsedBody();

        if (!isset($body['id'])) {
            $this->data['errors'] = ['user id is required'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $row = User::where('id', $body['id'])->first();

        if (!$row) {
            $this->data['errors'] = ['not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $row->update($body);

        $user = $this->model->where('id', $body['id'])->first();
        $user = $this->relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function guestUpdatePassword($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['email'])) {
            $errors[] = 'email is required';
        }

        if (!isset($body['password_token'])) {
            $errors[] = 'token is required';
        }

        if (!isset($body['new_password'])) {
            $errors[] = 'password is required';
        }

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        if ($body['new_password'] != $body['confirm_new_password']) {
            $errors[] = 'passwords do not match';
        }

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $email = $body['email'] ?? '';
        $token = $body['password_token'] ?? '';
        $password = $body['new_password'] ?? '';

        $row = $this->model->where('email', $email)->where('pin', $token);

        if (!$row->exists()) {
            $this->data['errors'] =  ['invalid/expired token'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $row = $row->first();

        $row->update(['password' => $this->encryptPassword($password), 'pin' => NULL]);

        $row = $this->relationships($row);

        $this->data['data'] = $row;
        $this->data['message'] = "Password Updated Successfully";
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }


}