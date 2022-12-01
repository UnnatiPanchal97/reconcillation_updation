<x-guest-layout>
<div class="d-flex flex-column flex-lg-row-fluid py-10">
    <!--begin::Content-->
    <div class="d-flex flex-center flex-column flex-column-fluid">
        <!--begin::Wrapper-->
        <div class="w-lg-500px p-10 p-lg-15 mx-auto">
            <!--begin::Form-->
            <form method="POST" class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" id="kt_sign_in_form"
                action="{{ route('login') }}">
                @csrf
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="headingline">Sign In to Reconcilation</h1>
                    <!--end::Title-->
                    <!--begin::Link-->
                    <div class="text-gray-400 fw-bold fs-4">New Here?
                        <a href="{{route('register')}}" class="link-primary fw-bolder">Create an Account</a>
                    </div>
                    <!--end::Link-->
                </div>
                <!--begin::Heading-->
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input class="form-control form-control-lg form-control-solid border-2 border-secondary rounded" type="text" name="email" autocomplete="off" />
                    @if($errors->first('email'))
                        <div class="alert alert-danger mt-2 mb-0">{{$errors->first('email')}}</div>    
                    @endif 
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack mb-2">
                        <!--begin::Label-->
                        <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                        <!--end::Label-->
                        <!--begin::Link-->
                        <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">Forgot Password
                            ?</a>
                        <!--end::Link-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Input-->
                    <input class="form-control form-control-lg form-control-solid border-2 border-secondary rounded" type="password" name="password" autocomplete="off" />
                    @if($errors->first('password'))
                        <div class="alert alert-danger mt-2 mb-0">{{$errors->first('password')}}</div>    
                    @endif 
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
                <!--begin::Actions-->
                <div class="text-center">
                    <!--begin::Submit button-->
                    <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                        <span class="indicator-label">Continue</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                    <!--end::Submit button-->
                    <!--begin::Separator-->
                    <!-- <div class="text-center text-muted text-uppercase fw-bolder mb-5">or</div> -->
                    <!--end::Separator-->
                    <!--begin::Google link-->
                    <!-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                        <img alt="Logo" src={{asset("admin-panel/assets/media/svg/brand-logos/google-icon.svg")}}
                            class="h-20px me-3" />Continue with Google</a> -->
                    <!--end::Google link-->
                    <!--begin::Google link-->
                    <!-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                        <img alt="Logo" src={{asset("admin-panel/assets/media/svg/brand-logos/facebook-4.svg")}}
                            class="h-20px me-3" />Continue with Facebook</a> -->
                    <!--end::Google link-->
                    <!--begin::Google link-->
                    <!-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100">
                        <img alt="Logo" src={{asset("admin-panel/assets/media/svg/brand-logos/apple-black.svg")}}
                            class="h-20px me-3" />Continue with Apple</a> -->
                    <!--end::Google link-->
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Content-->
    <!--begin::Footer-->
    @include('layouts.guest-footer')
    <!--end::Footer-->
</div>
</x-guest-layout>
<!--end::Form-->