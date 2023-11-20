<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DriversRequest;
use App\Http\Resources\DriversResource;
use App\Http\Resources\MarketsResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\UploadImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    use UploadImage;
   public function __construct(){
        $this->middleware('checkAdmin')->except('driversOnline');
      }

      public function index()
      {
          $drivers = User::whenSearch(request()->search)
              ->where('type', 'driver')
              ->where('expiry_date','>=',Carbon::now())
              ->where('status','unblock')
              ->latest()
              ->paginate(10);



          $data = DriversResource::collection($drivers);
          return response()->api($data);
      }

      public function store(DriversRequest $request)
      {

            $data = $request->validated();

            if (isset($_FILES)){

                $image = $this->uploadImage($request, 'drivers');

                foreach ($image as $key=>$value){
                    $data[$key]= $value;
                }
            }
            $data['type'] = 'driver';
            $data['status'] = 'unblock';
            $data['market']= 'close';


            $driver = User::create($data);

            return response()->api(new DriversResource($driver),'created successfully', 201);
      }

        public function show($id)
        {

            $driver = User::where('type','driver')->find($id);
            if (!$driver){
                return response()->apiError('Not Found', 1, 404);
            }

            return response()->api(new DriversResource($driver));
        }

        public function update(DriversRequest $request, $id)
        {
            $driver = User::where('type','driver')->find($id);
            if (!$driver){
                return response()->apiError('Not Found', 1, 404);
            }
            $data = $request->validated();

            if (isset($_FILES)){
                $this->updateImage($driver);

                $image = $this->uploadImage($request, 'drivers');

                foreach ($image as $key=>$value){
                    $data[$key]= $value;
                }
            }
            $data['type'] = 'driver';

            $driver->update($data);

            return response()->api(new DriversResource($driver),'updated successfully', 200);
        }


        public function destroy($id)
        {
            $driver = User::where('type','driver')->find($id);

            if (!$driver){
                return response()->apiError('Not Found', 1, 404);
            }
            $this->deleteImage($driver);
            $driver->delete();
            return response()->apiError('deleted successfully', 0, 200);
        }

    public function expired()
    {
        //filter by type market and registration_date >= expiry_date
        $drivers = User::whenSearch(request()->search)->where('type','driver')
            ->where('expiry_date','<=',Carbon::now())
            ->latest()
            ->paginate(10);

        if (!count($drivers) > 0){
            return response()->apiError('No Data Found', 1, 404);
        }
        $data = DriversResource::collection($drivers);

        return response()->api($data);
    }

    public function driversOnline(){
        $drivers = User::where('type','driver')
            ->where('status','unblock')
            ->where('expiry_date','>=',Carbon::now())
            ->where('market','open')
            ->latest()
            ->paginate(10);


        $data = DriversResource::collection($drivers);

        return response()->api($data);
    }



}
