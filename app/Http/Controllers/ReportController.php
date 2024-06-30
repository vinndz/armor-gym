<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipTransaction;
use App\Models\Membership;
use App\Models\DailyGymTransaction;

use App\Models\Suplement;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('owner.daily_report.index');
    }

    public function indexMonthly()
    {
        return view('owner.monthly_report.index');
    }
    

    public function monthlyReport(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get("start", 0);
        $rowPerPage = $request->get("length");
        $searchValue = $request->input('search.value', '');

        $columnIndex = $request->input('order.0.column', 0);
        $columnName = $request->input('columns.' . $columnIndex . '.data', 'id');
        $columnSortOrder = $request->input('order.0.dir', 'asc');

        $typeTransaction = $request->input('type_transaction', 'All');
        $month = $request->input('month', 'All');

        $suplementTransactions = DB::table('suplement_transactions')
            ->join('users', 'users.id', '=', 'suplement_transactions.user_id')
            ->select(
                'suplement_transactions.id',
                'users.name',
                DB::raw("'Suplement' as type_transaction"),
                'suplement_transactions.date as date_transaction',
                'suplement_transactions.amount',
                'suplement_transactions.total'
            )
            ->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('suplement_transactions.amount', 'like', '%' . $searchValue . '%')
                    ->orWhere('suplement_transactions.total', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(suplement_transactions.date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"]);
            });

        $dailyGymTransactions = DB::table('daily_gym_transactions')
            ->join('users', 'users.id', '=', 'daily_gym_transactions.user_id')
            ->select(
                'daily_gym_transactions.id',
                'users.name',
                DB::raw("'Daily Gym Transaction' as type_transaction"),
                'daily_gym_transactions.date as date_transaction',
                DB::raw("NULL as amount"),  // Placeholder for the amount column
                'daily_gym_transactions.price as total'
            )
            ->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('daily_gym_transactions.price', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(daily_gym_transactions.date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"]);
            });

        $membershipTransactions = DB::table('membership_transactions')
            ->join('users', 'users.id', '=', 'membership_transactions.user_id')
            ->select(
                'membership_transactions.id',
                'users.name',
                DB::raw("'Membership Transaction' as type_transaction"),
                'membership_transactions.start_date as date_transaction',
                DB::raw("NULL as amount"),  // Placeholder for the amount column
                'membership_transactions.total'
            )
            ->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('membership_transactions.total', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(membership_transactions.start_date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
                    ->orWhereRaw("DATE_FORMAT(membership_transactions.end_date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
                    ->orWhere('membership_transactions.status', 'like', '%' . $searchValue . '%');
            });



        if ($typeTransaction !== 'All') {
            switch ($typeTransaction) {
                case 'Daily Gym Transaction':
                    $unionQuery = $dailyGymTransactions;
                    break;
                case 'Membership Transaction':
                    $unionQuery = $membershipTransactions;
                    break;
                case 'Suplement':
                    $unionQuery = $suplementTransactions;
                    break;
                
            }
        } else {
            $unionQuery = $suplementTransactions->unionAll($dailyGymTransactions)->unionAll($membershipTransactions);
        }

        if ($month !== 'All') {
            $unionQuery = DB::table(DB::raw("({$unionQuery->toSql()}) as sub"))
                ->mergeBindings($unionQuery)
                ->whereRaw("MONTH(sub.date_transaction) = ?", [$month]);
        }


        Log::debug($unionQuery->toSql());
        $totalRecords = $unionQuery->count();
        $totalRecordsWithFilter = $totalRecords;

        $records = $unionQuery->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data = [];
        foreach ($records as $index => $record) {
            $formattedDate = Carbon::parse($record->date_transaction)->format('d-m-Y');
            $amount = $record->amount ?? 1;  // Default amount to 1 if null
            $data[] = [
                "id" => $index + 1,
                "name" => ucwords($record->name),
                "type_transaction" => $record->type_transaction,
                "date_transaction" => $formattedDate,
                "amount" => $amount,
                "total" =>  $record->total,
                // "total" =>  number_format($record->total, 2, ',', '.'),
            ];
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData" => $data
        ];

        return response()->json($response);
    }


    public function dailyReport(Request $request)
    {

        $draw = $request->get('draw', 1); // Default value for draw
        $start = $request->get("start", 0); // Default start
        $rowperpage = $request->get("length", 10); // Default rows per page

        $columnIndex_arr = $request->get('order', []);
        $columnName_arr = $request->get('columns', []);
        $order_arr = $request->get('order', []);
        $search_arr = $request->input('search', ['value' => '']);

        $columnIndex = isset($columnIndex_arr[0]['column']) ? $columnIndex_arr[0]['column'] : 0; // Column index
        $columnName = isset($columnName_arr[$columnIndex]['data']) ? $columnName_arr[$columnIndex]['data'] : 'id'; // Column name
        $columnSortOrder = isset($order_arr[0]['dir']) ? $order_arr[0]['dir'] : 'asc'; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $today = Carbon::now()->toDateString();

        // Query untuk mengambil data transaksi suplemen
        $suplementTransactions = User::select(
            'suplement_transactions.id',
            'users.name',
            DB::raw("'Suplement' as type_transaction"),
            'suplement_transactions.date as date_transaction',
            'suplement_transactions.amount',
            'suplement_transactions.total'
        )
        ->join('suplement_transactions', 'users.id', '=', 'suplement_transactions.user_id')
        ->whereDate('suplement_transactions.date', $today)
        ->where(function ($q) use ($searchValue) {
            $q->where('users.name', 'like', '%' . $searchValue . '%')
            ->orWhere('suplement_transactions.amount', 'like', '%' . $searchValue . '%')
            ->orWhere('suplement_transactions.total', 'like', '%' . $searchValue . '%')
            ->orWhereRaw("DATE_FORMAT(suplement_transactions.date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"]);
        });

        // Query untuk mengambil data transaksi gym harian
        $dailyGymTransactions = DailyGymTransaction::select(
            'daily_gym_transactions.id',
            'users.name',
            DB::raw("'Daily Gym Transaction' as type_transaction"),
            'daily_gym_transactions.date as date_transaction',
            DB::raw("'-' as amount"),
            'daily_gym_transactions.price as total'
        )
        ->join('users', 'users.id', '=', 'daily_gym_transactions.user_id')
        ->whereDate('daily_gym_transactions.date', $today)
        ->where(function ($q) use ($searchValue) {
            $q->where('users.name', 'like', '%' . $searchValue . '%')
            ->orWhere('daily_gym_transactions.price', 'like', '%' . $searchValue . '%')
            ->orWhereRaw("DATE_FORMAT(daily_gym_transactions.date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"]);
        });

        // Query untuk mengambil data transaksi keanggotaan
        $membershipTransactions = MembershipTransaction::select(
            'membership_transactions.id',
            'users.name',
            DB::raw("'Membership Transaction' as type_transaction"),
            'membership_transactions.start_date as date_transaction',
            DB::raw("'-' as amount"),
            'membership_transactions.total'
        )
        ->join('users', 'users.id', '=', 'membership_transactions.user_id')
        ->whereDate('membership_transactions.start_date', $today)
        ->where(function ($q) use ($searchValue) {
            $q->where('users.name', 'like', '%' . $searchValue . '%')
            ->orWhere('membership_transactions.total', 'like', '%' . $searchValue . '%')
            ->orWhereRaw("DATE_FORMAT(membership_transactions.start_date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
            ->orWhereRaw("DATE_FORMAT(membership_transactions.end_date, '%d-%m-%Y') LIKE ?", ["%" . $searchValue . "%"])
            ->orWhere('membership_transactions.status', 'like', '%' . $searchValue . '%');
        });

        // Mengambil jenis transaksi yang dipilih dari permintaan
        $typeTransaction = $request->input('type_transaction');


        if ($typeTransaction !== null) {
            // Query untuk mengambil data berdasarkan jenis transaksi yang dipilih
            $query = null;

            switch ($typeTransaction) {
                case 'Suplement':
                    $query = $suplementTransactions;
                    break;
                case 'Daily Gym Transaction':
                    $query = $dailyGymTransactions;
                    break;
                case 'Membership Transaction':
                    $query = $membershipTransactions;
                    break;
                default:
                    // Jika jenis transaksi tidak dipilih atau "All", gabungkan semua query
                    $query = $suplementTransactions->unionAll($dailyGymTransactions)->unionAll($membershipTransactions);
                    break;
            }
        } else {
            // Jika parameter 'type' tidak ada, beri nilai default untuk query
            $query = $suplementTransactions->unionAll($dailyGymTransactions)->unionAll($membershipTransactions);
        }

        $totalRecords = $query->count();
        $totalRecordswithFilter = $totalRecords;

        // Get paginated records
        $records = $query->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
    
        foreach ($records as $index => $record) {
            $formattedDate = Carbon::parse($record->date_transaction)->format('d-m-Y');
            $amount = $record->amount == '-' ? 1 : $record->amount;
            $data_arr[] = array(
                "id" => $index + 1,
                "name" => ucwords($record->name),
                "type_transaction" => $record->type_transaction,
                "date_transaction" => $formattedDate,
                "amount" => $amount,
                "total" => $record->total,
                // "total" =>  number_format($record->total, 2, ',', '.'),
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
