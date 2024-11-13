<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use App\Models\{
    User,
};

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $user;

    public function __construct()
    {
        $this->user         = new User;
    }

    public function index()
    {
        $data = [
            'title'     => 'Daftar User',
        ];

        return view('user/v_index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'                  => 'required',
                'email'                 => 'required',
                'password'              => 'required',
                'is_active'             => 'required',
            ],
            [
                'name.required'         => 'Nama user wajib diisi',
                'email.required'        => 'Email wajib diisi',
                'password.required'     => 'Password wajib diisi',
                'is_active.required'    => 'Aktif wajib diisi',
            ]
        );

        $save = $this->user->create([
            'id'                => str::uuid(),
            'name'              => $request->input('name'),
            'email'             => $request->input('email'),
            'password'          => Hash::make($request->input('password')),
            'is_active'         => $request->input('is_active'),
        ]);

        if ($save) {
            return response()->json([
                'status'        => 'success',
                'message'       => 'Data berhasil disimpan'
            ], 201);
        } else {
            return response()->json([
                'status'        => 'error',
                'message'       => $save->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->user::where('id', $id)->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $password = $request->input('password');

        $request->validate(
            [
                'name'                  => 'required',
                'email'                 => 'required',
                'is_active'             => 'required',
            ],
            [
                'name.required'         => 'Nama user wajib diisi',
                'email.required'        => 'Email wajib diisi',
                'is_active.required'    => 'Aktif wajib diisi',
            ]
        );

        if ($password) {
            $update = $this->user->where('id', $request->id)->update([
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'password'          => Hash::make($request->input('password')),
                'is_active'         => $request->input('is_active'),
            ]);
        } else {
            $update = $this->user->where('id', $request->id)->update([
                'name'              => $request->input('name'),
                'email'             => $request->input('email'),
                'is_active'         => $request->input('is_active'),
            ]);
        }

        if ($update) {
            return response()->json([
                'status'        => 'success',
                'message'       => 'Data berhasil diperbarui'
            ], 201);
        } else {
            return response()->json([
                'status'        => 'error',
                'message'       => 'Data gagal diperbarui',
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function json()
    {
        $query = User::orderby('name', 'ASC')
            ->get();

        $user = DataTables::of(
            $query
        )->make(true);

        return $user;
    }
}
