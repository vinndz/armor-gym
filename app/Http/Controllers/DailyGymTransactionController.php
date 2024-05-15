<?php

namespace App\Http\Controllers;

use App\Models\DailyGymTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\SearchService;


class DailyGymTransactionController extends Controller
{
    protected $searchService;

    public function __construct (SearchService $searchService) 
    {
        $this->searchService = $searchService;
    }   
    
    public function index()
    {
        $users= User::where('role', 'GUEST')->get();
        $dailys = DailyGymTransaction::all();
        return view('admin.daily_gym_transaction.index', compact('users', 'dailys'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'price' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        $existingTransaction = DailyGymTransaction::where('user_id', $user->id)
        ->whereDate('date', Carbon::today())
        ->exists();

        if ($existingTransaction) {
            return redirect()->back()->withErrors(['username' => 'This user has already made a transaction today.']);
        }

        $date = Carbon::now()->format('Y-m-d');

        DailyGymTransaction::create([
            'user_id' => $user->id,
            'date' => $date,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Successfully added daily gym ');
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'username' => 'required',
            'price' => 'required|numeric',
        ]);
    
        $daily = DailyGymTransaction::findOrFail($id);
    
        $daily->fill($validatedData);
        $daily->save();
    
        if ($daily) {
            return redirect()
                ->back()
                ->with(["success" => "Successfully updated supplement"]);
        } else {
            return redirect()
                ->back()
                ->with(["error" => "Failed to update supplement"]);
        }
    }

    public function search(Request $request)
    {
        $dailys = $this->searchService->handle($request, new DailyGymTransaction(), ['name','username', 'price', 'date'], ['user'])
            ->paginate(10)
            ->withQueryString()
            ->withPath('daily-gym-transaction-index');

        return view('admin.daily_gym_transaction.table', [
            'dailys' => $dailys,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyGymTransaction $dailyGymTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyGymTransaction $dailyGymTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.



     * Remove the specified resource from storage.
     */
    public function destroy(DailyGymTransaction $dailyGymTransaction)
    {
        //
    }
}
