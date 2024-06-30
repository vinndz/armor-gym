<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suplement;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
class SuplementController extends Controller
{
    public function index()
    {
        $suplements = Suplement::all();

        $title = 'Delete Suplement Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('admin/suplement_data/index', compact('suplements'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'name' => 'required|unique:suplements',
            'stock' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("img");
            $data["image"] = $imagePath;
        }


        // Simpan data ke database
        Suplement::create($data);

        Alert::success('Success', 'Successfully created supplement');
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
        $totalRecords = Suplement::count();

        // Query untuk pencarian dan pengurutan
        $query = Suplement::select('*');

        // Penerapan pencarian jika ada nilai pencarian
        if (!empty($searchValue)) {
            $query->where(function($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('stock', 'like', '%' . $searchValue . '%')
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
            $name = $record->name; 
            $stock = $record->stock; 
            $price = $record->price; 
            $description = $record->description; 

            $data_arr[] = array(
                "id" => $no,
                "name" => $name,
                "stock" => $stock,
                "price" => $price,
                "description" => $description,
                "action" => '<div class="btn-group" role="group">
                                <a href="'.route("suplement-data.update", ['id' => $record->id]).'" class="text-primary btn-update" data-target="#updateSuplement'.$record->id.'">
                                <button type="button" class="btn btn-outline-success btn-xs" style="width:80px; margin-right:20px">Update</button>
                                </a>

                                <a href="'.route('suplement-data.destroy', $record->id).'" class="btn btn-outline-danger btn-xs rounded-2" style="width:80px; margin-right:20px; display:inline;" data-confirm-delete="true">Delete</a>
                                
                                </div>',
                                // <a href="'.route("suplement-data.destroy", ['id' => $record->id]).'" class="text-primary btn-xs" data-confirm-delete="true">
                                //     <button type="button" class="btn btn-outline-danger btn-xs" style="width:80px; margin-right:20px">Delete</button>
                                // </a>
                                
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
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'stock' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar bersifat opsional
        ]);


        $suplement = Suplement::find($id);
        $imageSuplement = $suplement->image;

        if ($request->hasFile("image")) {
            if ($imageSuplement) {
                Storage::delete($imageSuplement);
            }
            $imagePath = $request->file("image")->store("img");
        } else {
            $imagePath = $imageSuplement;
        }

        $validatedData["image"] = $imagePath;
        $suplement->update($validatedData);

        if ($suplement) {
            Alert::success('Success', 'Successfully updated supplement');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated supplement');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $suplement = Suplement::findOrFail($id);
        $relatedTransactions = $suplement->transactions()->exists();
        if ($relatedTransactions) {
            Alert::error('Error', 'Cannot delete type suplement data. There are associated suplement transaction!');
            return redirect()->back();
        }
        Storage::delete($suplement->image);
        $suplement->delete();

        Alert::success('Success', 'Successfully deleted type suplement data!');
        return redirect()->back();
    }


    // public function destroy($id)
    // {
    //     $suplement = Suplement::findOrFail($id);
    //     $relatedTransactions = $suplement->transactions()->exists();
    //     if ($relatedTransactions) {
    //         Alert::error('Error', 'Cannot delete type suplement data. There are associated suplement transaction!');
    //         return redirect()->back();
    //     }
    //     $suplement->delete();
    //     Alert::success('Success', 'Successfully deleted type suplement data!');
    //     return redirect()->back();

    // }

    


}
