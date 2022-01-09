<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\NormalAccount;
use App\Models\ProAccount;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'place_of_birth' => ['required', 'string'],
            'country' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'region' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['firstname'].' '. $data['lastname'],
            'date_of_birth' => $data['date_of_birth'],
            'place_of_birth' => $data['date_of_birth'],
            'country' => $data['country'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number'],
            'region' => $data['region'],
            'password' => Hash::make($data['password']),
        ]);
    }

    private function generateUserId()
    {
        $string = '0123456789';
        $output = '';
        for ($i = 0; $i < 15; $i++) {
            $output .= $string[rand(0, strlen($string) - 1)];
        }
        return $output;
    }

    public function login()
    {
        $validator = Validator::make([
            'email' => request('email'),
            'password' => request('password'),
        ], [
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string']
        ]);
        if ($validator->fails()) {
            return $validator->messages();
            exit;
        }
        $user = User::where('email', request('email'))->first();
        if ($user && Hash::check(request('password'), $user->getAuthPassword())) {
            return [
                'token' => $user->createToken(time())->plainTextToken
            ];
        } else if($user && !Hash::check(request('password'), $user->getAuthPassword())) {
            return response()->json([
                'message' => 'Invalid Password'
            ]);
        } else {
            return response()->json([
                'message' => "Account doesn't exist"
            ]);
        } 
    }
    public function register()
    {
        $data = [
            'ownerName' => request('ownerName'),
            'email' => request('email'),
            'phoneNumber' => request('phoneNumber'),
            'businessName' => request('businessName'),
            'numberOfEmployees' => request('numberOfEmployees'),
            'businessDescription' => request('businessDescription'),
            'country' => request('country'),
            'state' => request('state'),
            'city' => request('city'),
            'generalLaymanLocation' => request('generalLaymanLocation'),
            'password' => request('password'),
            'confirmPassword' => request('confirmPassword'),
            'image' => request('image'),
        ];
        $validator = Validator::make($data, [
            'ownerName' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'phoneNumber' => ['required', 'string', 'min:9'],
            'businessName' => ['required', 'string'],
            'numberOfEmployees' => ['required'],
            'businessDescription' => ['required', 'string'],
            'country' => ['required', 'string'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
            'generalLaymanLocation' => ['required', 'string'],
            'password' => [
                'required', 
                'string', 
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'confirmPassword' => ['required', 'string', 'same:password'],
            'image' => ['required', 'image'],
        ]);
        if ($validator->fails()) {
            return $validator->messages();
            exit;
        }
        User::create([
            'owner_name' => strtoupper($data['ownerName']),
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'],
            'business_name' => strtoupper($data['businessName']),
            'number_of_employees' => $data['numberOfEmployees'],
            'business_description' => $data['businessDescription'],
            'country' => strtoupper($data['country']),
            'state' => strtoupper($data['state']),
            'city' => strtoupper($data['city']),
            'general_layman_location' => strtoupper($data['generalLaymanLocation']),
            'password' => Hash::make($data['password']),
            'has_pro_account' => false,
            'had_pro_account' => false,
            'can_create_store' => false,
            'is_blocked' => false,
            'image' => request('image')->store('users', 'public'),
        ]);
        return response([
            'message' => 'Account created successfully!'
        ], 201);
    }
}
