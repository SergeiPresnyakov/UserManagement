<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DBService;
use App\Http\Requests\UserCommonInfoUpdateRequest;
use App\Http\Requests\UserSecurityUpdateRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{   
    /**
     * Набор подготовленных запросов,
     * чтобы подтягивать из БД
     * только то, что нужно.
     * Например не доставать весь объект User
     * ради одной аватарки.
     * 
     * @var App\Services\DBService
     */
    private $db;

    /**
     * @var App\Models\User
     */
    private $user;

    public function __construct(DBService $db, User $user)
    {
        $this->db = $db;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->paginate(6);

        return view('index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('forms.create');
    }

    /**
     * Edit user credentials form
     * 
     * @param int $id User ID
     */
    public function security($id)
    {
        $email = $this->db->getUserEmail($id);

        return view('forms.security', compact('email', 'id'));
    }

    /**
     * Set user status form
     * 
     * @param int $id User ID
     */
    public function status($id)
    {
        $currentStatus = $this->db->getUserStatus($id);

        return view('forms.status', compact('currentStatus', 'id'));
    }

    /**
     * Change user avatar form
     * 
     * @param int $id User ID
     */
    public function media($id)
    {
        $avatar = $this->db->getUserAvatar($id);

        return view('forms.media', compact('avatar'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->find($id);

        return view('profile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->db->getForEdit($id);

        return view('forms.edit', compact('user'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserCommonInfoUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function commonInfoUpdate(UserCommonInfoUpdateRequest $request, $id)
    {
        $user = $this->user->find($id);
        $user->name = $request->name;
        $user->info->job = $request->job;
        $user->info->phone = $request->phone;
        $user->info->address = $request->address;

        $savedUser = $user->save();
        $savedInfo = $user->info->save();

        if ($savedUser && $savedInfo) {
            return back()->with('success', 'Данные обновлены');
        } else {
            return back()->withErrors('Ошибка обновления');
        }
    }

    /**
     * Update user credentials
     * 
     * @param App\Http\Requests\UserSecurityUpdateRequest $request
     * @param int $id User ID
     */
    public function securityUpdate(UserSecurityUpdateRequest $request, $id)
    {
        $user = $this->user->find($id);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            return back()->with('success', 'Учётные данные обновлены');
        } else {
            return back()->withErrors('Не удалось обновить учётные данные');
        }
    }

    /**
     * Set user status
     * 
     * @param Illuminate\Http\Request $request
     * @param int $id User ID
     */
    public function setStatus(Request $request, $id)
    {
        $user = $this->user->find($id);
        $isUpdated = $user->update(['status' => $request->status]);

        if ($isUpdated) {
            return back()->with('success', 'Статус обновлён');
        } else {
            return back()->withErrors('Не удалось сменить статус');
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
}
