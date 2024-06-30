<?php

namespace App\Http\Controllers;

use App\Models\DailyGymTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;




class DailyGymTransactionController extends Controller
{
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
            'username' => ['required', function ($attribute, $value, $fail) {
                $existingTransaction = DailyGymTransaction::whereHas('user', function ($query) use ($value) {
                    $query->where('username', $value);
                })->whereDate('date', Carbon::today())->exists();
        
                if ($existingTransaction) {
                    $fail('This user has already made a transaction today.');
                }
            }],
            'price' => 'required',
        ]);
        $user = User::where('username', $request->username)->first();


        if (!$user) {
            return redirect()->back()->withErrors(['username' => 'User not found.']);
        }

        $existingTransaction = DailyGymTransaction::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->exists();

        if ($existingTransaction) {
            return redirect()->back()->withErrors(['transaction' => 'This user has already made a transaction today.']);
        }
        $date = Carbon::now()->format('Y-m-d');

        DailyGymTransaction::create([
            'user_id' => $user->id,
            'date' => $date,
            'price' => $request->price,
        ]);

        Alert::success('Success', 'Successfully created daily gym');
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

        // Total records
        $totalRecords = DailyGymTransaction::distinct('user_id')
                                            ->count('user_id');

        // Filtered records
        $filteredRecords = DailyGymTransaction::with('user')
            ->whereHas('user', function($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('username', 'like', '%' . $searchValue . '%');
            })
            ->orWhereRaw("DATE_FORMAT(date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
            ->whereIn('daily_gym_transactions.id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('daily_gym_transactions')
                    ->groupBy('user_id');
            })
            ->latest('date')
            ->limit(1); // Batasi hasil hanya satu baris

        $totalRecordswithFilter = $filteredRecords->distinct('user_id')->count('user_id');

        // Get paginated records
        $records = $filteredRecords->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();


        $data_arr = [];
        $temp_records = [];

        foreach ($records as $record) {
            $user_id = $record->user_id;

            // Jika data belum ada atau tanggal yang sekarang lebih baru, simpan data terbaru
            if (!isset($temp_records[$user_id]) || Carbon::parse($record->date)->gt(Carbon::parse($temp_records[$user_id]->date))) {
                $temp_records[$user_id] = $record;
            }
        }

        $counter = 1;
        foreach ($temp_records as $record) {
            $name = $record->user->name; 
            $username = $record->user->username; 
            $price = $record->price; 
            $date = Carbon::parse($record->date)->format('d-m-Y');

            $detail = '<a href="#" class="btn btn-outline-info btn-xs detail-btn" style="width:80px; margin-right:20px;" data-id="'.$record->user->id.'">Detail</a>';


            // Tambahkan data ke array $data_arr
            $data_arr[] = array(
                "id" => $counter,
                "name" => $name,
                "username" => $username,
                "price" => $price,
                "date" => $date,
                "action" => $detail,
            );
            $counter++;
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }



    public function detail($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $dailys = DailyGymTransaction::where('user_id', $userId)->latest('date')->paginate(10);
            return view('admin.daily_gym_transaction.detail', compact('user', 'dailys'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangani jika pengguna tidak ditemukan
            dd($e->getMessage());
            Alert::error('Errors', 'User not found!');
            return redirect()->back();
        }
    }

    public function receipt($userId)
    {
        try {
            // Mengambil transaksi harian berdasarkan $userId
            $daily = DailyGymTransaction::where('id', $userId)->first();
            
            // Mendapatkan informasi pengguna yang bertransaksi
            $user = $daily->user;
       

            $dailyTransactionId = $daily->id;
            
            return view('admin.daily_gym_transaction.receipt', compact('user', 'daily'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangani jika transaksi tidak ditemukan
            dd($e->getMessage());
            Alert::error('Errors', 'Transaction not found!');
            return redirect()->back();
        }
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
            Alert::success('Success', 'Successfully updated daily gym');
            return redirect()->back();
        } else {
            Alert::success('Error', 'Failed updated daily gym');
            return redirect()->back();
        }
    }



   
}
