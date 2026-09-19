 $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const inputPhone = document.getElementById("phone");
    inputPhone.addEventListener("keypress", function(e) {
        const charCode = e.which || e.keyCode;
        const charStr = String.fromCharCode(charCode);
        if (!/^\d$/.test(charStr) || this.value.length >= 10) e.preventDefault();
    });
    inputPhone.addEventListener("paste", function(e) {
        const pastedData = e.clipboardData.getData("text");
        if (!/^\d{1,10}$/.test(pastedData)) e.preventDefault();
    });

    $('#_inquiryModule').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = $('#contact-submit');
        const originalBtnText = submitBtn.html();

        form.find('.error-text').text('');

        submitBtn.prop('disabled', true).html('Submitting...');

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (response.message) {
                    toastr.success(response.message, 'Success', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000
                    });
                    form[0].reset();
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#error-' + key).text(value[0]);
                        toastr.error(value[0], 'Error', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 5000
                        });
                    });
                } else {
                    toastr.error('Something went wrong. Please try again.', 'Error', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000
                    });
                }
            }
        });
    });

});
