<?php

namespace App\Http\Controllers;

use App\Models\MembershipTransaction;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class MembershipTransactionController extends Controller
{


    public function index()
    {
        $users = User::where('role', 'GUEST')->cursor();
        $members = User::where('role', 'MEMBER')->cursor();
        $userMembership = MembershipTransaction::all();
        $memberships = MembershipTransaction::whereHas('membership')->cursor();
        $types = Membership::all();
        return view('admin.membership_transaction.index', compact('users', 'memberships', 'members', 'types', 'userMembership'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'type' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
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
        

        Alert::success('Success', 'Successfully created membership member');
        return redirect()->back();
    }

    public function data(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $searchValue = $request->input('search.value'); // Search value

        // Total records
        $totalRecords = MembershipTransaction::count();

        // Query untuk pencarian dan pengurutan
        $query = MembershipTransaction::select('*');

        // Penerapan pencarian jika ada nilai pencarian
        if (!empty($searchValue)) {
            $query->where(function($query) use ($searchValue) {
                $query->whereHas('membership', function ($query) use ($searchValue) {
                    $query->where('type', 'like', '%' . $searchValue . '%');
                })
                ->orWhereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                })
                ->orWhere('total', 'like', '%' . $searchValue . '%')
                ->orWhereRaw("DATE_FORMAT(start_date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
                ->orWhereRaw("DATE_FORMAT(end_date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
                ->orWhere('status', 'like', '%' . $searchValue . '%');
            });
        }
        
        $filteredRecords = $query->count();

        // Get paginated records
        $records = $query->orderBy($columnName_arr[$columnIndex_arr[0]['column']]['data'], $columnIndex_arr[0]['dir'])
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

        $data_arr = array();

        foreach ($records as $index => $record) {
            $no = $index + 1;
            $name = $record->user->name; 
            $type = $record->membership->type; 
            $start_date = $record->start_date; 
            $end_date = $record->end_date; 
            $total = $record->total; 
            $status = $record->status; 
        
            // Validasi jika end_date kurang dari hari ini
            if ($end_date < now()) {
                // Update status menjadi 'expired'
                $record->status = 'expired';
                $record->save();
                $status = 'expired'; // Update status pada variabel lokal
            }
        
            $data_arr[] = array(
                "id" => $no,
                "name" => $name,
                "type" => $type,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "total" => 'Rp. '.$total,
                "status" => $status,
                "action" => '<div class="btn-group" role="group">' . 
                                // ($record->membership->type === 'membership' && $record->status === 'active' ? 
                                // '<a href="'.route("membership-transaction.update", ['id' => $record->id]).'" class="text-primary btn-update" data-target="#updateActivationMember'.$record->id.'">
                                //     <button type="button" class="btn btn-outline-success btn-xs" style="width:120px; margin-right:20px">Update</button>
                                // </a>' 
                                // : '') . 

                                '<a href="#" class="btn btn-outline-info btn-xs card-btn rounded-2" style="width:120px; margin-right:20px;" data-id="'.($record->user ? $record->user->id : '').'" onclick="handleCardMemberClick(\''.($record->status ?? '').'\')">Card Member</a>' .

                                '<a href="#" class="btn btn-outline-warning btn-xs receipt-btn rounded-2" style="width:120px; margin-right:20px;" data-id="'.$record->user->id.'">Receipt</a>' .
                            '</div>'
            );
        }
        

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $filteredRecords,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $membership = MembershipTransaction::find($id);
        $total = $membership->total + $membership->membership->price;
        // Lakukan pembaruan data
        $membership->update([
            'membership_id' => $request->type,
            'total' => $total,
        ]);

        Alert::success('Success', 'Successfully updated membership member gym');
        return redirect()->back();
    }

    public function cardMember(Request $request, $id)

    {
        $user = User::find($id);
        $membership = MembershipTransaction::where('user_id', $user->id)->first();
        if($membership->status=== 'expired')
        {
            Alert::error('Errors', 'Membership expired cannot access it!');
            return redirect()->back();
        }
        return view('admin.membership_transaction.card-member', compact('user', 'membership'));
    }

    public function receipt($userId)
{
    try {
        // Mengambil transaksi berdasarkan user_id
        $membership = MembershipTransaction::where('user_id', $userId)->firstOrFail();
        
        // Mendapatkan informasi pengguna yang bertransaksi
        $user = $membership->user;

        return view('admin.membership_transaction.receipt', compact('user', 'membership'));
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Tangani jika transaksi tidak ditemukan
        Alert::error('Error', 'Transaction not found!');
        return redirect()->back();
    }
}


}
