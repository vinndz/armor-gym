<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suplement;
use App\Services\SearchService;
class SuplementController extends Controller
{
    protected $searchService;

    public function __construct (SearchService $searchService) 
    {
        $this->searchService = $searchService;
    }
    public function index()
    {
        $suplements = Suplement::all();

        return view('admin/suplement_data/index', compact('suplements'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);
        
            Suplement::create([
                'name' => $request->name,
                'stock' => $request->stock,
                'price' => $request->price,
                'description' => $request->description,
            ]);
            return redirect()->back()->with('success', 'Successfully added suplement');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
        ]);
    
        $suplement = Suplement::findOrFail($id);
    
        $suplement->fill($validatedData);
        $suplement->save();
    
        if ($suplement) {
            return redirect()
                ->back()
                ->with(["success" => "Successfully updated supplement"]);
        } else {
            return redirect()
                ->back()
                ->with(["error" => "Failed to update supplement"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $suplement = Suplement::where("id", $id);

        $suplement->delete();

        if ($suplement) {
            return redirect()
                ->intended("suplement-data/index")
                ->with(["success" => "Successfully updated supplement"]);
        } else {
            return redirect()
                ->intended("suplement-data/index")
                ->with(["error" => "Failed to update supplement"]);
        }
    }

    public function search(Request $request)
    {
        $suplements = $this->searchService->handle($request, new Suplement, ['name','description', 'stock'])->paginate(10)->withQueryString()->withPath('suplement-data-index');

        return view('admin.suplement_data.table', [
            'suplements' => $suplements,
        ]);
    }
}
