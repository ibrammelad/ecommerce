@extends('layouts.admin')

@section('content')



    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">الرئيسية </a>
                                </li>

                                <li class="breadcrumb-item"><a href="{{route('admin.languages')}}"> الاقسام الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item active"> تعديل - {{$mainCategory->name}}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form">  تعديل - {{$mainCategory->name}} </h4>
                                    <a class="heading-elements-toggle"><i
                                            class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                @include('admin.includes.alerts.success')
                                @include('admin.includes.alerts.errors')
                                <div class="card-content collapse show">
                                    <div class="card-body">

                                        <form class="form" action="{{route("admin.maincategories.update" , $mainCategory->id)}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method("PATCH")
                                        <input type="hidden" name="id"  value="{{bcrypt($mainCategory->id)}}">
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <img
                                                        src="{{$mainCategory -> photo}}"
                                                        class="rounded-circle  height-150" alt="صورة القسم  ">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label> صورة القسم </label>
                                                <input type="file" name="photo" size="auto">
                                                @error('photo')
                                                <span class="text-danger">هذا الحقل مطلوب</span>
                                                @enderror
                                            </div>
                                            <div class="form-body">
                                                <h4 class="form-section"><i class="ft-home"></i> بيانات  القسم </h4>

                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> اسم القسم {{__('messages.'.$mainCategory->translation_lang)}} </label>
                                                            <input type="text" value="{{$mainCategory->name}}" id="name"
                                                                   class="form-control"
                                                                   placeholder=""
                                                                   name="category[0][name]">
                                                            @error("category.0.name")
                                                            <span class="text-danger">هذا الحقل مطلوب</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 hidden ">
                                                        <div class="form-group">
                                                        <label for="projectinput1">أختصار اللغة {{__('messages.'.$mainCategory->translation_lang)}}</label>
                                                            <input type="text" value="{{$mainCategory->translation_lang}}" id="name"
                                                                   class="form-control"
                                                                   placeholder=""
                                                                   name="category[0][translation_lang]">
                                                            @error("category.0.translation_lang")
                                                            <span class="text-danger">هذا الحقل مطلوب </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mt-1">
                                                            <input type="checkbox"  value="1" name="category[0][active]"
                                                                   id="switcheryColor4"
                                                                   class="switchery" data-color="success"
                                                                   @if ($mainCategory -> active == 1)checked @endif/>
                                                            <label for="switcheryColor4"
                                                        class="card-title ml-1">الحالة {{__('messages.'.$mainCategory->translation_lang)}}</label>

                                                            @error("category.0.active")
                                                            <span class="text-danger">هذا الحقل مطلوب</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="button" class="btn btn-warning mr-1 ml-2"
                                                        onclick="history.back();">
                                                    <i class="ft-x"></i> تراجع
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> تعديل
                                                </button>
                                            </div>
                                        </form>




                                    </div>
                                </div>
                                <ul class="nav nav-tabs">
                                    @isset($mainCategory->categories)
                                        @foreach ( $mainCategory->categories as $index => $translation )

                                    <li class="nav-item">
                                    <a class="nav-link {{ $index == 0 ? 'active' :'' }}" id="homeLabel" data-toggle="tab"
                                    href="#homeLabel{{$index}}"
                                    aria-controls="homeLabel" aria-expanded="{{$index == 0 ? 'true' : 'false'}}">{{__("messages.".$translation->translation_lang)}}</a>
                                    </li>

                                    @endforeach
                                @endisset
                                </ul>
                            <div class="tab-content px-1 pt-1">
                                @isset($mainCategory->categories)
                                @foreach ( $mainCategory->categories as $index => $translation )

                                <div class="tab-pane {{ $index == 0 ? 'active' :'' }}"
                                id="homeLabel{{$index}}" aria-labelledby="homeLabel"
                                aria-expanded="{{$index == 0 ? 'true' : 'false'}}" role="tabpanel">
                                <form class="form" action="{{route("admin.maincategories.update" , $translation->id)}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method("PATCH")
                                    <input type="hidden" name="id"  value="{{encrypt($translation->id)}}">
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="ft-home"></i> بيانات  القسم </h4>

                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="projectinput1"> اسم القسم {{__('messages.'.$translation->translation_lang)}} </label>
                                                    <input type="text" value="{{$translation->name}}" id="name"
                                                           class="form-control"
                                                           placeholder=""
                                                           name="category[{{$index}}][name]">
                                                    @error("category.$index.name")
                                                    <span class="text-danger">هذا الحقل مطلوب</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 hidden ">
                                                <div class="form-group">
                                                <label for="projectinput1">أختصار اللغة {{__('messages.'.$translation->translation_lang)}}</label>
                                                    <input type="text" value="{{$translation->translation_lang}}" id="name"
                                                           class="form-control"
                                                           placeholder=""
                                                           name="category[{{$index}}][translation_lang]">
                                                    @error("category.$index.translation_lang")
                                                    <span class="text-danger">هذا الحقل مطلوب </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mt-1">
                                                    <input type="checkbox"  value="1" name="category[{{$index}}][active]"
                                                           id="switcheryColor4"
                                                           class="switchery" data-color="success"
                                                           @if ($translation -> active == 1)checked @endif/>
                                                    <label for="switcheryColor4"
                                                class="card-title ml-1">الحالة {{__('messages.'.$translation->translation_lang)}}</label>

                                                    @error("category.$index.active")
                                                    <span class="text-danger">هذا الحقل مطلوب</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" class="btn btn-warning mr-1 ml-2"
                                                onclick="history.back();">
                                            <i class="ft-x"></i> تراجع
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="la la-check-square-o"></i> تعديل
                                        </button>
                                    </div>
                                </form>

                                </div>
                                @endforeach
                                @endisset
                            </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Basic form layout section end -->
            </div>
        </div>
    </div>

@endsection
