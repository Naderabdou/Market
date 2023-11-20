<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MarketsRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\MarketsResource;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\UploadImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MarketsController extends Controller
{
    use UploadImage;
      public function __construct(){
        $this->middleware('checkAdmin')->except('show', 'index','ShowProductsMarket');
      }


    public function index()
    {
        $markets = User::whenSearch(request()->search)->where('type', 'market')

            ->where('expiry_date','>=',Carbon::now())
            ->where('status','unblock')
            ->whenSearch(request()->search)
             ->latest()
            ->paginate(10);







       $data = MarketsResource::collection($markets);
        return response()->api($data);
    }




    public function store(MarketsRequest $request)
    {

      //  $this->checkAdmin();
        $data = $request->validated();

        if (isset($_FILES)){

            $image = $this->uploadImage($request, 'markets');

            foreach ($image as $key=>$value){
                $data[$key]= $value;
            }

        }
        $data['type'] = 'market';
        $data['status'] = 'unblock';
        $data['market']= 'close';


        $market = User::create($data);
        if (!$market){
            return response()->apiError('Error in create market', 1, 500);
        }
        return response()->api(new MarketsResource($market),'Market created successfully', 200);
    }





    public function update(MarketsRequest $request,$market)

    {

       if (!$markets = User::where('type', 'market')->find($market)){
           return response()->apiError('Market not found', 1, 404);
       }




        $data = $request->validated();


        if (isset($_FILES)){

            $this->updateImage($markets);
            $image = $this->uploadImage($request, 'markets');

            foreach ($image as $key=>$value){
                $data[$key]= $value;
            }

        }



        $markets->update($data);

        return response()->api(new MarketsResource($markets),'Market updated successfully', 200);
    }





    public function destroy($id)
    {


        if (!$market = User::where('type', 'market')->find($id)){
        return response()->apiError('Market not found', 1, 404);
    }

        $this->deleteImage($market);
        if (!$market->delete()) {
            return response()->apiError('Error in delete market', 1, 500);
        }
        return response()->apiSuccess('Market deleted successfully', 0, 200);
    }

    public function expired()
    {

        $markets = User::whenSearch(request()->search)->where('type','market')->where('expiry_date','<=',Carbon::now())->latest()->paginate(10);
        if (!count($markets) > 0){
            return response()->apiError('No Data Found', 1, 404);
        }
        $data = MarketsResource::collection($markets);


        return response()->api($data);
    }

    public function show($id)
    {


        if (!$market = User::where('type', 'market')->with('products')->find($id)){
            return response()->apiError('Market not found', 1, 404);
        }
        return response()->api(new MarketsResource($market));
    }
    public function ShowProductsMarket($market)
    {
        $products = User::where('type', 'market')
            ->find($market)
            ->products()
             ->whenSearch(request()->search)
            ->latest()
            ->paginate(5);





        if (!count($products) > 0){
            return response()->apiError('No Products', 1, 404);
        }
        return response()->api(ProductsResource::collection($products));

    }







}
