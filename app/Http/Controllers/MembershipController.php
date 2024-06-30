<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();

        $title = 'Delete Membership Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
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
            Alert::success('Success', 'Successfully created membership');
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
        $totalRecords = Membership::count();

        // Query untuk pencarian dan pengurutan
        $query = Membership::select('*');

        // Penerapan pencarian jika ada nilai pencarian
        if (!empty($searchValue)) {
            $query->where(function($query) use ($searchValue) {
                $query->where('type', 'like', '%' . $searchValue . '%')
                    ->orWhere('price', 'like', '%' . $searchValue . '%')
                    ->orWhere('description', 'like', '%' . $searchValue . '%');
            });
        }

        // Total records setelah penerapan pencarian
        $totalRecordswithFilter = $query->count();

        // Get paginated records
        $records = $query->orderBy($columnName_arr[$columnIndex_arr[0]['column']]['data'], $columnIndex_arr[0]['dir'])
                        ->skip($start)
                        ->take($rowperpage)
                        ->get();

        $data_arr = array();

        foreach ($records as $index => $record) {
            $no = $index + 1;
            $type = $record->type; 
            $price = $record->price; 
            $description = $record->description; 

            $data_arr[] = array(
                "id" => $no,
                "type" => $type,
                "price" => $price,
                "description" => $description,
                "action" => '<div class="btn-group" role="group">
                                <a href="'.route("membership-data.update", ['id' => $record->id]).'" class="text-primary btn-update" data-target="#updateMembership'.$record->id.'">
                                    <button type="button" class="btn btn-outline-success btn-xs" style="width:80px; margin-right:20px">Update</button>
                                </a>                                                    
                                <a href="'.route('membership-data.destroy', $record->id).'" class="btn btn-outline-danger btn-xs rounded-2" style="width:80px; margin-right:20px" data-confirm-delete="true">Delete</a>
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
            Alert::success('Success', 'Successfully updated memebrship!');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated membership!');
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $relatedTransactions = $membership->membershipTransactions()->exists();
        if ($relatedTransactions) {
            Alert::error('Error', 'Cannot delete type membership data. There are associated membership transaction!');
            return redirect()->back();
        }
        $membership->delete();
        Alert::success('Success', 'Successfully deleted type membership data!');
        return redirect()->back();

        $membership->delete();
    }


}
