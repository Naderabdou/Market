<?php
namespace App\Traits;


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UploadImageExtras
{
    //function search
      public array $tests = [];

    public function uploadImageExtreStore($request,$name): array

    {
        foreach ($request->file('image') as $key=>$value){

            if ($key == count($request->file('image'))) break;
            $path=Storage::disk('public')->putFile($name,$value);
            $this->test[$key]=$path;
        };



        return $this->test;
    }


    public  function uploadImageExtrasUpdate($request,$name): array

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

    public function updateImageExtras($model):void
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

    public function deleteImageExtras($model):void
      {
          foreach ($model->images as $image) {
              if (File::exists('storage/' . $image->image)) {
                  unlink('storage/' . $image->image);
                    $image->delete();

              }
          }



      }

}
