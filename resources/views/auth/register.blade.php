<x-guest-layout>
<div class="d-flex flex-column flex-lg-row-fluid py-10">
    <!--begin::Content-->
    <div class="d-flex flex-center flex-column flex-column-fluid">
        <div class="w-lg-600px p-10 p-lg-15 mx-auto" style="overflow-x:hidden; overflow-y: hidden;">
            <!--begin::Heading-->
            <div class="mb-10 text-center">
                <!--begin::Title-->
                <h2 class="headingline">Create an Account</h2>
                <!--end::Title-->
                <!--begin::Link-->
                <div class="text-gray-400 fw-bold fs-4">Already have an account?
                    <a href="{{route('login')}}" class="link-primary fw-bolder">Sign in here</a>
                </div>
                <!--end::Link-->
            </div>
            <!--end::Heading-->
            <!--begin::Form-->
            <form class="form w-100" method="POST" action="{{ route('sign-up.store') }}" data-parsley-validate="" novalidate>
            @csrf
                <!-- company name -->
                <div class="row fv-row mb-7">
                    <x-input-label for="company_name" :value="__('Company Name')" class="form-label fw-bolder text-dark fs-6" />
                    <x-text-input id="company_name" class="form-control form-control-lg form-control-solid border-2 border-secondary rounded" type="text" name="company_name" :value="old('company_name')" required autofocus />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                </div>
                <!-- firstname -->
                <div class="row fv-row mb-7">
                    <div class="col-xl-6">
                        <x-input-label for="firstname" :value="__('First Name')" class="form-label fw-bolder text-dark fs-6" />
                        <x-text-input id="firstname" class="form-control form-control-lg form-control-solid border-2 border-secondary rounded" type="text" name="firstname" :value="old('firstname')" required autofocus />
                        <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                    </div>
                    <!-- lastname -->
                    <div class="col-xl-6">
                        <x-input-label for="lastname" :value="__('Last Name')" class="form-label fw-bolder text-dark fs-6" />
                        <x-text-input id="lastname" class="form-control form-control-lg form-control-solid border-2 border-secondary rounded" type="text" name="lastname" :value="old('lastname')" required autofocus />
                        <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                    </div>
                </div>
                <!-- Phone Number -->
                <div class="row fv-row mb-7">
                    <x-input-label for="phone" :value="__('Phone')" class="form-label fw-bolder text-dark fs-6" />
                    <x-text-input id="phone" class="form-control form-control-lg form-control-solid border-2 border-secondary rounded" type="number" name="phone" :value="old('phone')" required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>
                <!-- Email Address -->
                <div class="row fv-row mb-7">
                    <x-input-label for="email" :value="__('Email')" class="form-label fw-bolder text-dark fs-6" />
                    <x-text-input id="email" class="form-control form-control-lg form-control-solid border-2 border-secondary rounded" type="email" name="email" :value="old('email')" autocomplete="email" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <!-- Password -->
                <div class="row fv-row mb-7">
                    <div class="col-xl-6">
                        <x-input-label for="password" :value="__('Password')" class="form-label fw-bolder text-dark fs-6" />
                        <x-text-input id="password" class="form-control form-control-lg form-control-solid border-2 border-secondary rounded"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <!-- Confirm Password -->
                    <div class="col-xl-6">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')"  class="form-label fw-bolder text-dark fs-6" />
                        <x-text-input id="password_confirmation" class="form-control form-control-lg form-control-solid border-2 border-secondary rounded"
                                        type="password"
                                        name="password_confirmation" autocomplete="Confirm Password" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
                <div class="fv-row mb-10">
                    <label class="form-check form-check-custom form-check-solid form-check-inline">
                        <span class="form-check-label fw-bold text-gray-700 fs-6">Already have an account?
                            <a href="{{ route('login') }}" class="ms-1 link-primary">{{ __('Sign in instead or return home?') }}</a>.</span>
                    </label>
                </div>
                <div class="text-center">
                    <x-primary-button class="custombtn">
                        {{ __('Sign Up') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>    
</div>
</x-guest-layout>
