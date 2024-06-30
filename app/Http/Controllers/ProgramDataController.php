<?php

namespace App\Http\Controllers;

use App\Models\ProgramData;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ProgramDataController extends Controller
{

    public function index()
    {
        $datas = ProgramData::all();
        $title = 'Delete Program Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('instructor.program_data.index', compact('datas'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("img");
            $data["image"] = $imagePath;
        }


        // Simpan data ke database
        ProgramData::create($data);

        Alert::success('Success', 'Successfully created data program');
        return redirect()->back();

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
        $totalRecords = ProgramData::count();

        // Query untuk pencarian dan pengurutan
        $query = ProgramData::select('*');

        // Penerapan pencarian jika ada nilai pencarian
        if (!empty($searchValue)) {
            $query->where(function($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
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
            $name = ucwords($record->name); 
            $description = ucwords($record->description); 

            $data_arr[] = array(
                "id" => $no,
                "name" => $name,
                "description" => $description,
                "action" => '<div class="btn-group" role="group">
                                <a href="'.route("program-data.update", ['id' => $record->id]).'" class="text-primary btn-update" data-target="#updateProgram'.$record->id.'">
                                    <button type="button" class="btn btn-outline-success btn-xs" style="width:80px; margin-right:20px">Update</button>
                                </a>
                                <a href="'.route('program-data.destroy', $record->id).'" class="btn btn-outline-danger btn-xs rounded-2" style="width:80px; margin-right:20px; display:inline;" data-confirm-delete="true">Delete</a>   
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
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Nullable untuk update
        ]);

        $data = ProgramData::findOrFail($id);
        $imageData = $data->image;
        

        if ($request->hasFile("image")) {
            if ($imageData) {
                Storage::delete($imageData);
            }
            $imagePath = $request->file("image")->store("img");
        } else {
            $imagePath = $imageData;
        }

        $validatedData["image"] = $imagePath;
        $data->update($validatedData);

        if ($data) {
            Alert::success('Success', 'Successfully updated data program');
            return redirect()->back();
        } else {
            Alert::error('Error', 'Failed updated data program');
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        // Periksa apakah ada hubungan yang terkait
        $programData = ProgramData::findOrFail($id);

        // Cek apakah ada transaksi keanggotaan yang terkait dengan program
        $relatedTransactions = $programData->programMembers()->exists();

        // Jika ada transaksi terkait, tampilkan pesan dan kembalikan
        if ($relatedTransactions) {
            Alert::error('Error', 'Cannot delete program data. There are associated program member!');
            return redirect()->back();
        }

        $programData->delete();

        Alert::success('Success', 'Successfully deleted program data!');
        return redirect()->back();
    }


}
