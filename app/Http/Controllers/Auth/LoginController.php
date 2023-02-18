<?php

namespace App\Http\Controllers\Auth;

use App\FreelancerAccount;
use App\Http\Controllers\Controller;
use App\Http\Drivers\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    private $freelancer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->freelancer = new Freelancer();
    }

    public function login()
    {
        return view('auth.login');
    }

    public function autheticateUser(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns',
            'password' => 'required|string'
        ]);
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], ($request->remember_me ? true : false))) {
            return redirect()->back()->with('error', __('auth.invalidLogin'))->withInput();
        }
        if ($account = FreelancerAccount::where('status', ACTIVE)->first()) {
            Auth::user()->freelancer_account_id = $account->id;
            Auth::user()->save();
            if (!$this->getFreelancerUserDetails()) {
                Auth::logout();
                return redirect()->back()->with('error', 'Error getting information. Try again');
            }
        }
        return redirect('leads');
    }

    public function getFreelancerUserDetails()
    {
        $data = [
            'qualification_details' => true,
            'jobs' => true,
            'avatar' => true,
            'country_details' => true,
            'profile_description' => true,
            'display_info' => true,
            'membership_details' => true,
            'balance_details' => true,
            'financial_details' => true,
            'location_details' => true,
            'portfolio_details' => true,
            'preferred_details' => true,
            'badge_details' => true,
            'status' => true,
            'employer_reputation' => true,
            'reputation_extra' => true,
            'employer_reputation_extra' => true,
            'cover_image' => true,
            'past_cover_images' => true,
            'mobile_tracking' => true,
            'deposit_methods' => true,
            'user_recommendations' => true,
            'marketing_mobile_number' => true,
            'sanction_details' => true,
            'limited_account' => true,
            'compact' => true
        ];
        $response = $this->freelancer->get('users/0.1/self', $data);
        if (empty($response) || $response->status == 'error') {
            return false;
        }
        session(['freelancerUser' => $response->result, 'threads' => []]);
        return true;
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('login');
    }
}
