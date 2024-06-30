<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Models\MembershipTransaction;
use App\Models\DailyGymTransaction;
use App\Models\Suplement;
use App\Models\GymSchedule;
use App\Models\ProgramData;
use App\Models\ProgramMember;

use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        $date = Carbon::now();

        $instructor = User::where('role', 'INSTRUCTOR')->count();
        $membership = Membership::all()->count();
        $membershipTr = MembershipTransaction::all()->count();
        $active = MembershipTransaction::where('status', 'active')->count();
        $notActive = MembershipTransaction::where('status', 'expired')->count();
        $totalDaily = DailyGymTransaction::all()->count();
        $daily = DailyGymTransaction::where('date',$date)->count();
        $suplement = Suplement::all()->count();
        $suplementTransaction = Suplement::whereHas('transactions')->count();
        $suplementTransactionDaily = Suplement::whereHas('transactions', function ($query) use ($date) {
                                                    $query->where('date', $date);
                                                })->count();
        return view('admin.home', compact('instructor', 'membership', 'membershipTr', 'active', 'notActive', 'totalDaily', 'daily', 'suplement', 'suplementTransaction', 'suplementTransactionDaily'));
    }

    public function indexOwner()
    {
        return view('owner.home');
    }

    public function indexInstructor()
    {
        $instructorId = auth()->user()->id; 
        $program = ProgramData::all()->count();
        $programMember = ProgramMember::where('user_id', $instructorId)->count();
        $scheduleGym = GymSchedule::where('user_id', $instructorId)->count();
        return view('instructor.home', compact('instructorId','program', 'programMember', 'scheduleGym'));
    }

    public function indexMember()
    {
        $memberId = auth()->user()->id;
        $schedulePresent = GymSchedule::whereHas('membershipTransaction', function($q) use ($memberId) {
                                            $q->where('user_id', $memberId);
                                        })->Where('status', 'present')
                                        ->count();

        $scheduleNotPresent = GymSchedule::whereHas('membershipTransaction', function($q) use ($memberId) {
                                $q->where('user_id', $memberId);
                            })->Where('status', '-')
                            ->count();

        return view('member.home', compact('memberId','schedulePresent', 'scheduleNotPresent'));
    }

    public function indexGuest()
    {
        return view('guest.home');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
