<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Director;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class DirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Director::with(['user', 'school']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            })->orWhereHas('school', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // School filter
        if ($schoolId = $request->input('school_id')) {
            $query->where('school_id', $schoolId);
        }

        // Commission percentage filter
        if ($commissionRange = $request->input('commission_range')) {
            switch ($commissionRange) {
                case 'low':
                    $query->where('commission_percentage', '<', 10);
                    break;
                case 'medium':
                    $query->whereBetween('commission_percentage', [10, 20]);
                    break;
                case 'high':
                    $query->where('commission_percentage', '>', 20);
                    break;
            }
        }

        // Balance filter
        if ($balanceRange = $request->input('balance_range')) {
            switch ($balanceRange) {
                case 'zero':
                    $query->where('balance', '<=', 0);
                    break;
                case 'low':
                    $query->whereBetween('balance', [1, 1000000]);
                    break;
                case 'medium':
                    $query->whereBetween('balance', [1000000, 5000000]);
                    break;
                case 'high':
                    $query->where('balance', '>', 5000000);
                    break;
            }
        }

        // Sort by
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        switch ($sortBy) {
            case 'name':
                $query->join('users', 'directors.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortOrder)
                    ->select('directors.*');
                break;
            case 'commission':
                $query->orderBy('commission_percentage', $sortOrder);
                break;
            case 'balance':
                $query->orderBy('balance', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $directors = $query->paginate(15)->withQueryString();

        // Statistics
        $totalDirectors = Director::count();
        $totalBalance = Director::sum('balance');
        $avgCommission = Director::avg('commission_percentage');
        $directorsWithSchools = Director::whereNotNull('school_id')->count();

        // Get all schools for filter dropdown
        $schools = School::orderBy('name')->get();
        return view('Director.index', compact(
            'directors',
            'totalDirectors',
            'totalBalance',
            'avgCommission',
            'directorsWithSchools',
            'schools'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        return view('Director.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validatsiya
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|min:8|unique:users,phone',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Director modeliga xos maydonlar
            'school_id' => 'required|exists:schools,id',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'balance' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // 2. Rasmni yuklash
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('director-photos', 'public');
            }

            // 3. User yaratish
            $user = User::create([
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'middle_name' => $validated['middle_name'] ?? null,
                'phone' => $validated['phone'],
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'],
                'city' => $validated['city'] ?? null,
                'region' => $validated['region'] ?? null,
                'address' => $validated['address'] ?? null,
                'password' => Hash::make($validated['password']),
                'photo' => $photoPath,
                'is_active' => $request->has('is_active'),
            ]);

            // 4. Rol biriktirish (agar 'director' roli mavjud bo'lsa)
            $roleName = 'director';
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                $user->assignRole($role);
            }

            // 5. Director modelini yaratish
            Director::create([
                'user_id' => $user->id,
                'school_id' => $validated['school_id'],
                'commission_percentage' => $validated['commission_percentage'],
                'balance' => $validated['balance'] ?? 0,
            ]);

            DB::commit();

            return redirect()->route('directors.index')->with('success', 'Direktor muvaffaqiyatli qo\'shildi!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Xatolik bo'lsa rasmni o'chirib tashlaymiz
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            return back()->with('error', 'Xatolik yuz berdi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $director= Director::findOrFail($id);
        return view('Director.show',compact('director'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $director = Director::findOrFail($id);
        $schools = School::orderBy('name')->get();
        return view('Director.edit', compact('director', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $director = Director::findOrFail($id);
        $validated = $request->validate([
            'school_id' => 'nullable|exists:schools,id',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $director->update($validated);

        return redirect()->route('directors.index')
            ->with('success', 'Direktor ma\'lumotlari yangilandi!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
