<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdsRequest;
use App\Http\Resources\AdsResource;
use App\Models\Ads;
use App\Traits\UploadImage;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    use UploadImage;
   public function __construct()
   {
       $this->middleware('product')->except('show','index');
   }
   public function index()
   {

       $ads = Ads::where('status','active')->latest()->paginate(10);

       return response()->api(AdsResource::collection($ads));
   }

    public function show($id)
    {
        $ads=Ads::find($id);
        if (!$ads){
            return response()->apiError('Ads not found',null,404);

        }
         return response()->json(new AdsResource($ads));
    }

    public function store(AdsRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')){
            $image = $this->uploadImage($request, 'ads');

            foreach ($image as $key=>$value){
                $data[$key]= $value;
            }
        }
        $data['user_id']=auth()->user()->id;

        $ads = Ads::create($data);
        return response()->api(new AdsResource($ads),'created successfully',0,201);
    }

    public function update(AdsRequest $request, $id)
    {
        $ads = Ads::find($id);
        if (!$ads){
            return response()->apiError('Ads not found',1, 404);

        }
        $data = $request->validated();
        if ($request->hasFile('image')){
            $this->updateImage($ads);
            $image = $this->uploadImage($request, 'ads');

            foreach ($image as $key=>$value){
                $data[$key]= $value;
            }
        }
        $data['user_id']=$ads->user_id;

        $ads->update($data);
        return response()->api(new AdsResource($ads),'updated successfully', 200);
    }

    public function destroy($id)
    {
        $ads = Ads::find($id);
        if (!$ads){
            return response()->apiError('Ads not found',null,404);

        }
        $this->deleteImageADS($ads);
        $ads->delete();
        return response()->apiSuccess('Ads deleted successfully', 0, 200);
    }
    public function allAdsUser(){
         $ads= Ads::where('user_id',auth()->user()->id)->latest()->paginate(10);
         return response()->api(AdsResource::collection($ads));
    }


}
