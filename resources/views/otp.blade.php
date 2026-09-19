<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <script type="text/javascript" src="https://control.msg91.com/app/assets/otp-provider/otp-provider.js"> </script>

    <script>
        var configuration = {
            widgetId: "3561626b4465323534343035",
            tokenAuth: "437787TuO7Aun9oH67767a1dP1",
            /* dentifier: "916264502143", */
            /* "otp" : "8642", */
            exposeMethods: true,
            success: (data) => {
                console.log("========================",data); 
                /*
                const myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Cookie", "PHPSESSID=pfa59815rfkmhu0snpenl2dbb6");
                const raw = JSON.stringify({
                    "authkey": "437787AEkaTVsW0b677681a7P1",
                    "access-token": data.message
                });
                const requestOptions = {
                    method: "POST",
                    headers: myHeaders,
                    body: raw,
                    redirect: "follow"
                };
                fetch("https://control.msg91.com/api/v5/widget/verifyAccessToken", requestOptions)
                    .then((response) => response.text())
                    .then((result) => console.log("------------",result))
                    .catch((error) => console.error("***************",error));
                console.log('Success', data);
                */
            },
            failure: (error) => {
                console.log('failure reason', error);
            },
        };
        initSendOTP(configuration);

        var configurationx = {
            widgetId: "3561626b4465323534343035",
            tokenAuth: "437787TuO7Aun9oH67767a1dP1",
            /* dentifier: "916264502143", */
            /* "otp" : "8642", */
            exposeMethods: true,
            success: (data) => {
                console.log("========================",data); 
                /*
                const myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Cookie", "PHPSESSID=pfa59815rfkmhu0snpenl2dbb6");
                const raw = JSON.stringify({
                    "authkey": "437787AEkaTVsW0b677681a7P1",
                    "access-token": data.message
                });
                const requestOptions = {
                    method: "POST",
                    headers: myHeaders,
                    body: raw,
                    redirect: "follow"
                };
                fetch("https://control.msg91.com/api/v5/widget/verifyAccessToken", requestOptions)
                    .then((response) => response.text())
                    .then((result) => console.log("------------",result))
                    .catch((error) => console.error("***************",error));
                console.log('Success', data);
                */
            },
            failure: (error) => {
                console.log('failure reason', error);
            },
        };
        </script>

</body>

</html>

<script>
    
    /*
    setTimeout(function () {
        initSendOTP(configuration);
    }, 1000);

    setTimeout(function () {
        var isCaptchaVerified = window.isCaptchaVerified();
        console.log('Captcha is verified or not', isCaptchaVerified);
    }, 2000);

    setTimeout(function () {
        window.sendOtp(
            '917623982394', // mandatory
            (data) => console.log('OTP sent successfully.', data),
            (error) => console.log('Error occurred', error)
        );
    }, 5000);

    window.verifyOtp(
        '2760', // mandatory
        (data) => console.log('OTP sent successfully.', data),
        (error) => console.log('Error occurred', error),
        "3561666a6b6d343033363034"
    );
    */

</script>