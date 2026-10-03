(function ($) {
    "use strict";

    function btnBusy($btn, busy) {
        if (!$btn.length) {
            return;
        }

        if (busy) {
            if (!$btn.data("fl-label")) {
                $btn.data("fl-label", $btn.html());
            }
            $btn.prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Please wait...');
            return;
        }

        $btn.prop("disabled", false).html($btn.data("fl-label") || $btn.html());
    }

    function inlineMsg(text, isError) {
        var $box = $(".fl-inline-msg").first();
        if (!$box.length) {
            return;
        }
        $box.removeClass("is-ok is-err").addClass(isError ? "is-err" : "is-ok").html(text);
    }

    $(document).on("submit", "form.js-fl-step", function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find("[type=submit]").first();
        var action = $form.attr("action");

        if (!action || $form.data("fl-sending")) {
            return false;
        }

        $form.data("fl-sending", true);
        btnBusy($btn, true);
        if (typeof loadLoader === "function") {
            loadLoader("on");
        }

        $.ajax({
            type: "POST",
            url: action,
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (xhr) {
                if (typeof loadLoader === "function") {
                    loadLoader();
                }

                if (xhr.message && typeof Notify === "function") {
                    Notify(xhr.message, "success");
                }

                if (xhr.step) {
                    window.location.href = xhr.step;
                    return;
                }

                $form.data("fl-sending", false);
                btnBusy($btn, false);
            },
            error: function (xhr) {
                $form.data("fl-sending", false);
                btnBusy($btn, false);

                if (typeof ajaxResponseFailure === "function") {
                    ajaxResponseFailure(xhr);
                } else {
                    inlineMsg("Something went wrong, please try again.", true);
                }

                if (xhr.responseJSON && xhr.responseJSON.step) {
                    setTimeout(function () { window.location.href = xhr.responseJSON.step; }, 1500);
                }
            },
        });

        return false;
    });

    $(document).on("click", "[data-fl-post]", function (e) {
        e.preventDefault();

        var $btn = $(this);
        var url = $btn.attr("data-fl-post");
        var $form = $($btn.attr("data-fl-form") || "form.js-fl-step").first();

        if (!url || $btn.prop("disabled")) {
            return false;
        }

        var data = $form.length ? new FormData($form.get(0)) : new FormData();
        var extra = $btn.data("fl-data");
        if (extra && typeof extra === "object") {
            Object.keys(extra).forEach(function (k) { data.append(k, extra[k]); });
        }

        btnBusy($btn, true);

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,
            success: function (xhr) {
                btnBusy($btn, false);

                if (xhr.message && typeof Notify === "function") {
                    Notify(xhr.message, "success");
                }
                if (xhr.message) {
                    inlineMsg(xhr.message, false);
                }

                if (xhr.show) {
                    $(xhr.show).prop("hidden", false);
                }
                if (xhr.hide) {
                    $(xhr.hide).prop("hidden", true);
                }
                if (xhr.readonly) {
                    $(xhr.readonly).prop("readonly", true);
                }
                if (xhr.focus) {
                    $(xhr.focus).trigger("focus");
                }
                if (xhr.cooldown) {
                    var $target = xhr.cooldown_target ? $(xhr.cooldown_target) : $btn;
                    startCooldown($target.length ? $target : $btn, parseInt(xhr.cooldown, 10));
                }
                if (xhr.step) {
                    window.location.href = xhr.step;
                }
            },
            error: function (xhr) {
                btnBusy($btn, false);
                if (typeof ajaxResponseFailure === "function") {
                    ajaxResponseFailure(xhr);
                }
            },
        });

        return false;
    });

    function startCooldown($btn, seconds) {
        if (!$btn.length || !seconds || seconds < 1) {
            return;
        }

        var label = $btn.data("fl-label") || $btn.html();
        var left = seconds;

        $btn.data("fl-label", label).prop("disabled", true);

        var tick = function () {
            if (left <= 0) {
                clearInterval(timer);
                $btn.prop("disabled", false).html(label);
                return;
            }
            $btn.html("Resend in " + left + "s");
            left -= 1;
        };

        tick();
        var timer = setInterval(tick, 1000);
    }

    $(function () {
        $("[data-fl-cooldown]").each(function () {
            startCooldown($(this), parseInt($(this).attr("data-fl-cooldown"), 10));
        });

        $(document).on("input", "[data-fl-otp]", function () {
            var $el = $(this);
            var max = parseInt($el.attr("maxlength"), 10) || 6;
            if ($el.val().length >= max) {
                $el.closest("form").find("[type=submit]").first().focus();
            }
        });

        var pincodes = {};

        $(document).on("input change", "[data-pincode-lookup]", function () {
            var $pin = $(this);
            var $form = $pin.closest("form");
            var typed = function () {
                return String($pin.val() || "").trim();
            };
            var pin = typed();

            var hint = function (text) {
                var $hint = $pin.siblings(".js-pincode-hint");
                if (!text) {
                    $hint.remove();
                    return;
                }
                if (!$hint.length) {
                    $hint = $('<span class="fl-field__hint js-pincode-hint"></span>').insertAfter($pin);
                }
                $hint.text(text);
            };

            if (!/^[1-9][0-9]{5}$/.test(pin)) {
                $pin.data("pincode-last", "");
                hint("");
                return;
            }
            if ($pin.data("pincode-last") === pin) {
                return;
            }
            $pin.data("pincode-last", pin);

            var fill = function (place) {
                if (typed() !== pin) {
                    return;
                }
                if (!place || !place.found) {
                    hint("We could not find this PIN code. Please enter your city and state.");
                    return;
                }
                if (place.city) {
                    $form.find("[name=city]").val(place.city).trigger("change");
                }
                if (place.state_id) {
                    $form.find("[name=state_id]").val(String(place.state_id)).trigger("change");
                }
                hint("");
            };

            if (pincodes[pin]) {
                fill(pincodes[pin]);
                return;
            }

            hint("Finding your city and state...");
            $.getJSON(String($pin.attr("data-pincode-lookup")).replace("__PIN__", pin))
                .done(function (place) {
                    pincodes[pin] = place;
                    fill(place);
                })
                .fail(function () {
                    if (typed() === pin) {
                        $pin.data("pincode-last", "");
                        hint("");
                    }
                });
        });
    });
})(jQuery);
