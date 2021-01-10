<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index' , compact('vendors'));
    }

    public function create()
    {
        $categories = MainCategory::where('translation_of' , 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorRequest $request)
    {
        try {
            $filePath ="";
            $request->has('active')?$request->request->add(['active'=>1]) : $request->request->add(['active'=>0]);
            if($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }
            $vendor = Vendor::create([
                'name' =>$request->name,
                'logo' =>$filePath,
                'mobile' =>$request->mobile,
                'category_id' =>$request->category_id,
                'email' =>$request->email,
                'active' =>$request->active,
                'address' => $request->address,
                'password' => $request->password,
                'longitude'=>$request->longitude,
                'latitude'=>$request->latitude,
            ]);
            Notification::send($vendor, new VendorCreated($vendor));
            return redirect()->route('admin.Vendors')->with(['success'=>"تم حفظ اللغة بنجاح"]);
        }
        catch (\Exception $ex)
        {
            return redirect()->route("admin.Vendors")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);
        }


    }


    public function edit($id)
    {
        try {

            $categories = MainCategory::where('translation_of' , 0)->Active()->get();
        $vendor = Vendor::selection()->findOrFail($id);
        if(!$vendor)
            return redirect()->route("admin.Vendors")->with(["error"=>"هذا التاجر غير موجود او قد يكون محذوفاً"]);

        return view('admin.vendors.edit' , compact('vendor','categories'));
        }
        catch (\Exception $ex)
        {
            return redirect()->route("admin.Vendors")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);

        }

    }

    public function update($id, VendorRequest $request)
    {
        try {
            $vendor = Vendor::selection()->findOrFail($id);
            if(!$vendor)
                return redirect()->route("admin.Vendors")->with(["error"=>"هذا التاجر غير موجود او قد يكون محذوفاً"]);

            DB::beginTransaction();
            if($request->has('logo'))
            {
                $filepath = uploadImage('vendors' , $request->logo);

                $vendor->where('id' , $id)
                    ->update([
                        'logo' => $request->$filepath,
                    ]);
                $image = Str::after($vendor->logo , 'assets/'); //cut string
                $image = base_path('assets/'.$image);  // get the path of basic app
                unlink($image);
            }

            $data=$request->except('_token' , 'password' , 'photo','id');
            if($request->has('password')) {
                $data['password'] = $request->password;
            }

            $vendor->where('id' , $id)
                ->update($data);
            DB::commit();
            return redirect()->route('admin.Vendors')->with(['success'=>"تم حفظ اللغة بنجاح"]);


        } catch (\Exception $exception)
        {
            DB::rollback();
            return redirect()->route("admin.Vendors")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);

        }
    }

    public function status($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor)
            return redirect()->route("admin.Vendors")->with(["error"=>"هذا التاجر غير موجود او قد يكون محذوفاً"]);

            $status =$vendor->active == 0 ? 1 : 0 ;
            $vendor->update(['active'=>$status]);
            return redirect()->route("admin.Vendors")->with(["success"=>"تم تغيير حالة القسم بنجاح"]);
        }
        catch (\Exception $ex)
        {
            return redirect()->route("admin.Vendors")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);
        }
    }

    public function destroy($id)
    {
        try {
            $vendor =Vendor::find($id);
            if (!$vendor)
                return redirect()->route("admin.Vendors")->with(["error"=>"هذا التاجر غير موجود او قد يكون محذوفاً"]);

            $image = Str::after($vendor->logo , 'assets/');//cut string
            $image = base_path('assets/'.$image);  // get the path of basic app
            unlink($image);              // delete photo from directory

            $vendor->delete();
            return redirect()->route("admin.Vendors")->with(["success"=>"تم حذف التاجر بنجاح"]);

        }
        catch (\Exception $ex)
        {
            return redirect()->route("admin.Vendors")->with(["error"=>"يرجي اعادة المحاولة فبما بعد"]);
        }


    }

}
