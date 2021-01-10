<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::selection()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index',compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(LanguageRequest $request)
    {
        try {
            if(!$request->has('active') )
                $request->request->add(['active'=>0]);
            else
                $request->request->add(['active'=>1]);
            Language::create($request->except('_token'));
            return redirect()->route("admin.languages")->with(["success"=>"تم حفظ اللغة بنجاح"]);
        } catch (Exception $exception)
        {
            return redirect()->route("admin.languages")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);

        }


    }

    public function edit($id)
    {
        $language = Language::find($id);
        if(!$language)
        {
            return redirect()->route("admin.languages")->with(["error"=>"هذة اللغة غير موجودة"]);
        }
        return view('admin.languages.edit',compact("language"));
    }

    public function update(LanguageRequest $request , $id)
    {
        try {
            $language = Language::find($id);

            if(!$language)
                {
                    return redirect()->route("admin.languages")->with(["error"=>"هذة اللغة غير موجودة"]);
                }
            if(!$request->has('active') )
                    $request->request->add(['active'=>0]);
            else
                $request->request->add(['active'=>1]);

            $language->update($request->except('_token'));
                return redirect()->route("admin.languages")->with(["success"=>"تم تحديث اللغة بنجاح"]);
             }
        catch (\Exception $exception)
           {
            return redirect()->route("admin.languages")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);

           }

    }

    public function destroy( $id)
    {
        try {
            $language = Language::find($id);

            if(!$language)
            {
                return redirect()->route("admin.languages")->with(["error"=>"هذة اللغة غير موجودة"]);
            }
            $language->delete();
            return redirect()->route("admin.languages")->with(["success"=>"تم حذف اللغة بنجاح"]);
        }
        catch (Exception $exception)
        {
            return redirect()->route("admin.languages")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);

        }

    }


}
