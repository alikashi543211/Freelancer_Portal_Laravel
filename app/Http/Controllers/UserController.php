<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user, $role;

    public function __construct()
    {
        $this->user = new User();
        $this->role = new Role();
    }

    public function index()
    {
        $heading = 'Users';
        $users = $this->user->newQuery()->where('id', '!=', Auth::id())->get();
        return view('users.index', compact('heading', 'users'));
    }

    public function add()
    {
        $roles = $this->role->newQuery()->get();
        $heading = 'Add New User';
        return view('users.add', compact('heading', 'roles'));
    }


    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $user = $this->user->newInstance();
            $user->password = Hash::make($inputs['password']);
            $user->fill($inputs);
            if (!$user->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while adding user');
            }
            DB::commit();
            return redirect('users')->with('success', 'User added successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function details($id)
    {
        $user = $this->user->newQuery()
            ->where('id', $id)
            ->first();
        $roles = $this->role->newQuery()->get();
        $heading = 'Details';
        return view('users.details', compact('user', 'heading', 'roles'));
    }

    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $user = $this->user->newQuery()->where('id', $inputs['id'])->first();
            if (!empty($inputs['password'])) {
                $user->password = Hash::make($inputs['password']);
            }
            $user->fill($inputs);
            if (!$user->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while updating user');
            }
            DB::commit();
            return redirect('users')->with('success', 'User updated successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function deactivate($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->user->newQuery()->where('id', $id)->first();
            $user->status = DEACTIVE;
            if (!$user->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while deactivating user');
            }
            DB::commit();
            return redirect('users')->with('success', 'User deactivated successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function activate($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->user->newQuery()->where('id', $id)->first();
            $user->status = ACTIVE;
            if (!$user->save()) {
                DB::rollback();
                return redirect()->back()->with('error', 'Error while activating user');
            }
            DB::commit();
            return redirect('users')->with('success', 'User activated successfully');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
