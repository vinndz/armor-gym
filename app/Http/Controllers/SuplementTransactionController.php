<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuplementTransaction;
use App\Models\User;
use App\Models\Suplement;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class SuplementTransactionController extends Controller
{

    public function index()
    {
        $users = User::all(); 
        $suplements = Suplement::all();
        return view('admin/suplement_transaction/index', compact('users', 'suplements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'suplement_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        // Retrieve user and supplement
        $user = User::findOrFail($request->user_id);
        $supplement = Suplement::findOrFail($request->suplement_id);

        // Check if there is sufficient stock
        if ($supplement->stock < $request->amount) {
            return response()->json(['errors' => ['amount' => ['Insufficient stock!']]], 422);
        }

        // Calculate total transaction amount
        $total = $request->amount * $supplement->price;

        // Create SuplementTransaction record
        $date = Carbon::now();
        $transaction = SuplementTransaction::create([
            'user_id' => $user->id,
            'suplement_id' => $supplement->id,
            'date' => $date,
            'amount' => $request->amount,
            'total' => $total,
        ]);

        // Update stock
        $supplement->stock -= $request->amount;
        $supplement->save();

        // Optionally, you can use an alert or flash message
        Alert::success('Success', 'Successfully created supplement transaction!');

        // Redirect back or return response as needed
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
        $totalRecords = SuplementTransaction::distinct('user_id')
                                            ->count('user_id');

        // Penerapan pencarian jika ada nilai pencarian
        $filteredRecords = SuplementTransaction::with('user', 'suplement')
        ->where(function($query) use ($searchValue) {
            $query->whereHas('user', function($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('username', 'like', '%' . $searchValue . '%');
                })
                ->orWhereHas('suplement', function($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('price', 'like', '%' . $searchValue . '%');
                })
                ->orWhereRaw("DATE_FORMAT(date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
                ->orWhere('amount', 'like', '%' . $searchValue . '%');
        })
        ->whereIn('suplement_transactions.id', function($query) {
            $query->select(DB::raw('MAX(id)'))
                ->from('suplement_transactions')
                ->groupBy('user_id');
        })
        ->latest('created_at')
        ->limit(1); // Batasi hasil hanya satu baris
    
        $totalRecordswithFilter = $filteredRecords->distinct('user_id')->count('user_id');


        // Get paginated records
        $records = $filteredRecords->skip($start)
                                    ->take($rowperpage)
                                    ->get();


        $data_arr = array();

        foreach ($records as $index => $record) {
            $no = $index + 1;
            $name = ucwords($record->user->name);
            $suplement = $record->suplement->name;
            $amount = $record->amount;
            $price = $record->suplement->price;
            $total = $record->total;
            $date = $record->date;


            // Add Additional button
            $detail = '<a href="#" class="btn btn-outline-info btn-xs additional-btn" style="width:80px; margin-right:20px;" data-id="'.$record->user->id.'">Detail</a>';


            // Extract supplement data for the current record (only the first one)
            

            $data_arr[] = array(
                "id" => $no,
                "name" => $name,
                "suplement" => $suplement,
                "amount" => $amount,
                "price" => $price,
                "total" => $total,
                "date" => $date,
                "action" =>  $detail ,
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


    public function detail($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $transactions = SuplementTransaction::where('user_id', $userId)->latest('date')->paginate(10);
            return view('admin.suplement_transaction.detail', compact('user', 'transactions'));
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
            $transaction = SuplementTransaction::where('id', $userId)->first();


            $user = $transaction->user;

            
            return view('admin.suplement_transaction.receipt', compact('user', 'transaction'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangani jika transaksi tidak ditemukan
            dd($e->getMessage());
            Alert::error('Errors', 'Transaction not found!');
            return redirect()->back();
        }
    }

    // public function detail($userId)
    // {
    //     $user = User::findOrFail($userId);
    
    //     $suplements = $user->suplements()->withPivot('amount', 'total', 'date')->latest('date')->paginate(10);
    //     // dd($suplements);
    //     $transaction = DB::table('suplement_transactions')->where('id', $userId)->first();

    //     return view('admin.suplement_transaction.detail', compact('user', 'suplements', 'transaction'));
    // }
    

    // public function receipt($userId)
    // {
    //     try {
    //         // Mengambil pengguna berdasarkan ID
    //         $user = User::findOrFail($userId);

    //         // $suplement = $user->suplements()->withPivot('amount', 'total', 'date')->first();
    //         // $suplementTransaction = DB::table('suplement_transactions')->where('id', $userId)->first();
    //         // $suplementTransaction = $user->suplements()->latest('created_at')->first();
    //         $suplementTransaction = $user->suplements()->orderBy('created_at')->first();

  
    //         return view('admin.suplement_transaction.receipt', compact('user', 'suplementTransaction'));
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         dd($e->getMessage());
    //         Alert::error('Errors', 'Transaction not found!');
    //         return redirect()->back();
    //     }
    // }


    


    
    


    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'suplement_id' => 'required|exists:suplements,id',
            'amount' => 'required|integer|min:1',
        ]);

        // Temukan data transaksi berdasarkan ID
        $user = User::findOrFail($id);

        // Temukan data suplemen berdasarkan ID
        $supplement = Suplement::findOrFail($validatedData['suplement_id']);

        // Cek apakah stok cukup
        if ($supplement->stock < $validatedData['amount']) {
            Alert::error('Errors', 'Insufficient stock!');
            return redirect()->back();
        }

        // Hitung total harga transaksi
        $total = $supplement->price * $validatedData['amount'];

        // Simpan perubahan data transaksi
        $user->update([
            'user_id' => $validatedData['user_id'],
            'amount' => $validatedData['amount'],
            'total' => $total,
        ]);

        // Update data pada tabel perantara menggunakan attach
        $user->suplements()->detach($supplement->id); // Hapus terlebih dahulu untuk mencegah duplikasi
        $user->suplements()->attach($supplement->id, [
            'date' => now(),
            'amount' => $validatedData['amount'],
            'total' => $total,
        ]);

        // Update stok suplemen
        $supplement->stock -= $validatedData['amount'];
        $supplement->save();

        Alert::success('Success', 'Successfully updated supplement transaction!');
        return redirect()->back();
    }





}
