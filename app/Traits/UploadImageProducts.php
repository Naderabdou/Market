<?php
namespace App\Traits;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UploadImageProducts
{
    //function search
      public array $test = [];

      public function uploadImagePrdoucts($request,$name): array

      {
       foreach ($request->file('image') as $key=>$value){

           if ($key == 3) break;
           $path=Storage::disk('public')->putFile($name,$value);
           $this->test[$key]=$path;};



          return $this->test;
      }




      public function updateImagePrdoucts($model):void
      {


           if (request()->hasFile('image')){

           foreach ($model->images as $image){



         if (File::exists('storage/'.$image->image)){
             unlink('storage/'.$image->image);
             $image->delete();


             }
          }


    }



      }
      public function deleteImageProducts($model):void
      {

          foreach ($model->images as $image) {

              if (File::exists('storage/' . $image->image)) {

                  unlink('storage/' . $image->image);
                    $image->delete();

              }
          }



      }

}
