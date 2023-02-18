<?php

namespace App\Http\Controllers;

use App\FreelancerAccount;
use App\Http\Controllers\Auth\LoginController;
use App\Job;
use App\Permission;
use App\Role;
use App\RolePermission;
use App\User;
use App\UserLead;
use BidsSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use JobSeeder;

class SettingController extends Controller
{
    private $role, $permission, $rolePermission, $freelanceAccount, $user;

    public function __construct()
    {
        $this->role = new Role();
        $this->permission = new Permission();
        $this->rolePermission = new RolePermission();
        $this->freelanceAccount = new FreelancerAccount();
        $this->user = new User();
    }
    public function index()
    {
        $heading = 'Permissions';
        $roles = $this->role->newQuery()->get();
        $permissions = $this->permission->newQuery()->get();
        $accounts = $this->freelanceAccount->newQuery()->get();
        // dd($roles);
        return view('settings.permissions', compact('roles', 'permissions', 'heading', 'accounts'));
    }

    public function profileEdit()
    {
        $heading = 'Update Profile';
        return  view('settings.profile', compact('heading'));
    }


    public function profileUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $user = $this->user->newQuery()->where('id', Auth::id())->first();
            $user->fill($inputs);
            if (!$user->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while updating profile')->withInput();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Profile Updated')->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function updatePassword(Request $request)
    {
        request()->validate([
            'password' => 'required|confirmed:password_confirmation'
        ]);
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $user = $this->user->newQuery()->where('id', Auth::id())->first();
            $user->password = Hash::make($inputs['password']);
            if (!$user->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while updating password')->withInput();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Password Updated')->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            if ($rolePermission = $this->rolePermission->newQuery()->where('permission_id', $inputs['permission_id'])->where('action_id', $inputs['action_id'])->where('role_id', $inputs['role_id'])->first()) {
                if ($rolePermission->delete()) {
                    DB::commit();
                    return ['success' => true, 'permission' => false];
                }
            } else {
                $rolePermission = $this->rolePermission->newInstance();
                $rolePermission->fill($inputs);
                if ($rolePermission->save()) {
                    DB::commit();
                    return ['success' => true, 'permission' => true];
                }
            }
            DB::rollBack();
            return ['success' => false];
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function addAccount()
    {
        $heading = 'Add New Account';
        return view('settings.accounts.add', compact('heading'));
    }

    public function storeAccount(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $freelanceAccount = $this->freelanceAccount->newInstance();
            $freelanceAccount->fill($inputs);
            if (!$freelanceAccount->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while adding new account.')->withInput();;
            }
            if (Auth::user()->freelancer_account_id == NULL) {
                Auth::user()->freelancer_account_id = $freelanceAccount->id;
                Auth::user()->save();
                // if (Job::count() == 0) {
                //     $job = new JobSeeder();
                //     $job->run();
                // }
            }
            // $bids = new BidsSeeder();
            // $bids->run();
            DB::commit();
            return redirect('settings')->with('success', 'Account added successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function deactivateAccount($id)
    {
        try {
            DB::beginTransaction();
            $freelanceAccount = $this->freelanceAccount->newQuery()->where('id', $id)->first();
            $freelanceAccount->status = DEACTIVE;
            if (!$freelanceAccount->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while deactivating account.');
            }
            DB::commit();
            return redirect('settings')->with('success', 'Account deactivated successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function activateAccount($id)
    {
        try {
            DB::beginTransaction();
            $freelanceAccount = $this->freelanceAccount->newQuery()->where('id', $id)->first();
            $freelanceAccount->status = ACTIVE;
            if (!$freelanceAccount->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while deactivating account.');
            }
            DB::commit();
            return redirect('settings')->with('success', 'Account deactivated successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function deleteAccount($id)
    {
        try {
            DB::beginTransaction();
            $freelanceAccount = $this->freelanceAccount->newQuery()->where('id', $id)->first();
            if (!$freelanceAccount->delete()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while deleting account.');
            }
            if (Auth::user()->freelancer_account_id == $freelanceAccount->id) {
                if (!$account = $this->freelanceAccount->newQuery()->where('id', $id)->first()) {
                    Auth::user()->freelancer_account_id = NULL;
                } else Auth::user()->freelancer_account_id = $account->id;
                Auth::user()->save();
            }
            UserLead::where('account_id', $id)->delete();
            DB::commit();
            return redirect('settings')->with('success', 'Account deleted successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function editAccount($id)
    {
        $heading = "Edit Account";
        if ($freelanceAccount = $this->freelanceAccount->newQuery()->where('id', $id)->first()) {
            return view('settings.accounts.edit', compact('heading', 'freelanceAccount'));
        } else {
            return redirect()->back()->with('error', 'Account not found');
        }
    }

    public function updateAccount(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            if (!$freelanceAccount = $this->freelanceAccount->newQuery()->where('id', $inputs['id'])->first()) {
                return redirect()->back()->with('error', 'Account not found');
            }
            $freelanceAccount->fill($inputs);
            if (!$freelanceAccount->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while updating account.')->withInput();
            }
            DB::commit();
            return redirect('settings')->with('success', 'Account udpated successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), ERROR_500);
        }
    }

    public function updateUserActiveAccount($id)
    {
        Auth::user()->freelancer_account_id = $id;
        Auth::user()->save();
        $login = new LoginController();
        $login->getFreelancerUserDetails();
        return redirect('leads');
    }
}
