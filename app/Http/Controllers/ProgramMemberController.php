<?php

namespace App\Http\Controllers;

use App\Models\ProgramMember;
use App\Models\MembershipTransaction;
use App\Models\ProgramData;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProgramMemberController extends Controller
{

    public function index()
    {
        $transactionMembers = MembershipTransaction::where('status', 'active')
                            ->whereHas('membership', function ($query) {
                                $query->where('type', 'membership with instructor')
                                        ->orWhere('type', 'membership add instructor');
                            })
                            ->with('user')
                            ->get();

        $members = $transactionMembers->pluck('user.name', 'user.id');
        $programs = ProgramData::all();


        return view('instructor.program_member.index', compact( 'members', 'programs'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'membership_id' => 'required',
            'program_data_id' => 'required',
            'date' => 'required',
        ]);


        // ambil id instruktur yang login
        $instructorId = auth()->user()->id;

        // ambil id
        $membershipTransaction = MembershipTransaction::where('user_id', $request->membership_id)->first();
        // dd($membershipTransaction);
        
    
        //cek status not finish
        $existingProgramMember = ProgramMember::where('user_id', $instructorId)
                                                ->where('membership_transaction_id', $membershipTransaction->id)
                                                ->where('status', 'not finish')
                                                ->exists();

        if ($existingProgramMember) {
            Alert::warning('Warning', 'You cannot add a new program member until the current one is finished!');
            return redirect()->back();
        }

        $status = 'not finish';
        $date = date('Y-m-d', strtotime($request->date));
        
        ProgramMember::create([
            'user_id' => $instructorId,
            'membership_transaction_id' => $membershipTransaction->id,
            'program_data_id' => $request->program_data_id,
            'date' => $date,
            'status' => $status
        ]);

        Alert::success('Success', 'Successfully created program member!');
        return redirect()->back();
    }

    public function update($id)
    {
        // Temukan ProgramMember berdasarkan ID
        $member = ProgramMember::find($id);

        if (!$member) {
            // Jika ProgramMember tidak ditemukan, redirect dengan pesan kesalahan
            Alert::error('Error', 'Failed updated program member!');
            return redirect()->back();
        }

        if($member->status == 'finish'){
            Alert::error('Error', 'Member already finish!');
            return redirect()->back();
        }
        // Update status menjadi 'finish'
        $member->update(['status' => 'finish']);

        // Redirect dengan pesan sukses jika berhasil
        Alert::success('Success', 'Successfully change status to finish!');
            return redirect()->back();
    }

    public function data(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start", 0);
        $rowperpage = $request->get("length", 10); // Rows display per page

        $columnIndex_arr = $request->get('order', []);
        $columnName_arr = $request->get('columns', []);
        $order_arr = $request->get('order', []);
        $searchValue = $request->input('search.value'); // Search value

        // Mendapatkan ID instruktur yang sedang login
        $instructorId = auth()->user()->id;

        // Menghitung total record
        $totalRecords = ProgramMember::where('user_id', $instructorId)->count();

        // Query untuk pencarian dan pengurutan
        $query = ProgramMember::where('user_id', $instructorId);

        // Penerapan pencarian jika ada nilai pencarian
        if (!empty($searchValue)) {
            $query->where(function($query) use ($searchValue) {
                $query->whereHas('membershipTransaction.user', function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                })
                ->orWhereHas('programData', function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                })
                ->orWhereRaw("DATE_FORMAT(date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue."%"])
                ->orWhere('status', 'like', '%' . $searchValue . '%');
            });
        }

        // Total records setelah penerapan pencarian
        $totalRecordswithFilter = $query->count();

        // Default sorting
        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnName = $columnName_arr[$columnIndex]['data'] ?? 'id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'asc';

        // Get paginated records
        $records = $query->orderBy($columnName, $columnSortOrder)
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

        $data_arr = array();
        $csrfToken = csrf_token();
        foreach ($records as $index => $record) {
            $no = $start + $index + 1;
            $name = ucwords($record->membershipTransaction->user->name); 
            $program = ucwords($record->programData->name); 
            $date = $record->date; 
            $status = ucwords($record->status); 

            $data_arr[] = array(
                "id" => $no,
                "name" => $name,
                "program" => $program,
                "date" => $date,
                "status" => $status,
                "action" => '<div class="btn-group" role="group">`
                                <form action="'.route('program-member.update', ['id' => $record->id]).'" method="POST" style="display: inline;">
                                    <input type="hidden" name="_token" value="'.$csrfToken.'">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button type="submit" class="btn btn-outline-success btn-xs" style="width:80px; margin-right:20px">Finish</button>
                                </form>
                            </div>',
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }






}
