<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["required " , "max:100" , "string"],
            "abbr"=>["required" , "string" ,"max:10"],
            "direction"=>["required" , "in:rtl,ltr"],
            "active"=>["in:0,1"]
        ];
    }

    public function messages()
    {
        return[
            "required" =>"this field is required",
            "direction.in" => "direction must be rtl or ltr",
            "active.in" =>"active must be 0 or 1",
            "name.string" => "name must be characters",
            "name.max" => "name can't be max about 100 characters",
            "abbr.string"=>"abbr must be characters",
            "abbr.max"=>" abbr can't be max about 100 characters",
        ];
    }
}
