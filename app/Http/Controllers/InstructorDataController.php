<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\SearchService;

class InstructorDataController extends Controller
{
    protected $searchService;

    public function __construct (SearchService $searchService) 
    {
        $this->searchService = $searchService;
    }
    public function index()
    {
        $users = User::where('role', 'GUEST')->cursor();
        $instructor = User::where('role', 'INSTRUCTOR')->cursor();
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
            return redirect()->back()->with('success', 'Data Instructor berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Nama yang dipilih tidak ditemukan.');
        }
    }
    public function destroy($id)
    {
        $instructor = User::where("id", $id);

        $instructor->update(['role' => 'GUEST']);

        if ($instructor) {
            return redirect()
                ->intended("/instructor-data/index")
                ->with(["sucesss" => "Successfully Deleted Instructor"]);
        } else {
            return redirect()
                ->intended("/instructor-data/index")
                ->with(["sucesss" => "Failed Deleted Instructor"]);
        }
    }

    public function search(Request $request)
    {
        $instructor = $this->searchService->handle($request, new User(), ['name','date_of_birth', 'gender','email'])
            ->where('role', 'instructor')
            ->paginate(10)
            ->withQueryString()
            ->withPath('instructor-data-index');

        return view('admin.instructor_data.table', [
            'instructor' => $instructor,
        ]);
    }
}
