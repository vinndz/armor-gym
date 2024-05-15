<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\SearchService;
use Carbon\Carbon;

class MembershipTransactionController extends Controller
{
    protected $searchService;

    public function __construct (SearchService $searchService) 
    {
        $this->searchService = $searchService;
    }

    public function index()
    {
        $users = User::where('role', 'GUEST')->cursor();
        $memberships = MembershipTransaction::whereHas('membership')->cursor();
        $types = Membership::all();
        return view('admin.membership_transaction.index', compact('users', 'memberships', 'types'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'type' => 'required',
            'start_date' => 'required|date',
            'month' => 'required|numeric',
        ]);
        
        // find user dan type mmembership
        $user = User::where('username', $request->username)->first();
        $membership = Membership::where('type', $request->type)->first();
        
        // create end_date automatic
        $start_date = Carbon::parse($request->start_date);
        $end_date = $start_date->copy()->addMonths($request->month);

        // total
        $total = $end_date->diffInMonths($start_date) * $membership->price;


        //status member
        $status = Carbon::now()->lt(Carbon::parse($end_date)) ? 'active' : 'expired';
     
        $user->update(['role' => 'MEMBER']);
        MembershipTransaction::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'total' => $total,
            'start_date' => $request->start_date,
            'end_date' => $end_date->format('Y-m-d'),
            'status' => $status,
        ]);
        

        return redirect()->intended("membership-transaction/index")
                        ->with(["sucesss" => "Successfully Activation Membership"]);
    }

    public function search(Request $request)
    {

        $memberships = $this->searchService->handle($request, new MembershipTransaction, ['user_id', 'membership_id', 'total', 'start_date', 'end_date', 'status'], ['user', 'membership'])
                                            ->paginate(10)
                                            ->withQueryString()
                                            ->withPath('membership-transaction-index');
            

        return view('admin.membership_transaction.table', [
            'memberships' => $memberships,
        ]);
    }

}
