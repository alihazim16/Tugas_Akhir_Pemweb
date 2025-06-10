<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Karena Customer adalah bagian dari model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role 'customer'
        $customers = User::where('user_type', 'CUSTOMER')->get(); // Asumsi kolom user_type di tabel users
        return response()->json($customers);
    }

    public function show($id)
    {
        $customer = User::where('user_type', 'CUSTOMER')->find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
        return response()->json($customer);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'full_name' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:15'],
            ]);

            $customer = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'user_type' => 'CUSTOMER', // Tentukan tipe user
                'created_at' => now(), // Otomatis oleh Laravel, tapi bisa diset manual
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Customer created successfully!', 'customer' => $customer], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $customer = User::where('user_type', 'CUSTOMER')->find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        try {
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $id],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
                'password' => ['nullable', 'string', 'min:8'], // Password opsional saat update
                'full_name' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:15'],
            ]);

            $customer->username = $request->username;
            $customer->email = $request->email;
            if ($request->password) {
                $customer->password = Hash::make($request->password);
            }
            $customer->full_name = $request->full_name;
            $customer->phone_number = $request->phone_number;
            $customer->updated_at = now();
            $customer->save();

            return response()->json(['message' => 'Customer updated successfully!', 'customer' => $customer]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy($id)
    {
        $customer = User::where('user_type', 'CUSTOMER')->find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully!'], 204);
    }
}