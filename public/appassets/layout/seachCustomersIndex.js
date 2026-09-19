let isSearching = false;

$("#searchCustomerBtn").on("click", function () {
    if (isSearching) return;

    let phone = $("#customerPhoneSearch").val();

    if (phone.length < 10) {
        $("#customerCard").hide();
        $("#viewBtn").hide();
        $("#noCustomer").hide();
        return;
    }

    isSearching = true;

    $("#customerLoader").show();
    $("#customerCard").hide();
    $("#viewBtn").hide();
    $("#noCustomer").hide();

    let currentUrl = window.location.pathname.replace(/\/$/, "");
    $.ajax({
        url: currentUrl + "/search",
        type: "GET",
        data: { phone: phone },

        success: function (res) {
            $("#customerLoader").hide();
            isSearching = false;

            if (res.status) {
                let customer = res.data;

                $("#customerName").text(customer.name ?? "-");
                $("#customerPhone").text(customer.phone ?? "-");
                $("#customerEmail").text(customer.email ?? "-");
                $("#customerStatus").text(customer.status ?? "-");

                let detailsUrl =
                    currentUrl.replace("search-customers", "customers") +
                    "/" +
                    (customer.uuid ?? customer.uuid);
                $("#viewDetailsLink").attr("href", detailsUrl);

                $("#customerCard").show();
                $("#viewBtn").show();
                $("#noCustomer").hide();
            } else {
                $("#customerCard").hide();
                $("#viewBtn").hide();
                if (res.message) {
                    $("#noCustomer").text(res.message);
                } else {
                    $("#noCustomer").text("Customer not found.");
                }
                $("#noCustomer").show();
            }
        },
        error: function (xhr) {
            $("#customerLoader").hide();
            isSearching = false;

            $("#customerCard").hide();
            $("#viewBtn").hide();
            let errMsg = "An error occurred.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errMsg = xhr.responseJSON.message;
            }
            $("#noCustomer").text(errMsg).show();
        },
    });
});

$("#customerPhoneSearch").on("keypress", function (e) {
    if (e.which === 13) {
        e.preventDefault();
        $("#searchCustomerBtn").click();
    }
});
