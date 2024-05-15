<?php

namespace App\Http\Controllers;

use App\Models\ProgramData;
use Illuminate\Http\Request;
use App\Services\SearchService;

class ProgramDataController extends Controller
{
    protected $searchService;

    public function __construct (SearchService $searchService) 
    {
        $this->searchService = $searchService;
    }
    public function index()
    {
        $datas = ProgramData::all();
        return view('instructor.program_data.index', compact('datas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
            ProgramData::create([
                'name' => $request->name,
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
            'description' => 'nullable|string|max:1000',
        ]);
    
        $data = ProgramData::findOrFail($id);
    
        $data->fill($validatedData);
        $data->save();
    
        if ($data) {
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
        $data = ProgramData::where("id", $id);

        $data->delete();

        if ($data) {
            return redirect()
                ->intended("program-data/index")
                ->with(["success" => "Successfully updated supplement"]);
        } else {
            return redirect()
                ->intended("program-data/index")
                ->with(["error" => "Failed to update supplement"]);
        }
    }

    public function search(Request $request)
    {
        $datas = $this->searchService->handle($request, new ProgramData, ['name','description'])->paginate(10)->withQueryString()->withPath('program-data-index');

        return view('instructor.program_data.table', [
            'datas' => $datas,
        ]);
    }


   
}
