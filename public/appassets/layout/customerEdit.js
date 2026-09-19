document.addEventListener("DOMContentLoaded", function() {
  const wizardIconsVertical = document.querySelector(
    ".wizard-vertical-icons-example"
  );

  if (
    typeof wizardIconsVertical !== undefined &&
    wizardIconsVertical !== null
  ) {
    const wizardIconsVerticalBtnNextList = [].slice.call(
        wizardIconsVertical.querySelectorAll(".btn-next")
      ),
      wizardIconsVerticalBtnPrevList = [].slice.call(
        wizardIconsVertical.querySelectorAll(".btn-prev")
      );

    const verticalIconsStepper = new Stepper(wizardIconsVertical, {
      linear: false
    });

    if (wizardIconsVerticalBtnNextList) {
      wizardIconsVerticalBtnNextList.forEach(wizardIconsVerticalBtnNext => {
        wizardIconsVerticalBtnNext.addEventListener("click", event => {
          verticalIconsStepper.next();
        });
      });
    }

    if (wizardIconsVerticalBtnPrevList) {
      wizardIconsVerticalBtnPrevList.forEach(wizardIconsVerticalBtnPrev => {
        wizardIconsVerticalBtnPrev.addEventListener("click", event => {
          verticalIconsStepper.previous();
        });
      });
    }
  }
});

$("#g-pwd-section").hide();
$(document).on("click", "#generate_pwd", function(e) {
    let randomstring = Math.random().toString(36).slice(-8);
    $("#g-pwd-section").show();
    $("#g_pwd").val(randomstring);
    $("input[name='password']").val(randomstring);
    $("input[name='cpassword']").val(randomstring);
});

$("#_moduleupdate").validate({
  rules: {
    name: {
      required: true
    },
    phone: {
      required: true
    },
    pincode: {
      required: true
    },
    mail: {
      required: true,
      email: true
    },
    city: {
      required: true
    },
    state: {
      required: true
    }
  },
  messages: {},
  submitHandler: function(form) {
    return true;
  }
});

$(document).on("submit", "#_moduleupdate", function(e) {
    e.preventDefault();
    if ($("#_moduleupdate").valid() === true && $(this).attr("action")) {
        loadLoader("on");
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function(xhr) {
                if (xhr.message) {
                    Notify(xhr.message, "success");
                }
                location.reload();
            },
            error: function(xhr) {
                ajaxResponseFailure(xhr);
            }
        });
    }
});

$("#ajax_datatables").DataTable();
