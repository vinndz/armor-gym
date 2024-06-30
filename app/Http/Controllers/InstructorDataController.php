<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class InstructorDataController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'GUEST')->cursor();
        $instructor = User::where('role', 'INSTRUCTOR')->cursor();

        $title = 'Inactive Instructor!';
        $text = "Are you sure you want to inactive this user?";
        confirmDelete($title, $text);
        return view('admin/instructor_data/index', compact('users', 'instructor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if($user) {
            $user->update(['role' => 'INSTRUCTOR']);
            Alert::success('Success', 'Successfully created instructor');
            return redirect()->back();
        } else {
            Alert::success('Success', 'Failed created supplement');
            return redirect()->back();
        }
    }
    public function destroy($id)
    {
        $instructor = User::where("id", $id);

        $instructor->update(['role' => 'GUEST']);

        if ($instructor) {
            Alert::success('Success', 'Successfully inactive instructor');
            return redirect()->back();
        } else {
            Alert::success('error', 'Failed inactive instructor');
            return redirect()->back();
        }
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
        $totalRecords = User::where('role', 'instructor')->count();


        // Query untuk pencarian dan pengurutan
        $query = User::select('*');

        $query->where('role', 'instructor');

        // Penerapan pencarian jika ada nilai pencarian
        if (!empty($searchValue)) {
            $query->where(function($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhereRaw("DATE_FORMAT(date_of_birth, '%d-%m-%Y') LIKE ?", ["%" . $searchValue."%"])
                    ->orWhere('gender', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
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
            $date_of_birth = $record->date_of_birth; 
            $gender = ucwords($record->gender); 
            $email = $record->email; 

            $data_arr[] = array(
                "id" => $no,
                "name" => $name,
                "date_of_birth" => $date_of_birth,
                "gender" => $gender,
                "email" => $email,
                "action" => '<div class="btn-group" role="group">                                                  
                                <a href="'.route('instructor-data.destroy', $record->id).'" class="btn btn-outline-danger btn-xs" style="width:80px; margin-right:20px" data-confirm-delete="true">Inactive</a>
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

}
