<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoriesRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class MainCategoriesController extends Controller
{
    public function index()
    {
            $default = get_default_lang();
            $mainCategories = MainCategory::where('translation_lang' , $default)->Selection()->get();
            return view('admin.mainCategories.index' , compact('mainCategories'));
    }


    public function create()
    {
        return view('admin.mainCategories.create');
    }

    public function store(MainCategoriesRequest $request)
    {
        try{

            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['translation_lang'] == get_default_lang();
            });

            $default_category = array_values($filter->all())[0];
            $filePath = "";
            if ($request->has('photo')) {

                $filePath = uploadImage('maincategories', $request->photo);
            }


            DB::beginTransaction();


            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['translation_lang'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);



            $Categories = $main_categories->filter(function ($value, $key) {
                return $value['translation_lang'] != get_default_lang();
            });

            if (isset($Categories) && $Categories->count())
            {
                $Categories_arr = [];
                foreach($Categories as $Category)
                {
                    $Categories_arr[]=[
                        'translation_lang' => $Category['translation_lang'],
                        'translation_of' => $default_category_id,
                        'name' => $Category['name'],
                        'slug' => $Category['name'],
                        'photo' => $filePath
                    ];
                }

                MainCategory::insert($Categories_arr);
            }

            DB::commit();

            return  redirect()->route("admin.maincategories")->with(["success"=>"تم حفظ اللغة بنجاح"]);
        }
        catch(Exception $exception)
        {
            DB::rollback();
             return redirect()->route("admin.maincategories")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);
        }


    }

    public function edit($mainCategory_id)
    {

        $mainCategory = MainCategory::with('categories')->selection()->findOrFail($mainCategory_id);
        if(!$mainCategory)

             return redirect()->route("admin.maincategories")->with(["error"=>"هذا القسم غير موجود "]);
        else
            return view('admin.maincategories.edit', compact('mainCategory'));
    }

    public function update($mainCategory_id ,  MainCategoriesRequest $request)
    {
        try{
            $mainCategory = MainCategory::selection()->findOrFail($mainCategory_id);
            if(!$mainCategory)

            return redirect()->route("admin.maincategories")->with(["error"=>"هذا القسم غير موجود "]);

            $category_update = array_values($request->category)[0];

                if(!$request->has('category.0.active'))
                    $request->request->add(['active'=> 0]);
                else
                    $request->request->add(['active'=> 1]);


               $main= MainCategory::where('id' , $mainCategory_id)
                    ->update([
                        'name' => $category_update['name'] ,
                        'active' => $request->active ,
                        ]);

                if($request->has('photo'))
                {
                    $filePath = uploadImage('maincategories', $request->photo);
                    MainCategory::where('id' ,$mainCategory_id)
                    ->update([
                        'photo'=> $filePath,
                    ]);
                    $image = Str::after($mainCategory->photo , 'assets/'); //cut string
                    $image = base_path('assets/'.$image);  // get the path of basic app
                    unlink($image);
                }

            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
            }
            catch(Exception $exception)
            {
                return redirect()->route("admin.maincategories")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);
            }

    }
    public function destroy( $id)
    {
        try {
            $Category = MainCategory::findOrFail($id);

            if(!$Category)
            {
                return redirect()->route("admin.maincategories")->with(["error"=>"هذة القسم غير موجودة"]);
            }

            $vendor = $Category->vendors();
            if(isset($vendor) && $vendor->count()>0)
            {
                return redirect()->route("admin.maincategories")->with(["error"=>"لا يمكن حذف هذا القسم"]);
            }

                $image = Str::after($Category->photo , 'assets/'); //cut string
                $image = base_path('assets/'.$image);  // get the path of basic app
                unlink($image);              // delete photo from directory

                $Category->categories()->delete();  // delete all translations
                $Category->delete();
                return redirect()->route("admin.maincategories")->with(["success"=>"تم حذف القسم بنجاح"]);

        }
        catch (Exception $exception)
        {
            return redirect()->route("admin.maincategories")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);

        }

    }

     public function status($id)
     {
         try {
             $mainCategory = MainCategory::findOrFail($id);
             if (!$mainCategory)
             return redirect()->route("admin.maincategories")->with(["error"=>"هذا القسم غير موجود"]);

             $status = $mainCategory->active == 0 ? 1 : 0 ;

             $mainCategory->update(['active'=>$status]);
             return redirect()->route("admin.maincategories")->with(["success"=>"تم تغيير حالة القسم بنجاح"]);

         }
         catch (\Exception $ex)
         {
             return redirect()->route("admin.maincategories")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);
         }


     }

}
