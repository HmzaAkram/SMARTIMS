<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('tenant');
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }
        
        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $users = $query->latest()->paginate(20);
        
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'managers' => User::where('role', 'manager')->count(),
            'active' => User::where('status', 'active')->count(),
        ];
        
        return view('super-admin.users.index', compact('users', 'stats'));
    }
    
    public function create()
    {
        $tenants = Tenant::where('status', 'active')->get();
        return view('super-admin.users.create', compact('tenants'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,manager,user',
            'status' => 'required|in:active,inactive',
            'tenant_id' => 'required|exists:tenants,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        User::create([
            'tenant_id' => $request->tenant_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'email_verified_at' => now(),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }
    
    public function show(User $user)
    {
        $user->load('tenant');
        return view('super-admin.users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        $tenants = Tenant::where('status', 'active')->get();
        return view('super-admin.users.edit', compact('user', 'tenants'));
    }
    
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => 'required|in:admin,manager,user',
            'status' => 'required|in:active,inactive',
            'tenant_id' => 'required|exists:tenants,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'tenant_id' => $request->tenant_id,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User updated successfully!');
    }
    
    public function destroy(User $user)
    {
        // Prevent deleting the last admin of a company
        if ($user->role == 'admin') {
            $adminCount = User::where('tenant_id', $user->tenant_id)
                ->where('role', 'admin')
                ->count();
                
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the only admin of a company');
            }
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
    
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status == 'active' ? 'inactive' : 'active'
        ]);
        
        return back()->with('success', 'User status updated!');
    }
}