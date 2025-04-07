<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Roles\Role;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user?->is_disabled) {
            return redirect()->route('login')->with('danger', 'Your account has been disabled. Please contact administrator.');
        }

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password']), true)) {
            if (session()->has('url.intended')) {
                $redirectTo = session()->get('url.intended');
                session()->forget('url.intended');

                if (auth()->user()->hasRole(Role::VENDOR)) {
                    return redirect()->route('vendors.dashboard');
                }

                return redirect()->to($redirectTo);
            } else {
                return redirect()->route('root');
                // if (auth()->user()->hasRole(Role::ADMIN)) {
                //     return redirect()->route('admin.home');
                // } else if (auth()->user()->hasRole(Role::MANAGER)) {
                //     return redirect()->route('manager.home');
                // } else if (auth()->user()->hasRole(Role::PURCHASING)) {
                //     return redirect()->route('purchasing.home');
                // } else if (auth()->user()->hasRole(Role::FINANCE)) {
                //     return redirect()->route('finance.home');
                // } else if (auth()->user()->hasRole(Role::LAPANGAN)) {
                //     return redirect()->route('lapangan.home');
                // } else if (auth()->user()->hasRole(Role::IT)) {
                //     return redirect()->route('it.home');
                // } else if (auth()->user()->hasRole(Role::ADMIN_LAPANGAN)) {
                //     return redirect()->route('adminlapangan.home');
                // } else if (auth()->user()->hasRole(Role::VENDOR)) {
                //     return redirect()->route('vendors.dashboard');
                // } else {
                //     return redirect()->route('home');
                // }
            }
        } else {
            return redirect()->route('login')
                ->with('danger', 'Email-Address And Password Are Wrong.');
        }

    }
}
