/* ==========================================================================
   Flubbi - pl-service / bl-service step flow
   --------------------------------------------------------------------------
   Steps are NOT switched in the browser. Every submit is a POST to its own
   Laravel route; the server validates, saves, and answers with the URL of the
   next step. The browser then does a real page load. Refresh, back button and
   "come back tomorrow" therefore all work, because the step the user belongs
   on is worked out server side from the database.
   ========================================================================== */

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

    /* ------------------------------------------------------------------
       Step form submit -> {"message": "...", "step": "<next url>"}
       ------------------------------------------------------------------ */

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
                    // full page load - this is what makes the flow resumable
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
                    return;
                }
                inlineMsg("Something went wrong, please try again.", true);
            },
        });

        return false;
    });

    /* ------------------------------------------------------------------
       Side actions that post but stay on the page (resend OTP, etc.)
       ------------------------------------------------------------------ */

    $(document).on("click", "[data-fl-post]", function (e) {
        e.preventDefault();

        var $btn = $(this);
        var url = $btn.attr("data-fl-post");
        var $form = $($btn.attr("data-fl-form") || "form.js-fl-step").first();

        if (!url || $btn.prop("disabled")) {
            return false;
        }

        btnBusy($btn, true);

        $.ajax({
            type: "POST",
            url: url,
            data: $form.length ? new FormData($form.get(0)) : new FormData(),
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
                if (xhr.cooldown) {
                    startCooldown($btn, parseInt(xhr.cooldown, 10));
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

    /* ------------------------------------------------------------------
       Resend cooldown (seconds come from the server, never trusted alone)
       ------------------------------------------------------------------ */

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

        // OTP boxes: jump to submit once filled, on mobile keyboards too
        $(document).on("input", "[data-fl-otp]", function () {
            var $el = $(this);
            var max = parseInt($el.attr("maxlength"), 10) || 6;
            if ($el.val().length >= max) {
                $el.closest("form").find("[type=submit]").first().focus();
            }
        });
    });
})(jQuery);
