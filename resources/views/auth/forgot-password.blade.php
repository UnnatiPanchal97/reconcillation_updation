<x-guest-layout>
<div class="d-flex flex-column flex-lg-row-fluid py-10">
    <!--begin::Content-->
    <div class="d-flex flex-center flex-column flex-column-fluid">
        <div class="w-lg-600px p-10 p-lg-15 mx-auto" style="overflow-x:hidden; overflow-y: hidden;">

             <!--begin::Heading-->
             <div class="mb-10 text-center">
                <!--begin::Title-->
                <h2 class="headingline">Forgot Password ?</h2>
                <!--end::Title-->
                <!--begin::Link-->
                <div class="text-gray-400 fw-bold fs-4">Enter your email to reset your password.</div>
                <!--end::Link-->
            </div>
            <!--end::Heading-->

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="row fv-row mb-7">
                    <x-input-label for="email" :value="__('Email')" class="form-label fw-bolder text-dark fs-6" />

                    <x-text-input id="email" class="form-control form-control-lg form-control-solid" type="email" name="email" :value="old('email')" required autofocus />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="custombtn">
                        {{ __('Submit') }}
                    </x-primary-button>

                    <a href="{{ route('login') }}" class="btn btn-lg btn-light-primary fw-bolder">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-guest-layout>