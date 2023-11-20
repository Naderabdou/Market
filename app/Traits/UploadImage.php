<?php
namespace App\Traits;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UploadImage
{
    //function search
      public array $test = [];

      public function uploadImage($request,$name): array
      {


            $keys = array_keys($_FILES);


                foreach ($keys as $key) {



                    if ($request->hasFile($key)){



                    $path=Storage::disk('public')->putFile($name,$request->$key);


                    $this->test[$key]=$path;
                    }
                }



          return $this->test;
      }


      public function updateImage($model):void
      {
          $keys = array_keys($_FILES);
            foreach ($keys as $key) {
                if (request()->hasFile($key)) {


                    if (File::exists('storage/' . $model->$key)) {


                        unlink('storage/' . $model->$key);

                    }

                }
            }



      }
      public function deleteImage($model):void
      {

        $keys = array_keys($model->toArray());

        //filter keys to get only images keys
        $keys = array_filter($keys, function ($key) {
            return str_contains($key, 'img');
        });



        foreach ($keys as $key) {
            //check if image not null
            if ($model->$key != null) {
                //check if image exists
                if (File::exists('storage/' . $model->$key)) {
                    //delete image
                    unlink('storage/' . $model->$key);
                }
            }
        }


      }

    public function deleteImageADS($model):void
    {


            if (File::exists('storage/' . $model->image)) {
                unlink('storage/' . $model->image);



        }



    }


}
