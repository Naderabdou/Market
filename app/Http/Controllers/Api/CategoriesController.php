<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoriesRequest;
use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use App\Traits\UploadImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CategoriesController extends Controller
{
    use UploadImage;
   public function __construct(){
         $this->Lang();
        $this->middleware('checkAdmin')->except('show', 'index');
      }

      public function index()
      {
          $categories = Category::whenSearch(request()->search)
              ->latest()
              ->paginate(10);



          $data = CategoriesResource::collection($categories);
          return response()->api($data);
      }

      public function store(CategoriesRequest $request)
      {



            $data = $request->validated();


          if (isset($_FILES)){

              $image = $this->uploadImage($request, 'categories');

              foreach ($image as $key=>$value){
                  $data[$key]= $value;
              }
          }


          $category = Category::create($data);

            return response()->api(new CategoriesResource($category),'created successfully', 201);
      }

      public function show($id)

      {
         //   $category = Category::with('markets')->find($id)->paginate(10);
            $category = Category::with('markets')->find($id);







            if (!$category){
                return response()->apiError('Category Not Found', 1, 404);
            }

          return response()->api(new CategoriesResource($category),'0', 200);
      }


      public function update(CategoriesRequest $request, $category)
      {
          if (!$category =Category::find($category)){
              return response()->apiError('Category Not Found', 1, 404);
          }
          $data = $request->validated();

          if (isset($_FILES)){

              $this->updateImage($category);

              $image = $this->uploadImage($request, 'categories');

              foreach ($image as $key=>$value){
                  $data[$key]= $value;
              }
          }

          $category->update($data);

          return response()->api(new CategoriesResource($category),'updated successfully', 200);
      }
      private function Lang(){
          $lang = request()->header('lang');
          if (isset($lang)){

              app()->setLocale($lang);

          }
      }
}
