<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExtrasRequest;
use App\Http\Resources\ExstraResource;
use App\Models\Extra;
use App\Models\Product;
use App\Traits\UploadImage;
use App\Traits\UploadImageExtras;
use App\Traits\UploadImageProducts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtrasController extends Controller
{
    public function __construct(){
        $this->middleware('product')->except('index', 'show');
    }
    use UploadImageExtras;


    public function index()
    {
       // $extra = Extra::all();
           $extra = Extra::where('status','available')->latest()->paginate(10);
           if (!count($extra) > 0){
            return response()->apiError('extra not found', 1, 404);}
        return response()->api(ExstraResource::collection($extra),0,200);
    }

    public function store(ExtrasRequest $request)
    {
        $data= $request->except('image');
        $data_images = [];
        $new_data = [];

        if (isset($_FILES)){
            $image = $this->uploadImageExtreStore($request, 'extras');
            foreach ($image as $key=>$value){


                $data_images[] = ['image'=>$value];
            }
        }
        foreach ($data as $key=>$value) {



            if (is_array($value)){

                foreach ($value as $k=>$v){

                    $new_data[$k][$key] = $v;
                }
            }
        }

         Extra::insert($new_data);
          $extra =  Extra::where('product_id',$new_data[0]['product_id'])
           ->whereTime('created_at','<', Carbon::now()->subSeconds(5))
           ->latest()
           ->paginate(count($data_images));

            foreach ($extra as $key=>$value){

       $value->image()->create($data_images[$key]);
   }


        return response()->apiSuccess('created successfully');
    }

    public function show($id)
    {
    if (!Extra::find($id)){
        return response()->apiError('Extra not found', 1, 404);
    }

       $extra= Extra::where('id',$id)->with('images')->get();




        return response()->api(ExstraResource::collection($extra),  200);
    }

    public function update(ExtrasRequest $request, $id)
    {
;
        $extra = Extra::find($id);
        if (!$extra){
            return response()->apiError('extra not found', 1, 404);
        }
        $data = $request->except('image');
        $data_images = [];

        if ($request->file('image') ){

            $this->updateImageExtras($extra);
            $image = $this->uploadImageExtrasUpdate($request, 'extras');
            foreach ($image as $key=>$value){
                $data_images[] = ['image'=>$value];
            }
        }


        $data['product_id'] = $extra -> product_id;


        $extra->update($data);
        $extra->images()->createMany($data_images);

        return response()->api(new ExstraResource($extra), 'updated successfully', 200);
    }

    public function destroy($id)
    {
        $extra = Extra::find($id);
        if (!$extra){
            return response()->apiError('extra not found', 1, 404);
        }
        $this->deleteImageExtras($extra);
        $extra->delete();
        return response()->apiSuccess('deleted successfully');
    }


}
