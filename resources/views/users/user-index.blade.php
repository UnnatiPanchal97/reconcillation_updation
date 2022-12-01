@extends('layouts.app')
@section('title', 'User Management')
@section('content')
@section('toolbar title','User Management')
<!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <h1>User Management</h1>
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <!--begin::Add user-->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Add User
                        </button>
                        <!--end::Add user-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Modal - Add task-->
                    <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
                        <!--begin::Modal dialog-->
                        <div class="modal-dialog modal-dialog-centered mw-650px">
                            <!--begin::Modal content-->
                            <div class="modal-content">
                                <!--begin::Modal header-->
                                <div class="modal-header" id="kt_modal_add_user_header">
                                    <!--begin::Modal title-->
                                    <h2 class="fw-bolder">Add User</h2>
                                    <!--end::Modal title-->
                                    <!--begin::Close-->
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                        data-kt-users-modal-action="close">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                        <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                    transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </div>
                                    <!--end::Close-->
                                </div>
                                <!--end::Modal header-->
                                <!--begin::Modal body-->
                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                    <!--begin::Form-->
                                    <form id="kt_modal_add_user_form" class="form" method="POST"
                                        action="{{route('user.store')}}">
                                        @csrf
                                        <!--begin::Scroll-->
                                        <div class="d-flex flex-column scroll-y me-n7 pe-7"
                                            id="kt_modal_add_user_scroll" data-kt-scroll="true"
                                            data-kt-scroll-activate="{default: false, lg: true}"
                                            data-kt-scroll-max-height="auto"
                                            data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                            data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
                                            data-kt-scroll-offset="300px">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-7">
                                                <!--begin::Label-->
                                                <label class="required fw-bold fs-6 mb-2">First Name</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="firstname"
                                                    class="form-control form-control-solid mb-3 mb-lg-0"
                                                    placeholder="First name" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-7">
                                                <!--begin::Label-->
                                                <label class="required fw-bold fs-6 mb-2">Last Name</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="text" name="lastname"
                                                    class="form-control form-control-solid mb-3 mb-lg-0"
                                                    placeholder="Last name" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-7">
                                                <!--begin::Label-->
                                                <label class="required fw-bold fs-6 mb-2">Email</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="email" name="email"
                                                    class="form-control form-control-solid mb-3 mb-lg-0"
                                                    placeholder="example@domain.com" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-7">
                                                <!--begin::Label-->
                                                <label class="required fw-bold fs-6 mb-2">Password</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input type="password" name="password"
                                                    class="form-control form-control-solid mb-3 mb-lg-0" autocomplete="" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="mb-7">
                                                <!--begin::Label-->
                                                <label class="required fw-bold fs-6 mb-5">Role</label>
                                                <!--end::Label-->
                                                <!--begin::Roles-->
                                                <!--begin::Input row-->
                                                <div class="d-flex fv-row">
                                                    <!--begin::Radio-->
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <!--begin::Input-->
                                                        <input class="form-check-input me-3" name="user_role" type="radio" value="3" id="kt_modal_add_role_option_1" checked='checked' />
                                                        <!--end::Input-->
                                                        <!--begin::Label-->
                                                        <label class="form-check-label"
                                                            for="kt_modal_add_role_option_1">
                                                            <div class="fw-bolder text-gray-800">Accountant</div>
                                                        </label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Radio-->
                                                </div>
                                                <!--end::Input row-->
                                                <div class='separator separator-dashed my-5'></div>
                                                <!--begin::Input row-->
                                                <div class="d-flex fv-row">
                                                    <!--begin::Radio-->
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <!--begin::Input-->
                                                        <input class="form-check-input me-3" name="user_role" type="radio" value="4" id="kt_modal_add_role_option_2" />
                                                        <!--end::Input-->
                                                        <!--begin::Label-->
                                                        <label class="form-check-label" for="kt_modal_add_role_option_2">
                                                            <div class="fw-bolder text-gray-800">User</div>
                                                        </label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Radio-->
                                                </div>
                                                <!--end::Input row-->
                                                <!--end::Roles-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="mb-7">
                                                <!--begin::Label-->
                                                <label class="required fw-bold fs-6 mb-5">Status</label>
                                                <!--end::Label-->
                                                <!--begin::Roles-->
                                                <!--begin::Input row-->
                                                <div class="d-flex fv-row">
                                                    <!--begin::Radio-->
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <!--begin::Input-->
                                                        <input class="form-check-input me-3" name="status" type="radio"
                                                            value="0" id="kt_modal_add_status_option_1"
                                                            checked='checked' />
                                                        <!--end::Input-->
                                                        <!--begin::Label-->
                                                        <label class="form-check-label"
                                                            for="kt_modal_add_status_option_1">
                                                            <div class="fw-bolder text-gray-800">Inactive</div>
                                                        </label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Radio-->
                                                </div>
                                                <!--end::Input row-->
                                                <div class='separator separator-dashed my-5'></div>
                                                <!--begin::Input row-->
                                                <div class="d-flex fv-row">
                                                    <!--begin::Radio-->
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <!--begin::Input-->
                                                        <input class="form-check-input me-3" name="status" type="radio"
                                                            value="1" id="kt_modal_add_status_option_2" />
                                                        <!--end::Input-->
                                                        <!--begin::Label-->
                                                        <label class="form-check-label"
                                                            for="kt_modal_add_status_option_2">
                                                            <div class="fw-bolder text-gray-800">Active</div>
                                                        </label>
                                                        <!--end::Label-->
                                                    </div>
                                                    <!--end::Radio-->
                                                </div>
                                                <!--end::Input row-->
                                                <!--end::Roles-->
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--end::Scroll-->
                                        <!--begin::Actions-->
                                        <div class="text-center pt-15">
                                            <button type="reset" class="btn btn-light me-3"
                                                data-kt-users-modal-action="cancel">Discard</button>
                                            <button type="submit" class="btn btn-primary"
                                                data-kt-users-modal-action="submit">
                                                <span class="indicator-label">Submit</span>
                                                <span class="indicator-progress">Please wait...
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Modal body-->
                            </div>
                            <!--end::Modal content-->
                        </div>
                        <!--end::Modal dialog-->
                    </div>
                    <!--end::Modal - Add task-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                @if(Session::has('status'))
                    <div class="mt-2 mb-0 alert alert-success" role="alert">
                        {{ Session::get('status')}}
                    </div>
                @endif
                @if(Session::has('delete'))
                    <div class="mt-2 mb-0 alert alert-danger" role="alert">
                        {{ Session::get('delete')}}
                    </div>
                @endif
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="kt_table_users">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            {{-- <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_table_users .form-check-input" value="1" />
                                </div>
                            </th> --}}
                            <th class="min-w-125px">Username</th>
                            <th class="min-w-125px">Email</th>
                            <th class="min-w-125px">Role</th>
                            <th class="min-w-125px">Status</th>
                            <th class="min-w-125px">Actions</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="text-gray-600 fw-bold">
                        <!--begin::Table row-->
                        @foreach($user as $key => $users)
                        <tr>
                            <!--begin::Checkbox-->
                            {{-- <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="1" />
                                </div>
                            </td> --}}
                            <!--end::Checkbox-->
                            <!--begin::User=-->
                            <td>
                                <!--begin::User details-->
                                <div class="d-flex flex-column">
                                    <span>{{$users->firstname}} {{$users->lastname}}</span>
                                </div>
                                <!--begin::User details-->
                            </td>
                            <!--end::User=-->
                            {{-- begin email --}}
                            <td >
                                <!--begin::User details-->
                                <div class="d-flex flex-column">
                                    <span>{{$users->email}}</span>
                                </div>
                                <!--begin::User details-->
                            </td>
                            {{-- /end email --}}
                            <!--begin::Role=-->
                            <td>
                                @if ($users->user_role == '3')
                                Accountant
                                @endif
                                @if ($users->user_role == '4')
                                User
                                @endif
                            </td>
                            <!--end::Role=-->
                            <!--begin::Last login=-->
                            <td>
                                <div class="badge badge-light fw-bolder">
                                    @if($users->status == '0')
                                    Inactive
                                    @else
                                    Active
                                    @endif
                                </div>
                            </td>
                            <!--end::Last login=-->
                            <!--begin::Action=-->
                            <td>
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                {{-- <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <path
                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                            fill="black" />
                                    </svg>
                                </span> --}}
                                <!--end::Svg Icon-->
                                </a>
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px " data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_update_details{{$users->id}}" class="menu-link px-3">Edit</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <form method="post" action="{{route('user.destroy',$users->id)}}">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <div class="menu-item px-3">
                                            <button type="submit" class="menu-link px-3">Delete</button>
                                        </div>
                                    </form>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                                <!--begin::Modal - Update user details-->
                                <div class="modal fade" id="kt_modal_update_details{{$users->id}}" tabindex="-1" aria-hidden="true">
                                    <!--begin::Modal dialog-->
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <!--begin::Modal content-->
                                        <div class="modal-content">
                                            <!--begin::Form-->
                                            <form class="form" method="POST" action="{{route('user.update',$users)}}" id="kt_modal_update_user_form">
                                                @csrf
                                                @method('put')
                                                <!--begin::Modal header-->
                                                <div class="modal-header" id="kt_modal_update_user_header">
                                                    <!--begin::Modal title-->
                                                    <h2 class="fw-bolder">Update User Details</h2>
                                                    <!--end::Modal title-->
                                                    <!--begin::Close-->
                                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                        <span class="svg-icon svg-icon-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                    </div>
                                                    <!--end::Close-->
                                                </div>
                                                <!--end::Modal header-->
                                                <!--begin::Modal body-->
                                                <div class="modal-body py-10 px-lg-17">
                                                    <!--begin::Scroll-->
                                                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_user_header" data-kt-scroll-wrappers="#kt_modal_update_user_scroll" data-kt-scroll-offset="300px">
                                                        <!--begin::User toggle-->
                                                        {{-- <div class="fw-boldest fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_user_info" role="button" aria-expanded="false" aria-controls="kt_modal_update_user_user_info">User
                                                            Information
                                                            <span class="ms-2 rotate-180">
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                                                                <span class="svg-icon svg-icon-3">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        fill="none">
                                                                        <path
                                                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                                                            fill="black" />
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->
                                                            </span>
                                                        </div> --}}
                                                        <!--end::User toggle-->
                                                        <!--begin::User form-->
                                                        <div id="kt_modal_update_user_user_info" class="collapse show">
                                                            <input type="hidden" class="form-control form-control-solid" name="id" value="{{$users->id}}" />
                                                            <!--begin::Input group-->
                                                            <div class="fv-row mb-7">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-bold mb-2">First name</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <input type="text" class="form-control form-control-solid" name="firstname" value="{{$users->firstname}}" />
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Input group-->
                                                            <div class="fv-row mb-7">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-bold mb-2">Last name</label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <input type="text" class="form-control form-control-solid" value="{{$users->lastname}}" name="lastname" />
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Input group-->
                                                            <div class="fv-row mb-7">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-bold mb-2">
                                                                    <span>Email</span>
                                                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Email address must be active"></i>
                                                                </label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <input type="email" class="form-control form-control-solid" value="{{$users->email}}" name="email"/>
                                                                <!--end::Input-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Input group-->
                                                            <div class="mb-7">
                                                                <!--begin::Label-->
                                                                <label
                                                                    class="required fw-bold fs-6 mb-5">Role</label>
                                                                <!--end::Label-->
                                                                <!--begin::Roles-->
                                                                <!--begin::Input row-->
                                                                <div class="d-flex fv-row">
                                                                    <!--begin::Radio-->
                                                                    <div class="form-check form-check-custom form-check-solid">
                                                                        <!--begin::Input-->
                                                                        <input class="form-check-input me-3" name="user_role" type="radio" value="3" id="kt_modal_update_role_option_1" @if ($users->user_role == '3') @checked(true) @endif/>
                                                                        <!--end::Input-->
                                                                        <!--begin::Label-->
                                                                        <label class="form-check-label" for="kt_modal_update_role_option_1">
                                                                            <div class="fw-bolder text-gray-800">
                                                                                Accountant
                                                                            </div>
                                                                        </label>
                                                                        <!--end::Label-->
                                                                    </div>
                                                                    <!--end::Radio-->
                                                                </div>
                                                                <!--end::Input row-->
                                                                <div class='separator separator-dashed my-5'></div>
                                                                <!--begin::Input row-->
                                                                <div class="d-flex fv-row">
                                                                    <!--begin::Radio-->
                                                                    <div
                                                                        class="form-check form-check-custom form-check-solid">
                                                                        <!--begin::Input-->
                                                                        <input class="form-check-input me-3" name="user_role" type="radio" value="4" id="kt_modal_update_role_option_2" @if ($users->user_role == '4') @checked(true) @endif/>
                                                                        <!--end::Input-->
                                                                        <!--begin::Label-->
                                                                        <label class="form-check-label"
                                                                            for="kt_modal_update_role_option_2">
                                                                            <div class="fw-bolder text-gray-800">
                                                                                User</div>
                                                                        </label>
                                                                        <!--end::Label-->
                                                                    </div>
                                                                    <!--end::Radio-->
                                                                </div>
                                                                <!--end::Input row-->
                                                                <!--end::Roles-->
                                                            </div>
                                                            <!--end::Input group-->
                                                            <!--begin::Input group-->
                                                            <div class="mb-7">
                                                                <!--begin::Label-->
                                                                <label
                                                                    class="required fw-bold fs-6 mb-5">Status</label>
                                                                <!--end::Label-->
                                                                <!--begin::Roles-->
                                                                <!--begin::Input row-->
                                                                <div class="d-flex fv-row">
                                                                    <!--begin::Radio-->
                                                                    <div
                                                                        class="form-check form-check-custom form-check-solid">
                                                                        <!--begin::Input-->
                                                                        <input class="form-check-input me-3" name="status" type="radio" value="0" id="kt_modal_update_status_option_1" @if ($users->status == '0') @checked(true) @endif />
                                                                        <!--end::Input-->
                                                                        <!--begin::Label-->
                                                                        <label class="form-check-label"
                                                                            for="kt_modal_update_status_option_1">
                                                                            <div class="fw-bolder text-gray-800">
                                                                                Inactive</div>
                                                                        </label>
                                                                        <!--end::Label-->
                                                                    </div>
                                                                    <!--end::Radio-->
                                                                </div>
                                                                <!--end::Input row-->
                                                                <div class='separator separator-dashed my-5'></div>
                                                                <!--begin::Input row-->
                                                                <div class="d-flex fv-row">
                                                                    <!--begin::Radio-->
                                                                    <div class="form-check form-check-custom form-check-solid">
                                                                        <!--begin::Input-->
                                                                        <input class="form-check-input me-3" name="status" type="radio" value="1" id="kt_modal_update_status_option_2" @if ($users->status == '1') @checked(true) @endif />
                                                                        <!--end::Input-->
                                                                        <!--begin::Label-->
                                                                        <label class="form-check-label" for="kt_modal_update_status_option_2">
                                                                            <div class="fw-bolder text-gray-800">
                                                                                Active
                                                                            </div>
                                                                        </label>
                                                                        <!--end::Label-->
                                                                    </div>
                                                                    <!--end::Radio-->
                                                                </div>
                                                                <!--end::Input row-->
                                                                <!--end::Roles-->
                                                            </div>
                                                            <!--end::Input group-->
                                                        </div>
                                                        <!--end::User form-->
                                                    </div>
                                                    <!--end::Scroll-->
                                                </div>
                                                <!--end::Modal body-->
                                                <!--begin::Modal footer-->
                                                <div class="modal-footer flex-center">
                                                    <!--begin::Button-->
                                                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                                    <!--end::Button-->
                                                    <!--begin::Button-->
                                                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                                        <span class="indicator-label">Submit</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                        </span>
                                                    </button>
                                                    <!--end::Button-->
                                                </div>
                                                <!--end::Modal footer-->
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                    </div>
                                </div>
                                <!--end::Modal - Update user details-->
                            </td>
                            <!--end::Action=-->
                        </tr>
                        @endforeach
                        <!--end::Table row-->
                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection
<!--begin::Page Custom Javascript(used by this page)-->
@section('footer-scripts')
    <script src="{{asset('admin-panel/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{asset('admin-panel/assets/js/custom/apps/user-management/users/list/table.js')}}"></script>
    <script src="{{asset('admin-panel/assets/js/custom/apps/user-management/users/list/export-user.js')}}"></script>
    <script type="module" src="{{asset('admin-panel/assets/js/custom/apps/user-management/users/list/add.js')}}">
    </script>
    <script src="{{asset('admin-panel/assets/js/custom/widgets.js')}}"></script>
    <script src="{{asset('admin-panel/assets/js/custom/apps/chat/chat.js')}}"></script>
    <script src="{{asset('admin-panel/assets/js/custom/modals/create-app.js')}}"></script>
    <script src="{{asset('admin-panel/assets/js/custom/modals/upgrade-plan.js')}}"></script>
    {{--view.html --}}
    <script type="module" src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/view.js')}}"></script>
    <script type="module" src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/update-details.js')}}">
    </script>
    <script src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/add-schedule.js')}}"></script>
    <script src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/add-task.js')}}"></script>
    <script src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/update-email.js')}}"></script>
    <script src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/update-password.js')}}"></script>
    <script type="module" src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/update-role.js')}}"></script>
    <script type="module" src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/add-auth-app.js')}}"></script>
    <script src="{{('admin-panel/assets/js/custom/apps/user-management/users/view/add-one-time-password.js')}}">
    </script>
@endsection
<!--end::Page Custom Javascript-->