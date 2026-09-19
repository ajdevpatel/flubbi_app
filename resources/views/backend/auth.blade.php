
<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{ config("web.webapp.base_url") }}appassets/" data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ config("web.webapp.base_url") . "store/" . config("web.webapp.base_media.favicon") }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/libs/typeahead-js/typeahead.css" />

    <!-- Vendor -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/css/formValidation.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ config("web.webapp.base_url") }}appassets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ config("web.webapp.base_url") }}appassets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ config("web.webapp.base_url") }}appassets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ config("web.webapp.base_url") }}appassets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Login -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="{{ route('_backendLogin') }}" class="app-brand-link gap-2">
                    <img src="{{ config("web.webapp.base_url") . "store/logo.webp" }}" width="200px">
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1 pt-2">Welcome to {{ config("web.webapp.env.app_name") }}! 👋</h4>
              <p class="mb-4">Please sign-in to your account and start the adventure</p>

              @if ($errors->any())
              <div class="row">
                  <ul class="col-12">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <form action="{{ route('_backendLogin') }}" method="post" id="_authlogin" class="mb-3" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label for="loginKey" class="form-label">Email or Username</label>
                  <input type="text" class="form-control" id="email" name="loginKey" placeholder="Enter your email or username" autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
                <p class="text-center">
                  <span>Made with ❤️ By </span>
                  <a href="https://www.edigitrix.com/" target="_blank">
                    <span>Edigitrix.com</span>
                  </a>
                </p>
              </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ config("web.webapp.base_url") }}appassets/vendor/libs/popper/popper.js"></script>
    <script src="{{ config("web.webapp.base_url") }}appassets/vendor/js/bootstrap.js"></script>
    <script src="{{ config("web.webapp.base_url") }}appassets/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

  </body>
</html>



<script language="javascript">

document.addEventListener("DOMContentLoaded", function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        },
        contentType: false,
        cache: false,
        processData: false
    });

    $("#_authlogin").validate({
        rules: {
            loginKey: {
                required: false,
                email: false,
            },
            password: {
                required: false
            }
        },
        messages: {
            /* lastName: {
                required: "Please enter last name",
            } */
        },
        submitHandler: function(form) {
            return true;
        }
    });

    $(document).on("submit", '#_authlogin', function(e) {
        e.preventDefault();
        if($("#_authlogin").valid() === true && $(this).attr("action")) {
			$.ajax({
                type: "POST",
				url: $(this).attr("action"),
				data: new FormData(this),
                dataType: 'json',
				success: function(xhr) {
                    if (xhr.is_url) {
                        window.location.href = xhr.is_url;
                    }
				},
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var x_msg = '';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            x_msg += value.join(', ');
                        });
                        alert(x_msg);
                    }
				}
			});
        }
    });
});

</script>


