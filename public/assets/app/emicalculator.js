if (document.getElementById("calculator")) {
    document.getElementsByTagName("body").onload = cal();
}
if (document.getElementById("calculator")) {
    // slider styling and color functions
    const rangeInputs = document.querySelectorAll('input[id="loanAmountRange"]');
    const numberInput = document.querySelector('input[id="loanAmountNumber"]');

    function handleInputChange(e) {
        let target = e.target;
        if (e.target.type !== "range") {
            target = document.getElementById("loanAmountRange");
        }
        const min = target.min;
        const max = target.max;
        const val = target.value;
        target.style.backgroundSize = ((val - min) * 100) / (max - min) + "% 100%";
    }
    rangeInputs.forEach((input) => {
        input.addEventListener("input", handleInputChange);
    });
    numberInput.addEventListener("input", handleInputChange);
    // slider styling and color functions

    // Interest styling and color functions
    const interestrangeInputs = document.querySelectorAll(
        'input[id="interestRange"]'
    );
    const interestnumberInput = document.querySelector(
        'input[id="interestNumber"]'
    );

    function handleInterestChange(e) {
        let target = e.target;
        if (e.target.type !== "range") {
            target = document.getElementById("interestRange");
        }
        const min = target.min;
        const max = target.max;
        const val = target.value;
        target.style.backgroundSize = ((val - min) * 100) / (max - min) + "% 100%";
    }
    interestrangeInputs.forEach((input) => {
        input.addEventListener("input", handleInterestChange);
    });
    interestnumberInput.addEventListener("input", handleInterestChange);
    // Interest styling and color functions

    // Loan Tenure styling and color functions
    const tenureInputs = document.querySelectorAll('input[id="tenureRange"]');
    const tenureInput = document.querySelector('input[id="tenureNumber"]');

    function handletenureChange(e) {
        let target = e.target;
        if (e.target.type !== "range") {
            target = document.getElementById("tenureRange");
        }
        const min = target.min;
        const max = target.max;
        const val = target.value;
        target.style.backgroundSize = ((val - min) * 100) / (max - min) + "% 100%";
    }
    tenureInputs.forEach((input) => {
        input.addEventListener("input", handletenureChange);
    });
    tenureInput.addEventListener("input", handletenureChange);
    // Loan Tenure styling and color functions
}
//calcualtion function
function cal() {
    let loanEvent = document.getElementById("loanAmountRange").value;
    let interestEvent = document.getElementById("interestRange").value;
    let tenureEvent = document.getElementById("tenureRange").value;

    let loanAmount = Number(loanEvent);
    let rate = interestEvent;
    let tenure = tenureEvent * 12;
    let r = rate / (12 * 100);
    let emi =
        (loanAmount * r * Math.pow(1 + r, tenure)) / (Math.pow(1 + r, tenure) - 1);
    let interest = emi * tenure - loanAmount;
    let totalAmount = parseFloat(loanAmount) + parseFloat(interest);
    //show Details
    document.getElementById("showEmi").innerHTML =
        "₹" + Number(emi.toFixed(0)).toLocaleString("en-IN");
    document.getElementById("showPrincipal").innerHTML =
        "₹" + Number(loanAmount.toFixed(0)).toLocaleString("en-IN");
    document.getElementById("showInterest").innerHTML =
        "₹" + Number(interest.toFixed(0)).toLocaleString("en-IN");
    document.getElementById("showTotalAmount").innerHTML =
        "₹" + Number(totalAmount.toFixed(0)).toLocaleString("en-IN");
    //pie chart
    let AmountValue = Number(loanAmount);
    let interestValue = Number(interest.toFixed(0));
    // pie chart
    var xValues = ["Loan Amount", "Interest"];
    var yValues = [AmountValue, interestValue];
    var barColors = ["#feb234", "#f64d3f"];

    var ctx = document.getElementById("pieChart").getContext("2d");
    if (window.myCharts != undefined) window.myCharts.destroy();
    window.myCharts = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues,
                borderWidth: "0",
            }, ],
        },

        options: {
            title: {
                display: true,
            },
        },
    });

    // pie chart
    //table function

    function calculateEMI(principal, rate, duration) {
        const monthlyRate = rate / 1200;
        const numPayments = duration;
        const emi =
            (principal * monthlyRate * Math.pow(1 + monthlyRate, numPayments)) /
            (Math.pow(1 + monthlyRate, numPayments) - 1);
        const monthNames = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ];

        // Get the current date and year
        const currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        let balance = principal;
        let data = "";
        for (let i = 0; i < numPayments; i++) {
            const interest = balance * monthlyRate;
            const principalPaid = emi - interest;
            balance -= principalPaid;
            // Calculate the payment month and year
            let paymentMonth = currentMonth + i + 1;
            let paymentYear = currentYear + Math.floor(paymentMonth / 12);
            paymentMonth %= 12;

            let month = monthNames[paymentMonth];
            let year = paymentYear;
            let monthlyPrincipal = Number(principalPaid.toFixed(0)).toLocaleString(
                "en-IN"
            );
            let monthlyInterest = Number(interest.toFixed(0)).toLocaleString("en-IN");
            let monthlyEmi = Number(emi.toFixed(0)).toLocaleString("en-IN");
            let monthlyBalance = Number(balance.toFixed(0)).toLocaleString("en-IN");
            data += `
          <tr>
                            <td>${month}  ${year}</th>
            <td>₹${monthlyPrincipal}</td>
            <td>₹${monthlyInterest}</td>
                            <td>₹${monthlyEmi}</td>
            <td>₹${monthlyBalance}</td>
                        </tr>
                `;
        }
        document.getElementById("tbody").innerHTML = data;
    }
    // Declare Values
    var principal = loanAmount;
    var duration = tenure;
    calculateEMI(principal, rate, duration);

    //rows hide show function
    let rows = document.querySelectorAll("tr");
    let showMoreBtn = document.getElementById("showMoreBtn");
    if (rows.length > 6) {
        showMoreBtn.style.display = "block";
    } else {
        showMoreBtn.style.display = "none";
    }
    if (rows.length === 0) {
        showMoreBtn.style.display = "none";
    }

    const initialRows = 6;
    for (let i = initialRows; i < rows.length; i++) {
        rows[i].style.display = "none";
    }

    function showMoreRows() {
        for (let i = 0; i <= rows.length; i++) {
            if (rows[i]) {
                rows[i].style.display = "";
                showMoreBtn.style.display = "none";
            }
        }
    }
    showMoreBtn.addEventListener("click", showMoreRows);
    //rows hide show function
    //cal() end
}

//validate input fields
function validateAmount() {
    let val = document.getElementById("loanAmountNumber").value;
    if (val < 100000 || val > 10000000) {
        document.getElementById("loanAmountNumber").style.cssText = `
        background-color: #f8d9d9;
        border-color: #ff2020;
        color: #ff2020;
        `;
    } else {
        document.getElementById("loanAmountNumber").style.cssText = `
        background-color: #fff;
        border: 1px solid #464646;
        color: #111;
        `;
    }
}

function validateInterest() {
    let val = document.getElementById("interestNumber").value;
    if (val < 1 || val > 30) {
        document.getElementById("interestNumber").style.cssText = `
        background-color: #f8d9d9;
        border-color: #ff2020;
        color: #ff2020;
        `;
    } else {
        document.getElementById("interestNumber").style.cssText = `
        background-color: #fff;
        border: 1px solid #464646;
        color: #111;
        `;
    }
}

function validateTenure() {
    let val = document.getElementById("tenureNumber").value;
    if (val < 1 || val > 30) {
        document.getElementById("tenureNumber").style.cssText = `
        background-color: #f8d9d9;
        border-color: #ff2020;
        color: #ff2020;
        `;
    } else {
        document.getElementById("tenureNumber").style.cssText = `
        background-color: #fff;
        border: 1px solid #464646;
        color: #111;
        `;
    }
}
if (document.getElementById("calculator")) {
    document.getElementById("loanAmountRange").addEventListener("change", cal);
    document.getElementById("interestRange").addEventListener("change", cal);
    document.getElementById("tenureRange").addEventListener("change", cal);
    document
        .getElementById("loanAmountNumber")
        .addEventListener("keyup", cal, true);
    document
        .getElementById("loanAmountNumber")
        .addEventListener("keyup", validateAmount, true);
    document
        .getElementById("interestNumber")
        .addEventListener("keyup", cal, true);
    document
        .getElementById("interestNumber")
        .addEventListener("keyup", validateInterest, true);
    document.getElementById("tenureNumber").addEventListener("keyup", cal, true);
    document
        .getElementById("tenureNumber")
        .addEventListener("keyup", validateTenure, true);
}

//initailize function
