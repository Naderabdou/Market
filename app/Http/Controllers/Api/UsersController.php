<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriversResource;
use App\Http\Resources\MarketsResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('checkAdmin')->except('open', 'close');
    }

    public function close($id){
        $user = User::wherein('type',['market','driver'])->find($id);
        if (!$user){
            return response()->apiError('user not found', 1, 404);
        }
        if ($user->market == 'close'){
            return response()->apiError('user already closed', 1, 404);
        }
        $user->update(['market' => 'close']);
        return response()->apiSuccess('user closed successfully', 0, 200);

    }
    public function open($id){
        $user = User::wherein('type',['market','driver'])->find($id);
        if (!$user){
            return response()->apiError('user not found', 1, 404);
        }
        if ($user->market == 'open'){
            return response()->apiError('user already opened', 1, 404);
        }
        $user->update(['market' => 'open']);
        return response()->apiSuccess('user opened successfully', 0, 200);

    }

    public function block($id)
    {
        $user = User::find($id);
        if (!$user){
            return response()->apiError('User not found', 1, 404);
        }
        if ($user->status == 'block'){
            return response()->apiError('User already blocked', 1, 404);
        }
        $user->update(['status' => 'block']);
        return response()->apiSuccess('User blocked successfully', 0, 200);
    }

    public function unblock($id)
    {


        $user = User::find($id);
        if (!$user){
            return response()->apiError('User not found', 1, 404);
        }
        if ($user->status == 'unblock'){
            return response()->apiError('User already unblocked', 1, 404);
        }
        $user->update(['status' => 'unblock']);
        return response()->apiSuccess('User unblocked successfully', 0, 200);
    }


    public function AllBlockUsers()
    {
        $users = User::where('status', 'block')->where('type','user')
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(10);



        return response()->api(UsersResource::collection($users));
    }
    public function AllBlockMarkets()
    {
        $users = User::where('status', 'block')
            ->where('type','market')
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(10);





        return response()->api(MarketsResource::collection($users));
    }
    public function AllBlockDrivers()
    {
        $users = User::where('status', 'block')
            ->where('type','driver')
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(10);



        return response()->api(DriversResource::collection($users));
    }


    public function AllUsers()
    {
        $users = User::where('type', 'user')
            ->when('status','unblock')
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(10);


        return response()->api(UsersResource::collection($users));
    }

}
