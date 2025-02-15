<?php

namespace App\Http\Controllers\Auth;

use App\Mail\NewClientRegistered;
use App\Mail\NewUserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Expertise;
use App\Models\Language;
use App\Models\Package;
use App\Models\Parents;
use App\Models\Qualification;
use App\Models\Tutor;
use App\Models\User;
use Carbon\Carbon;
use Exception;

use App\Mail\WelcomeRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends AbstractLoginController
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function showRegistrationForm(Request $request) {
        $error['message'] = null;
        if($request->session()->has('error')) $error['message'] = $request->session()->pull('error');
        $expertiseTypes = Expertise::all('id', 'name')->toArray();
        $languageTypes = Language::all('id', 'name')->toArray();
        $qualificationTypes = Qualification::all('id', 'name')->toArray();
        $user = $request->user();
        $subjectTypes = getSubjectTypes();
        return view('auth.register', compact('error','user','expertiseTypes', 'languageTypes', 'qualificationTypes', 'subjectTypes'));
    }

    public function showPricingForm(Request $request) {
        if(!$request->user()) {
            return redirect()->route('login');
        }
        $packages = Package::orderBy('monthly_price')->get()->toArray();
        $user = $request->user();
        return view('auth.pricing', compact('packages', 'user'));
    }

    public function resend_verify_email() {
        if(Auth::user()) {
            if(Auth::user()->email_verified_at) {
                return redirect()->route('dashboard.index');
            } else {
                return view('auth.resend_verify_email');
            }
        } else {
            return redirect()->route('login');
        }
    }
    public function resend_verify_email_action(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);
        $email = $request->input('email');
        $verified_user = User::where(['email' => $email])->whereNotNull('email_verified_at')->first();
        if ($verified_user) {
            return response()->json([
                'message' => 'The user already verified.'
            ], 500);
        } else {
            $user = User::where('email', $email)->first();
            if ($user) {
                $verifyData = base64_encode(json_encode([
                    'email' => $email
                ]));
                try {
                    $verifyUrl = rtrim(config('app.url'), '/') . "/verify-email/?upn=$verifyData";
                    Mail::to($email)->send(new WelcomeRegister($user, $verifyUrl));
                    return response()->json([
                        'message' => 'Email was sent successfully, Confirm your mail box'
                    ]);
                } catch (Exception) {
                    return response()->json([
                        'message' => __('auth.failed')
                    ], 500);
                }
            } else {
                return response()->json([
                    'message' => __('auth.failed')
                ], 500);
            }
        }
    }

    public function create_user(Request $request) {
        $request->validate([
            'role' => 'required',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|unique:users,email',
            'password' => 'required'
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if($user) {
                return response()->json([
                    'message' => 'User already exists'
                ]);
            }
            $data = $request->only('role', 'firstname', 'lastname', 'email', 'password');
            $data['password'] = base64_decode(urldecode($data['password']));
            $data['password'] = Hash::make($data['password']);
            $newUser = new User($data);
            $newUser->save();
            $this->sendLoginResponse($newUser, $request);
            return response()->json([
                'message' => 'User registered successfully',
                'user_id' => $newUser->id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function create(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'subject' => 'required',
            'about' => 'required',
            'phoneNumber' => 'required',
            'country' => 'required',
            'city' => 'required'
        ]);
        $newUser = User::find($request->id);
        $role = $newUser->role;
        switch($role) {
            case 'tutor':
                $request->validate([
                    'expertise' => 'required',
                    'availability' => 'required',
                    'currency' => 'required',
                    'hourlyrate' => 'nullable|required_without_all:dailyrate,termlyrate,annualrate',
                    'dailyrate' => 'nullable|required_without_all:hourlyrate,termlyrate,annualrate',
                    'termlyrate' => 'nullable|required_without_all:hourlyrate,dailyrate,annualrate',
                    'annualrate' => 'nullable|required_without_all:hourlyrate,dailyrate,termlyrate',
                    'willingTravel' => 'required',
                    'willingLocation' => 'required',
                    'languages' => 'required'
                ]);
                $tutorData = $request->only('expertise', 'availability', 'currency', 'hourlyrate', 'dailyrate', 'termlyrate', 'annualrate', 'willingTravel', 'willingLocation', 'languages');
                try {
                    $newUser->subject = $request->subject;
                    $newUser->about = $request->about;
                    $newUser->phoneNumber = $request->phoneNumber;
                    $newUser->country = $request->country;
                    $newUser->city = $request->city;
                    if($request->has('avatar')) $newUser->avatar = $request->input('avatar');
                    if($request->has('street')) $newUser->street = $request->input('street');
                    $newUser->save();
                    $userId = $newUser->id;
                    $tutorData['user_id'] = $userId;
                    $newTutor = new Tutor($tutorData);
                    if($request->has('qualification')) $newTutor->qualification = $request->input('qualification');
                    if($request->has('document')) $newTutor->document = $request->input('document');
                    if($request->has('document_dbs')) $newTutor->document_dbs = $request->input('document_dbs');
                    if($request->has('document_qualification')) $newTutor->document_qualification = $request->input('document_qualification');
                    if($request->has('reference')) $newTutor->reference = $request->input('reference');
                    $newTutor->save();
                    $verifyData = base64_encode(json_encode([
                        'email' => $newUser->email
                    ]));
                    $verifyUrl = rtrim(config('app.url'), '/') . "/verify-email/?upn=$verifyData";
                    Mail::to($newUser->email)->send(new WelcomeRegister($newUser, $verifyUrl));
                    Mail::to(config('mail.from.address'))->send(new NewUserRegistered($newUser));
                    return $this->sendLoginResponse($newUser, $request);
                } catch (Exception $e) {
                    return response()->json([
                        'message' => $e->getMessage()
                    ], 500);
                }
                break;
            case 'parent':
                $request->validate([
                    'availability' => 'required',
                    'frequent' => 'required',
                    'payway' => 'required',
                    'neededTutor' => 'required',
                    'timezone' => 'required',
                    'languages' => 'required'
                ]);
                $parentData = $request->only('availability', 'frequent', 'payway', 'neededTutor', 'timezone', 'languages');
                try {
                    $newUser->subject = $request->subject;
                    $newUser->about = $request->about;
                    $newUser->phoneNumber = $request->phoneNumber;
                    $newUser->country = $request->country;
                    $newUser->city = $request->city;
                    $newUser->save();
                    $userId = $newUser->id;
                    $parentData['user_id'] = $userId;
                    $newParent = new Parents($parentData);
                    $newParent->save();
                    $verifyData = base64_encode(json_encode([
                        'email' => $newUser->email
                    ]));
                    $verifyUrl = rtrim(config('app.url'), '/') . "/verify-email/?upn=$verifyData";
                    Mail::to($newUser->email)->send(new WelcomeRegister($newUser, $verifyUrl));
                    Mail::to(config('mail.from.address'))->send(new NewUserRegistered($newUser));
                    $tutors = User::with('reviews', 'tutor', 'package')
                        ->where('role', 'tutor')
                        ->where('status', 'active')
                        ->whereNotNull('email_verified_at')
                        ->whereNotNull('package_id')
                        ->whereRaw('JSON_VALID(subject)')
                        ->groupBy('users.id')
                        ->orderByDesc('total_score')
                        ->get();
                    foreach($tutors as $tutor) {
                        Mail::to($tutor->email)->send(new NewClientRegistered($newUser));
                    }
                    return $this->sendLoginResponse($newUser, $request);
                } catch (Exception $e) {
                    return response()->json([
                        'message' => $e->getMessage()
                    ], 500);
                }
                break;
            default:
                break;
        }
    }

    public function verify_email(Request $request)
    {
        $upn = null;
        if($request->has('upn')) $upn = $request->input('upn');
        if(!$upn) {
            return abort(404);
        }
        try {
            $userData = json_decode(base64_decode($upn));
            $verified_user = User::where(['email' => $userData->email])->whereNotNull('email_verified_at')->first();
            if ($verified_user) {
                return redirect()->route('dashboard.index',['status' => 'Email already verified, you can login.']);
            } else {
                $user = User::where('email', $userData->email)->first();
                if ($user) {
                    $user->email_verified_at = Carbon::now();
                    $user->save();
                    return redirect()->route('dashboard.index',['status' => 'Email verified now you can login.']);
                } else {
                    return abort(404);
                }
            }
        } catch (Exception) {
            return abort(404);
        }
    }
}
