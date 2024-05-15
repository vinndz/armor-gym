<?php

namespace App\Http\Controllers;

use App\Models\ProgramMember;
use App\Models\MembershipTransaction;
use App\Models\ProgramData;
use App\Models\User;
use Illuminate\Http\Request;

class ProgramMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructorId = auth()->user()->id;
        $programMembers = ProgramMember::where('user_id', $instructorId)->get();

        $transactionMembers = MembershipTransaction::where('status', 'active')
                                                    ->whereHas('membership', function ($query) {
                                                        $query->where('type', 'membership with instructor');
                                                    })
                                                    ->pluck('user_id');
        $members = User::whereIn('id', $transactionMembers)->pluck('name');

        $programs = ProgramData::select('name')->get();


        return view('instructor.program_member.index', compact('programMembers', 'members', 'programs'));
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
        // Validasi input
        $request->validate([
            'membership_transaction_id' => 'required',
            'program_data_id' => 'required',
        ]);

        // Dapatkan ID instruktur yang login
        $instructorId = auth()->user()->id;

        // Dapatkan data membership transaction berdasarkan ID yang diberikan
        $membershipTransaction = MembershipTransaction::findOrFail($request->membership_transaction_id);

        //
        $membershipType = $membershipTransaction->membership->type;

        // Pastikan member memiliki jenis membership with instructor dan status active
        if ($membershipType !== 'membership with instructor' || $membershipTransaction->status !== 'active') {
            return redirect()->back()->with('error', 'Invalid membership transaction.');
        }

        // Buat program member baru
        $programMember = new ProgramMember();
        $programMember->user_id = $membershipTransaction->user_id;
        $programMember->membership_transaction_id = $request->membership_transaction_id;
        $programMember->program_data_id = $request->program_data_id;
        $programMember->instructor_id = $instructorId; 
        $programMember->save();

        return redirect()->route('program-member.index')->with('success', 'Program member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramMember $programMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramMember $programMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramMember $programMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramMember $programMember)
    {
        //
    }
}
