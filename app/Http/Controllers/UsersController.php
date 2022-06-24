<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    protected $menu = 'user';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::orderBy('id', 'DESC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $user
        ];
        return view('user.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'menu' => $this->menu,
            'title' => 'add',
        ];
        return view('user.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:64',
            'email' => 'required|email:dns|unique:users,email,NULL,id,deleted_at,NULL|max:64',
            'password' => 'required|max:128',
            'roles' => 'required|max:64',
            'foto' => 'mimes:png,jpeg,jpg|max:2048',
        ]);
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->roles = $request->roles;
            $user->password = bcrypt($request->password);
            // upload file
            if ($request->file()) {
                $file = $request->file('foto');
                $fileName = Carbon::now()->format('ymdhis') . '_' . Auth::user()->id . '_' . str::random(25) . '.' . $file->extension();
                $user->foto = $fileName;
                $file->move(public_path('files/foto'), $fileName);
            }
            $user->status = 'Aktif';
            $user->save();

            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('user');
        } catch (\Throwable $err) {
            DB::rollBack();
            throw $err;
            AlertHelper::addAlert(false);
            return back();
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
        $data = [
            'menu' => $this->menu,
            'title' => 'edit',
            'item' => User::findorfail(Crypt::decryptString($id))
        ];
        return view('user.edit')->with($data);
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
        $decrypted_id = Crypt::decryptString($id);
        $validated = $request->validate([
            'name' => 'required|max:64',
            'email' => "required|email:dns|max:64|unique:users,email,$decrypted_id,id,deleted_at,NULL",
            'password' => 'required|max:128',
            'roles' => 'required|max:64',
            'status' => 'required',
            'foto' => 'mimes:png,jpeg,jpg|max:2048',
        ]);
        DB::beginTransaction();
        try {
            $user = User::findOrFail($decrypted_id);
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if ($request->password_old != $request->password) {
                $user->password = bcrypt($validated['password']);
            }
            $user->roles = $validated['roles'];
            // upload file
            if ($request->file()) {
                $file = $request->file('foto');
                $fileName = Carbon::now()->format('ymdhis') . '_' . Auth::user()->id . '_' . str::random(25) . '.' . $file->extension();
                $user->foto = $fileName;
                $file->move(public_path('files/foto'), $fileName);
                // hapus file yg sebelumnya
                $file_path = public_path() . '/files/foto/' . $request->file_old;
                unlink($file_path);
            }
            $user->status = $validated['status'];
            $user->save();
            DB::commit();
            AlertHelper::updateAlert(true);
            return redirect('user');
        } catch (\Throwable $err) {
            DB::rollBack();
            AlertHelper::updateAlert(false);
            return back();
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
        DB::beginTransaction();
        try {
            $vendor = User::findOrFail(Crypt::decryptString($id));
            $vendor->delete();
            DB::commit();
            AlertHelper::deleteAlert(true);
            return back();
        } catch (\Throwable $err) {
            DB::rollBack();
            AlertHelper::deleteAlert(false);
            return back();
        }
    }

    public function profile(Request $request, $id)
    {
        $data = [
            'menu' => $this->menu,
            'title' => 'profile',
            'item' => User::findorfail(Crypt::decryptString($id))
        ];
        return view('user.profile')->with($data);
    }

    public function update_profile(Request $request, $id)
    {
        $decrypted_id = Crypt::decryptString($id);
        $validated = $request->validate([
            'name' => 'required|max:64',
            'email' => "required|email:dns|max:64|unique:users,email,$decrypted_id,id,deleted_at,NULL",
            'password' => 'required|max:128',
            'foto' => 'mimes:png,jpeg,jpg|max:2048',
        ]);
        DB::beginTransaction();
        try {
            $user = User::findOrFail($decrypted_id);
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if ($request->password_old != $request->password) {
                $user->password = bcrypt($validated['password']);
            }
            // upload file
            if ($request->file()) {
                $file = $request->file('foto');
                $fileName = Carbon::now()->format('ymdhis') . '_' . Auth::user()->id . '_' . str::random(25) . '.' . $file->extension();
                $user->foto = $fileName;
                $file->move(public_path('files/foto'), $fileName);
                // hapus file yg sebelumnya
                if ($request->file_old) {
                    $file_path = public_path() . '/files/foto/' . $request->file_old;
                    unlink($file_path);
                }
            }
            $user->save();
            DB::commit();
            AlertHelper::updateAlert(true);
            return back();
        } catch (\Throwable $err) {
            DB::rollBack();
            AlertHelper::updateAlert(false);
            return back();
        }
    }
}
