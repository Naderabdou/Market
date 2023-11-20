<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductsRequest;
use App\Http\Resources\ProductsResource;
use App\Models\Extra;
use App\Models\Product;
use App\Traits\UploadImageExtras;
use App\Traits\UploadImageProducts;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use UploadImageProducts;
    use UploadImageExtras;
    public function __construct(){
        $this->lang();
        $this->middleware('product')->except('index', 'show');
    }

    public function index()
    {
        $products = Product::whenSearch(request()->search)
         ->where('status','available')
         ->latest()
         ->paginate(10);


        $data= ProductsResource::collection($products);

        return response()->api($data);
    }



    public function store(ProductsRequest $request)
    {


       $data= $request->except('image');


        $data_images = [];
        if (isset($_FILES)){
            $image = $this->uploadImagePrdoucts($request, 'products');
            foreach ($image as $key=>$value){


                $data_images[] = ['image'=>$value];
            }
        }


        $product = Product::create($data);

       $product->images()->createMany($data_images);


        return response()->api(new ProductsResource($product));
    }

    public function update(ProductsRequest $request, $id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->apiError('product not found', 1, 404);
        }
        $data = $request->except('image');
        $data_images = [];
        if (isset($_FILES)){
            $this->updateImagePrdoucts($product);
            $image = $this->uploadImagePrdoucts($request, 'products');
            foreach ($image as $key=>$value){
                $data_images[] = ['image'=>$value];
            }
        }

        $product->update($data);
        $product->images()->createMany($data_images);
        $data = new ProductsResource($product);
        return response()->api($data);
    }
    public function show($id)
    {

        $product = Product::with('images')->find($id);

        if (!$product){
            return response()->apiError('product not found', 1, 404);
        }
        $data = new ProductsResource($product);
        return response()->api($data);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->apiError('product not found', 1, 404);
        }
        $this->deleteImageProducts($product);
        $product->delete();
        return response()->apiSuccess('product deleted successfully', 0, 200);
    }

    public function statusAvailable($id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->apiError('product not found', 1, 404);
        }
        if ($product->status == 'available'){
            return response()->apiError('product status already available', 1, 404);
        }

        $product->update(['status'=>'available']);
        return response()->apiSuccess('product status available successfully', 0, 200);
    }

    public function statusUnavailable($id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->apiError('product not found', 1, 404);
        }
        if ($product->status == 'unavailable'){
            return response()->apiError('product status already unavailable', 1, 404);
        }

        $product->update(['status'=>'unavailable']);
        return response()->apiSuccess('product status unavailable successfully', 0, 200);
    }

    public function allProductsAvailable()
    {
        $products = Product::where('status','available')
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(10);


        $data= ProductsResource::collection($products);

        return response()->api($data);
    }
    public function allProductsUnavailable(){
        $products = Product::where('status','unavailable')
            ->whenSearch(request()->search)
            ->latest()
            ->paginate(10);

        $data= ProductsResource::collection($products);

        return response()->api($data);
    }

    private function Lang(){
        $lang = request()->header('lang');
        if (isset($lang)){

            app()->setLocale($lang);

        }
    }



}
