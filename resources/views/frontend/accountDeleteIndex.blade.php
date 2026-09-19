@extends('frontend.layoutIndex')

@section('bodyIndex')
    <!-- Breadcrumb Area -->
    <div class="breadcumb-area d-flex">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title">
                            <h4>Delete Account</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home </a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Delete Account</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form Section -->
    <section class="contact_area inner_section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="section_title">
                        <h4>IMPORTANT NOTIFICATION</h4>
                        <h1>Account Deletion</h1>
                        <p>We respect your privacy and data. We are here to help you seamlessly remove your account from our
                            platform.</p>
                    </div>
                    <div class="contact_main_info">
                        <div class="call-do-action-info">
                            <div class="call-do-social_icon">
                                <i class="fas fa-trash-alt"></i>
                            </div>
                            <div class="call_info">
                                <p>Irreversible Action</p>
                                <h3>Deleting your account permanently removes your access to our services.</h3>
                            </div>
                        </div>
                        <div class="call-do-action-info">
                            <div class="call-do-social_icon">
                                <i class="fas fa-user-slash"></i>
                            </div>
                            <div class="call_info">
                                <p>Unlink Data</p>
                                <h3>Your contact information is safely unlinked to prevent further marketing.</h3>
                            </div>
                        </div>
                        <div class="call-do-action-info">
                            <div class="call-do-social_icon">
                                <i class="fas fa-undo"></i>
                            </div>
                            <div class="call_info">
                                <p>Fresh Start</p>
                                <h3>You are welcome to register again at any time using your original details.</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-form-box style_two">
                        <div class="section_title style_three style_four text-center">
                            <h4>VERIFICATION</h4>
                            <h1>Confirm Account Deletion</h1>
                        </div>

                        <div id="_deleteMessage"
                            style="display: none; padding: 10px; margin-top: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: 600; text-align: center;">
                        </div>

                        <form id="_deleteStep1" method="post" action="javascript:void(0);">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-box">
                                        <input type="text" name="phone" id="phone"
                                            placeholder="Enter 10 Digit Mobile Number..." required maxlength="10">
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center contact-form">
                                    <button type="submit" id="sendOtpBtn"><i class="fas fa-paper-plane"></i> Send
                                        OTP</button>
                                </div>
                            </div>
                        </form>

                        <form id="_deleteStep2" method="post" action="javascript:void(0);" style="display: none;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-box">
                                        <input type="text" name="otp" id="otp"
                                            placeholder="Enter strictly 6 digit OTP..." required maxlength="6">
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center contact-form">
                                    <button type="submit" id="verifyOtpBtn" style="background: #dc3545;"><i
                                            class="fas fa-trash"></i> Confirm Deletion</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('jsIndex')
    <script>
        $(document).ready(function() {
            function showMessage(msg, isError) {
                $('#_deleteMessage').text(msg).css({
                    'background-color': isError ? '#ffe6e6' : '#e6ffe6',
                    'color': isError ? '#cc0000' : '#006600',
                    'border': '1px solid ' + (isError ? '#cc0000' : '#006600')
                }).show();
            }

            $('#_deleteStep1').on('submit', function(e) {
                e.preventDefault();
                let phone = $('#phone').val();

                $.ajax({
                    url: "{{ route('_accountDeleteSendOtp') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        phone: phone
                    },
                    beforeSend: function() {
                        $('#sendOtpBtn').html(
                            '<i class="fas fa-spinner fa-spin"></i> Sending...');
                        $('#sendOtpBtn').prop('disabled', true);
                        $('#_deleteMessage').hide();
                    },
                    success: function(response) {
                        $('#sendOtpBtn').html('<i class="fas fa-paper-plane"></i> Send OTP');
                        $('#sendOtpBtn').prop('disabled', false);

                        if (response.status) {
                            showMessage(response.message, false);
                            $('#_deleteStep1').hide();
                            $('#_deleteStep2').show();
                        } else {
                            showMessage(response.message || 'Error sending OTP', true);
                        }
                    },
                    error: function() {
                        $('#sendOtpBtn').html('<i class="fas fa-paper-plane"></i> Send OTP');
                        $('#sendOtpBtn').prop('disabled', false);
                        showMessage('Network error occurred.', true);
                    }
                });
            });

            $('#_deleteStep2').on('submit', function(e) {
                e.preventDefault();
                let phone = $('#phone').val();
                let otp = $('#otp').val();

                if (confirm(
                        "Are you sure you want to permanently delete your account? This action cannot be fully undone."
                        )) {
                    $.ajax({
                        url: "{{ route('_accountDeleteVerify') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            phone: phone,
                            otp: otp
                        },
                        beforeSend: function() {
                            $('#verifyOtpBtn').html(
                                '<i class="fas fa-spinner fa-spin"></i> Verifying...');
                            $('#verifyOtpBtn').prop('disabled', true);
                            $('#_deleteMessage').hide();
                        },
                        success: function(response) {
                            $('#verifyOtpBtn').html(
                                '<i class="fas fa-trash"></i> Confirm Deletion');
                            $('#verifyOtpBtn').prop('disabled', false);

                            if (response.status) {
                                showMessage(response.message, false);
                                $('#_deleteStep2').hide();
                            } else {
                                showMessage(response.message || 'Error verifying OTP', true);
                            }
                        },
                        error: function() {
                            $('#verifyOtpBtn').html(
                                '<i class="fas fa-trash"></i> Confirm Deletion');
                            $('#verifyOtpBtn').prop('disabled', false);
                            showMessage('Network error occurred.', true);
                        }
                    });
                }
            });
        });
    </script>
@endsection
