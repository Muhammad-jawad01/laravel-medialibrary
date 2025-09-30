<?php


namespace App\Http\Controllers\Api;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        $request->validate([
            'type' => 'required|in:student,staff',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($request->type === 'student') {
            // Check unique email for students
            $request->validate([
                'email' => 'unique:students,email',
            ]);
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);


            $token = $student->createToken('Student Token')->plainTextToken;
            return response()->json(['message' => 'Registration successful', 'student' => $student, 'token' => $token], 201);
        } elseif ($request->type === 'staff') {
            // Check unique email for staff
            $request->validate([
                'email' => 'unique:staff,email',
            ]);
            $staff = Staff::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $staff->createToken('Staff Token')->plainTextToken;
            return response()->json(['message' => 'Registration successful', 'staff' => $staff, 'token' => $token], 201);
        }
        return response()->json(['message' => 'Invalid registration type.'], 400);
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Session store not set on request.

        // Try student login first
        $student = Student::where('email', $credentials['email'])->first();
        if ($student && Hash::check($credentials['password'], $student->password)) {
            $token = $student->createToken('api-token')->plainTextToken;

            return response()->json(['message' => 'Login successful', 'student' => $student, 'token' => $token], 200);
        } else {
            // If not student, try staff login
            $staff = Staff::where('email', $credentials['email'])->first();
            if ($staff && Hash::check($credentials['password'], $staff->password)) {
                $token = $staff->createToken('api-token')->plainTextToken;
                return response()->json(['message' => 'Login successful', 'staff' => $staff, 'token' => $token], 200);
            }
        }
        return response()->json(['message' => 'Invalid credentials.'], 401);
    }


    public function staffLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Session store not set on request.

        // Student login
        $staff = Staff::where('email', $credentials['email'])->first();
        if ($staff && Hash::check($credentials['password'], $staff->password)) {
            $token = $staff->createToken('Staff Token')->plainTextToken;
            return response()->json(['message' => 'Login successful', 'staff' => $staff, 'token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }
}
