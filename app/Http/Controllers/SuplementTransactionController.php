<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suplement;
use App\Models\User;
use App\Services\SearchService;

class SuplementTransactionController extends Controller
{
    protected $searchService;

    public function __construct (SearchService $searchService) 
    {
        $this->searchService = $searchService;
    }
    public function index()
    {
        $users = User::all(); 
        $suplements = Suplement::all();
        return view('admin/suplement_transaction/index', compact('users', 'suplements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'suplement_id' => 'required|exists:suplements,id',
            'amount' => 'required|integer|min:1',
        ]);

        // Mendapatkan data user berdasarkan ID
        $user = User::findOrFail($request->user_id);

        // Mendapatkan data suplemen berdasarkan ID
        $suplement = Suplement::findOrFail($request->suplement_id);

        // Cek apakah stok cukup
        if ($suplement->stock < $request->amount) {
            return response()->json(['errors' => 'Stok suplemen tidak mencukupi'], 400);
        }

        // Menghitung total harga transaksi
        $total = $suplement->price * $request->amount;

        // Menyimpan data transaksi suplemen
        $transactionData = [
            'date' => now(),
            'amount' => $request->amount,
            'total' => $total,
        ];

        $user->suplements()->attach($suplement->id, $transactionData);

        // Update stok suplemen
        $suplement->stock -= $request->amount;
        $suplement->save();

        return response()->json(['success' => 'Transaksi suplemen berhasil disimpan'], 201);
    }


    // public function search(Request $request)
    // {
    //     $users = $this->searchService->handle($request, User::query(), ['name', 'suplements.name', 'suplements.pivot.total', 'suplements.pivot.amount', 'suplements.price'])->paginate(10)->withQueryString()->withPath('suplement-transaction-index');

    //     return view('admin.suplement_transaction.table', [
    //         'users' => $users,
    //     ]);
    // }

    public function search(Request $request)
    {
        $users = $this->searchService->handle($request, new User(), ['name', 'suplement_id', 'amount', 'price', 'total','date'],['suplements'])->paginate(10)->withQueryString()->withPath('suplement-transaction-index');

        return view('admin.suplement_transaction.table', [
            'users' => $users,
        ]);
    }



}
