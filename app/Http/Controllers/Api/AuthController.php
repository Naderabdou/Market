<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //except login and register
    public function __construct()
    {
        //except auth admin

        $this->middleware('checkUser')->except('register');

    }



    public function login(UserRequest $request){


        global $credentials;
        $user=  User::where('phone', $request->phone)->first();


        if ($user){

            if ($user->type == 'user'){
                $credentials = $request->only('phone');
                $credentials['password'] = $user->phone;;

            }else{
                $credentials = $request->only('phone', 'password');
            }

            return $this->auth($credentials);

        }else{
            return response()->apiError('not register',1,400);


             //  return $this->register($request);

        }



    }
    public function register(UserRequest $request){

        $data = $request->validated();


        if ($request->password){

            $data['password'] = $request->password;

        }else{
            $data['password'] = $data['phone'];
        }

        $data['type'] = 'user';
        $data['name'] = 'user';
        $data['status'] = 'unblock';

        $user = User::create($data);


        $data = new UserResource($user);

       // $data['token']= $user->createToken('my-app-token')->plainTextToken;

        return response()->api($data,'register success');
    }

    public function logout(Request $request){
        $token = $request -> header('Authorization');
       $token= substr($token,7);

        if($token){
            try {
                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return response()->apiError('some thing went wrongs',1,400);
            }
            return  response()->apiSuccess('Logged out successfully');
        }else{
            return response()->apiError('400','some thing went wrongs');
        }
       // return response()->api(null, __('auth.logout'), 0,200);
    }

    public function user(){
        $token = request() -> header('Authorization');
        $token= substr($token,7);

        $data = new UserResource(auth()->user());
        $data['token']= $token;
        return response()->api($data,'success');
    }
    public function refreshToken(){


        return response()->api(auth('api')->refresh(),'New access token',0,200);



    }


    protected function auth($credentials)
    {
      //  $token = JWTAuth::attempt($credentials);
        try {


        if ($token=JWTAuth::attempt($credentials)){
            $user = auth()->user();


            $data = new UserResource($user);
           // $data['token'] = $user->createToken('my-app-token')->plainTextToken;
           $data['token'] = $token;
            return response()->api($data);

        }else{
            return response()->apiError( __('auth.failed'), 1, 401);
        }}
        catch (Exception $e){
            dd($e->getMessage());
            return response()->apiError($e->getMessage(), 1, 401);
        }
    }

}
