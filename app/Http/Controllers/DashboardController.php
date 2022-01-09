<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ProAccount;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function user()
    {
        return [
            'user' => auth()->user()
        ];
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response(['message' => "Logout Successful!"]);
    }

    public function createEmployee(Request $request)
    {
        $user = auth()->user();
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'dateOfBirth' => $request->dateOfBirth,
            'phoneNumber' => $request->phoneNumber,
            'image' => $request->image,
        ];
        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:employees'],
            'dateOfBirth' => ['required', 'string', 'date'],
            'phoneNumber' => ['required', 'string', 'size:9'],
            'image' => ['required', 'image'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user->employees()->create([
            'name' => strtoupper($data['name']),
            'email' => $data['email'],
            'date_of_birth' => $data['dateOfBirth'],
            'phone_number' => $data['phoneNumber'],
            'image' => $request->image->store('employees', 'public'),
        ]);
        $user->number_of_employees++;
        $user->update();
        return response(['message' => 'Employee Added Successfully!']);
    }

    public function employees()
    {
        return auth()->user()->employees()->get() ?? [];
    }

    public function deleteEmployee($id)
    {
        $employee = auth()->user()->employees()->find($id);
        if ($employee) {
            $employee->delete();
            return response(['message' => 'Employee deleted successfully!']);
        }
        return response(["message" => "Employee doesn't exist"], 404);
    }

    public function proAccount(Request $request)
    {
        $account_types = [
            ['id' => 0, 'name' => 'BASIC PLAN'],
            ['id' => 1, 'name' => 'INTERMEDIATE PLAN'],
            ['id' => 2, 'name' => 'PRO PLAN'],
            ['id' => 3, 'name' => 'SUPER TIER PLAN']
        ];
        $user = auth()->user();
        $data = [
            'id' => $request->id,
            'amount' => $request->amount,
            'expiryDate' => $request->expiryDate,
        ];
        $validator = Validator::make($data, [
            'id' => ['required'],
            'amount' => ['required', 'string'],
            'expiryDate' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $pro_type = '';
        $can_create_store = false;
        foreach ($account_types as $at) {
            if ($data['id'] == $at['id']) {
                $pro_type =  $at['name'];
                if ($data['id'] != 0) {
                    $can_create_store = true;
                } break;
            }
        }
        $account = ProAccount::where('user_id', $user->id)->first();
        if ($account) {
            $account->amount = $data['amount'];
            $account->account_type = $pro_type;
            $account->expiry_date = $data['expiryDate'];
            $account->user_id = $user->id;
            $account->update();
            $user->can_create_store = $can_create_store;
            $user->update();
            return response(['message' => 'Pro Account Updated Successfully!']);
        } else {
            ProAccount::create([
                'amount' => $data['amount'],
                'account_type' => $pro_type,
                'expiry_date' => $data['expiryDate'],
                'user_id' => $user->id
            ]);
            $user->has_pro_account = true;
            $user->can_create_store = $can_create_store;
            $user->update();
            return response(['message' => 'Pro Account Created Successfully!']);
        }
    }

    public function accountDetails()
    {
        $account = ProAccount::where('user_id', auth()->user()->id)->first();
        return $account ?? response(['message' => 'No pro account available'], 404);
    }

    public function accountExpired()
    {
        $user = auth()->user();
        $account = ProAccount::where('user_id', $user->id)->first();
        if ($account) {
            if (strtotime(date('Y-m-d H:i:s')) >= strtotime($account->expiry_date)) {
                $account->delete();
                $user->has_pro_account = false;
                $user->had_pro_account = true;
                $user->update();
                return response(['message' => 'Account has expired'], 401);
            }
            return response(['message' => 'Account has not expired'], 200);
        }
        return response(['message' => 'No pro account available'], 404);
    }

    public function createCategory(Request $request)
    {
        $data = [
            'name' => $request->name,
        ];
        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $category = $user->categories()->where('name', $request->name)->first();
        if ($category) {
            return response(['message' => 'Category already exist!'], 403);
        } else {
            $user->categories()->create([
                'name' => strtoupper($data['name']),
            ]);
            return response(['message' => 'Category created successfully!'], 200);
        }
    }

    public function categories()
    {
        return auth()->user()->categories()->get() ?? [];
    }

    public function categoryProducts($id)
    {
        $user = auth()->user();
        $category = $user->categories()->find($id);
        return $category ? $user->products()->where('category', $category->name)->get() : [];
    }

    public function deleteCategory($id)
    {
        $user = auth()->user();
        $category = $user->categories()->find($id);
        if ($category) {
            $user->products()->where('category', $category->name)->update([
                'category' => 'UNKNOWN'
            ]);
            $category->delete();
            return response(['message' => 'Category deleted successfully!']);
        }
        return response(["message" => "Category doesn't exist"], 404);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->products()->withTrashed()->forceDelete();
            $user->employees()->delete();
            $user->categories()->delete();
            $user->sales()->withTrashed()->forceDelete();
            $user->tokens()->delete();
            ProAccount::where('user_id', $id)->delete();
            $user->delete();
            return response(['message' => 'User deleted successfully!']);
        }
        return response(["message" => "User doesn't exist"], 404);
    }

    public function block_unblock($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->is_blocked) {
                $user->is_blocked = false;
                $user->update();
                return response(['message' => 'User unblocked successfully!']);
            } else {
                $user->is_blocked = true;
                $user->update();
                $user->products()->delete();
                return response(['message' => 'User blocked successfully!']);
            }
        }
        return response(["message" => "User doesn't exist"], 404);
    }

    public function createUserName(Request $request)
    {
        $data = [
            'username' => $request->username,
        ];
        $validator = Validator::make($data, [
            'username' => ['required', 'string', 'unique:users'],
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400);
        }
        $user = auth()->user();
        $user->username = $data['username'];
        $user->update();
        return response(['message' => 'User name created successfully!']);

    }

    public function getUserByUserName($username)
    {
        $user = User::where('username', $username)->first(); 
        if ($user) {
            return response([
                'user' => $user,
                'products' => $user->products()->get()
            ]);
        }
        return response(['message' => 'User not found'], 404);
    }
}