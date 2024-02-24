<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::orderBy('name')->get();

        return response()->json($customers);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:customers',
            'password' => 'required|string|min:6',
        ]);

        $customer = new Customer();
        $customer->name = $validatedData['name'];
        $customer->email = $validatedData['email'];
        $customer->password = Hash::make($validatedData['password']);
        $customer->save();

        return response()->json($customer, 201);
    }
}
