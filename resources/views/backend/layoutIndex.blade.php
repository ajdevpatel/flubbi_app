<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ config('web.webapp.base_url') }}appassets/"
    data-template="vertical-menu-template-no-customizer-starter">

<head>
    <meta charset="utf-8" />
    <meta name="key-token" content="{{ csrf_token() }}">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>{{ config('web.webapp.env.app_name') }}</title>
    <meta name="description" content="{{ config('web.webapp.env.app_name') }}" />
    <link rel="icon" type="image/x-icon" href="{{ app('request')->input('app_store_favicon') }}" />
    <script type="text/javascript">
        window.base_url = "{{ config('web.webapp.base_url') . config('web.webapp.admin_slug') }}";
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('appassets/vendor/fonts/tabler-icons.css') }}" />
    <!-- <link rel="stylesheet" href="{{ asset('appassets/vendor/fonts/fontawesome.css') }}" /> -->
    <!-- <link rel="stylesheet" href="{{ asset('appassets/vendor/fonts/flag-icons.css') }}" /> -->
    <link rel="stylesheet" href="{{ asset('appassets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('appassets/app/layout.css') }}" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/css/formValidation.min.css" />

    @hasSection('styleIndex')
        @yield('styleIndex')
    @endif

    @hasSection('inlineStyleIndex')
        @yield('inlineStyleIndex')
    @endif

    <script src="{{ asset('appassets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('appassets/js/config.js') }}"></script>

</head>

<body>
    <div class="loader-main-inner ">
        <div class="loader-bac"></div>
        <div class="u-loader">
            <img src="{{ asset('appassets/app/loader.svg') }}">
        </div>
    </div>

    <div class="layout-wrapper layout-content-navbar ">
        <div class="layout-container">
            @include('backend.includes.sidebar')

            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme border border-primary"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ app('request')->input('app_store_favicon') }}" alt
                                            class="h-auto rounded-circle border border-primary border-2 img-fluid" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ app('request')->input('app_store_favicon') }}" alt
                                                            class="h-auto rounded-circle border border-primary border-2 img-fluid" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span
                                                        class="fw-medium d-block">{{ ucfirst(app('request')->input('auth_name')) }}</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <?php /*
									<li>
										<a class="dropdown-item" href="#">
										<i class="ti ti-user-check me-2 ti-sm"></i>
										<span class="align-middle">My Profile</span>
										</a>
									</li>
									<li>
										<a class="dropdown-item" href="#">
										<i class="ti ti-settings me-2 ti-sm"></i>
										<span class="align-middle">Settings</span>
										</a>
									</li>
									<li>
										<a class="dropdown-item" href="#">
										<span class="d-flex align-items-center align-middle">
											<i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
											<span class="flex-grow-1 align-middle">Billing</span>
											<span class="flex-shrink-0 badge badge-center rounded-pill bg-label-danger w-px-20 h-px-20"
											>2</span
											>
										</span>
										</a>
									</li>
									<li>
										<div class="dropdown-divider"></div>
									</li>
									*/
                                    ?>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('_backendLogout') }}">
                                            <i class="ti ti-logout me-2 ti-sm"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('bodyIndex')
                    </div>

                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by
                                    <a href="#" target="_blank" class="footer-link text-primary fw-medium">AK</a>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    @php
                                        /*
									<a
										href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"
										target="_blank"
										class="footer-link me-4"
										>Documentation</a
									>
									*/
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    <!-- Add New Model -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addNewPopupModal" aria-labelledby="addNewPopupModalLabel">
        <div class="offcanvas-header pl-2 pt-2 pb-2">
            <h5 id="addNewPopupModalLabel" class="offcanvas-title"><span
                    class="ti-xs ti ti-file-plus me-2"></span>Add New</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100 pt-3 border-top">
            @yield('addNewPopupModalIndex')
        </div>
    </div>
    <!-- End Add New Model -->

    <script src="{{ asset('appassets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('appassets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('appassets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('appassets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('appassets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('appassets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('appassets/vendor/js/menu.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="{{ asset('appassets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('appassets/js/main.js') }}"></script>
    <script src="{{ asset('appassets/app/layout.js') }}"></script>

    <script type="text/javascript">
        window.lengthMenu = [
            [5, 10, 20, 50, -1],
            [5, 10, 20, 50, "All"]
        ];
        window.ex_button = [{
                extend: 'print',
                text: 'Print',
                className: 'btn btn-outline-primary'
            },
            {
                extend: 'csv',
                text: 'Export Excel',
                className: 'btn btn-outline-primary'
            },
            {
                extend: 'pdf',
                text: 'Export PDF',
                className: 'btn btn-outline-primary'
            },
            {
                extend: 'excel',
                text: 'Export Excel',
                className: 'btn btn-outline-primary'
            },
            {
                extend: 'copy',
                text: 'Copy',
                className: 'btn btn-outline-primary mr-2'
            }
        ];
    </script>

    @hasSection('jsIndex')
        @yield('jsIndex')
    @endif

    @hasSection('inlineJsIndex')
        @yield('inlineJsIndex')
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script type="text/javascript">
                Notify('{{ $error }}');
            </script>
        @endforeach
    @endif

</body>

</html>
