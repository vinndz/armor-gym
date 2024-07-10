<?php

namespace App\Http\Controllers;

use App\Models\GymSchedule;
use App\Models\MembershipTransaction;
use Illuminate\Http\Request;
use app\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class GymScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = GymSchedule::all();
        // foreach ($datas as $data) {
        //     dd($data->membership_transaction_id->name);
        // }
        $transactionMembers = MembershipTransaction::where('status', 'active')
                            ->whereHas('membership', function ($query) {
                                $query->where('type', 'membership with instructor')
                                    ->orWhere('type', 'membership add instructor');
                            })
                            ->with('user')
                            ->get();
        $title = 'Delete Schedule Gym Member!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $members = $transactionMembers->pluck('user.name', 'user.id');
        return view('instructor.gym_schedule.index', compact('members', 'datas'));
    }

   

    /**
     * Show the form for creating a new resource.
     */

     public function store(Request $request)
     {
        
        $request->validate([
            'membership_id' => 'required',
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $instructorId = auth()->user()->id;
        
        $membershipTransaction = MembershipTransaction::where('user_id', $request->membership_id)->first();
        
        
        $date = Carbon::parse($request->date)->format('Y-m-d H:i');
        $status = '-';

        GymSchedule::create([
            'user_id' => $instructorId,
            'membership_transaction_id' => $membershipTransaction->id,
            'date' => $date,
            'status' => $status,
        ]);

        Alert::success('Success', 'Successfully created gym schedule member!');
        return redirect()->back();
     }

     public function update(Request $request, $id)
     {
         // Validasi input
         $validateData = $request->validate([
             'membership_transaction_id' => 'required',
             'date'  => 'required',
         ]);
         
         // Temukan jadwal berdasarkan ID
         $schedule = GymSchedule::findOrFail($id);
         
         // Jika jadwal tidak ditemukan
         if (!$schedule) {
             Alert::error('Error', 'Failed to find gym schedule member!');
             return redirect()->back();
         }
         
         // Cek apakah jadwal dengan ID $id memiliki status 'present'
         if ($schedule->status === 'present') {
             Alert::error('Error', 'Cannot update member because status is present!');
             return redirect()->back();
         }
         
         // Perbarui data jadwal dengan data yang telah divalidasi
         $schedule->membership_transaction_id = $validateData['membership_transaction_id'];
         $schedule->date = $validateData['date'];
         $schedule->save();
         
         // Berhasil memperbarui, berikan pesan sukses dan redirect kembali
         Alert::success('Success', 'Successfully updated gym schedule member!');
         return redirect()->back();
     }
     

     




     public function destroy($id)
     {
        $schedule = GymSchedule::findOrFail($id);
        if ($schedule->status === 'present') {
            Alert::error('Error', 'Cannot delete member because status is present!');
            return redirect()->back();
        }
        $schedule->delete();

        Alert::success('Success', 'Successfully deleted gym schedule member!');
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
        $search_arr = $request->input('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $instructorId = auth()->user()->id;

        // Total records
        $totalRecords = GymSchedule::whereHas('user', function($q) use ($instructorId) {
            $q->where('id', $instructorId)->where('role', 'instructor');
        })->count();

        // Filtered records
        $filteredRecords = GymSchedule::whereHas('user', function($q) use ($instructorId) {
            $q->where('id', $instructorId)->where('role', 'instructor');
        })->where(function($query) use ($searchValue) {
            $query->whereHas('user', function($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%');
            })->orWhereHas('membershipTransaction.user', function($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%');
            })->orWhereRaw("DATE_FORMAT(date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
                ->orWhereRaw("DAYNAME(date) LIKE ?", ["%" . $searchValue . "%"])
                ->orWhereRaw("TIME(date) LIKE ?", ["%" . $searchValue . "%"])
                ->orWhere('status', 'like', '%' . $searchValue . '%');
        });

        $totalRecordswithFilter = $filteredRecords->count();

        // Get paginated records
        $records = $filteredRecords->orderBy($columnName, $columnSortOrder)
                                    ->with('user', 'membershipTransaction')
                                    ->skip($start)
                                    ->take($rowperpage)
                                    ->get();

        $data_arr = array();
        $csrfToken = csrf_token();
        foreach ($records as $index => $record) {
            $id = $index + 1;
            $date = $record->date;

            // Mendapatkan nama member dari relasi membership
            $name_member = $record->membershipTransaction->user->name;
            $date = Carbon::parse($date);

            $formattedDate = $date->format('d-m-Y');
            $day = $date->format('l');
            $time = $date->format('H:i');
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "membership_transaction_id" => $name_member,
                "date" => $formattedDate,
                'day' => $day,
                'time' => $time,
                'status' => $status,
                "action" => '<div class="btn-group" role="group">
                            <a href="'.url("gym-schedule/update/".$record->id).'" class="text-primary btn-update" data-bs-toggle="modal" data-bs-target="#updateScheduleGym'.$record->id.'">
                                <button type="button" class="btn btn-outline-success btn-xs" style="width:80px; margin-right:20px">
                                    Update
                                </button>
                            </a>
                            <form action="'.route('gym-schedule.update-status', ['id' => $record->id]).'" method="POST" style="display: inline;">
                                <input type="hidden" name="_token" value="'.$csrfToken.'">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="btn btn-outline-primary btn-xs" style="width:80px; margin-right:20px">Present</button>
                            </form>                                    
                            <a href="'.route('gym-schedule.destroy', $record->id).'" class="btn btn-outline-danger btn-xs rounded-2" style="width:80px; margin-right:20px;" data-confirm-delete="true">Delete</a>
                        </div>',
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function indexMember()
    {
        return view('member.index');
    }

    public function updateStatus($id)
    {
       $schedule = GymSchedule::findOrFail($id);
       $checkStatus = GymSchedule::where('id', $id)->where('status', 'present')->first();

       if(!$schedule){
           Alert::error('Error', 'Cannot find id member!');
           return redirect()->back();
       }

       if($checkStatus){
           Alert::error('Error', 'Member already present!');
           return redirect()->back();
       }

       

       $schedule->update(['status' => 'present']);
       Alert::success('Success', 'Successfully update status member!');
       return redirect()->back();
       
    }
    public function dataMember(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->input('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $memberId = auth()->user()->id;

        // Total records
        $totalRecords = GymSchedule::whereHas('membershipTransaction', function($q) use ($memberId) {
            $q->where('user_id', $memberId);
        })->count();

        // Filtered records
        $filteredRecords = GymSchedule::whereHas('membershipTransaction', function($q) use ($memberId) {
            $q->where('user_id', $memberId);
        })->where(function($query) use ($searchValue) {
            $query->whereHas('user', function($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%');
            })
            ->orWhereRaw("DATE_FORMAT(date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
            ->orWhereRaw("DAYNAME(date) LIKE ?", ["%" . $searchValue . "%"])
            ->orWhereRaw("TIME(date) LIKE ?", ["%" . $searchValue . "%"])
            ->orWhere('status', 'like', '%' . $searchValue . '%');
        });

        $totalRecordswithFilter = $filteredRecords->count();

        // Get paginated records
        $records = $filteredRecords->orderBy($columnName, $columnSortOrder)
                                    ->with(['membershipTransaction.user']) // Load user relation of membershipTransaction
                                    ->skip($start)
                                    ->take($rowperpage)
                                    ->get();

        $data_arr = array();
        $csrfToken = csrf_token();
        foreach ($records as $index => $record) {
            $id = $index + 1;
            $date = $record->date;

            // Mendapatkan nama member dari relasi membership
            $name = $record->user->name;

            $date = Carbon::parse($date);

            $formattedDate = $date->format('d-m-Y');
            $day = $date->format('l');
            $time = $date->format('H:i');
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "date" => $formattedDate,
                'day' => $day,
                'time' => $time,
                'status' => $status,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }



     
}
