<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Services\SearchService;

class MembershipController extends Controller
{
    protected $searchService;

    public function __construct (SearchService $searchService) 
    {
        $this->searchService = $searchService;
    }
    public function index()
    {
        $memberships = Membership::all();
        return view('admin/membership_data/index', compact('memberships'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);
        
            Membership::create([
                'type' => $request->type,
                'price' => $request->price,
                'description' => $request->description,
            ]);
            return redirect()->back()->with('success', 'Successfully added type membership');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membership $membership, $id)
    {
        $validatedData = $request->validate([
            'type' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);
    
        $membership = Membership::findOrFail($id);
    
        $membership->fill($validatedData);
        $membership->save();
    
        if ($membership) {
            return redirect()
                ->back()
                ->with(["success" => "Successfully updated membership type"]);
        } else {
            return redirect()
                ->back()
                ->with(["error" => "Failed to update membership type"]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $membership = Membership::where("id", $id);

        $membership->delete();

        if ($membership) {
            return redirect()
                ->intended("membership-data/index")
                ->with(["success" => "Successfully deleted membership typr"]);
        } else {
            return redirect()
                ->intended("membership-data/index")
                ->with(["error" => "Failed to deleted membership type"]);
        }
    }

    public function search(Request $request)
    {
        $memberships = $this->searchService->handle($request, new Membership, ['type','price', 'description'])
                                            ->paginate(10)
                                            ->withQueryString()
                                            ->withPath('membership-data-index');

        return view('admin.membership_data.table', [
            'memberships' => $memberships,
        ]);
    }
}
