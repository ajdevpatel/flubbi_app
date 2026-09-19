<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{{ config("web.webapp.env.app_name") }}</title>
        <meta name="author" content="{{ config("web.webapp.env.app_dev_author") }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	</head>
	<body marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper" dir="ltr" style="background-color:#f7f7f7;margin:0;padding:70px 0;width:100%" width="100%" >
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="700" style="background-color:#fff;border:1px solid #dedede;border-radius:3px" id="template_container">
                            <tr>
                                <td align="center" valign="top" style="border-bottom: 1px solid #8080803b;">
                                    {{-- Header --}}
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#fff;border:1px solid #dedede;border-radius:3px;background-color:rgba(80,182,142,.1);color:#636386;border-bottom:0;font-weight:unset;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-top:5px solid #50b68e;border-radius:3px 3px 0 0" id="template_header">
                                        <tr>
                                            <td id="header_wrapper" style="padding:10px;display: block;">
                                                <div width="40%" style="margin:0; float: left;">
                                                    <img src="{{ $in_data["store"]["logo"] }}" alt="{{ config("web.webapp.env.app_name") }}"
                                                    style="display:inline-block;height:auto;vertical-align:middle;margin-right:10px;max-width:200px;width:auto;max-height:80px"/>
                                                </div>
                                                <div width="60%" style="margin:0; float: right;">
                                                    <ul style="list-style: circle;">
                                                        <li style="margin-bottom: 5px;"><b>Email : </b> <a href="mailto:{{ str_replace(' ', '', $in_data["store"]["mail"]) }}" style="text-decoration: none;float: right;">&nbsp; {{ $in_data["store"]["mail"] }} </a></li>
                                                        <li style="margin-bottom: 5px;"><b>Mobile No : </b> <a href="tel:{{ str_replace(' ', '', $in_data["store"]["phone"]) }}" style="text-decoration: none;float: right;">{{ $in_data["store"]["phone"] }}</a> </li>
                                                        <li style="margin-bottom: 5px;"><b>WhatsApp : </b> <a href="https://wa.me/{{ str_replace(' ', '', $in_data["store"]["wapp_phone"]) }}" style="text-decoration: none;float: right;">{{ $in_data["store"]["wapp_phone"] }} </a> </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    {{-- End Header --}}
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    {{-- Body --}}
                                    <table border="0" cellpadding="10" cellspacing="0" width="700" id="template_body">
                                        <tr>
                                            <td valign="top" style="margin: 1rem;margin-top: 3rem;">
                                                <div style="margin: 0.5rem 1rem;padding:0.2rem;font-size:0.9rem;line-height:1.3rem;margin-bottom: 0px;">
                                                    @hasSection("bodyIndex")
                                                        @yield("bodyIndex")
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    {{-- End Body --}}
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    {{-- Footer --}}
                                    <table border="0" cellpadding="10" cellspacing="0" width="700" id="template_footer">
                                        <tr>
                                            <td valign="top" style="padding-bottom: 0rem;margin-top: 0px;padding-top: 0.2rem;">
                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td style="padding:0 20px;font-size:0.9rem;vertical-align:top" align="left">
                                                            <p style="margin: 2px 0px 0.6rem 0px;">
                                                                {{ config("web.mail.footer_contact_us.text") }}
                                                                <a href="mailto:{{ str_replace(' ', '', config("web.mail.footer_contact_us.mail")) }}" style="color:#c61392" target="_blank"><i>{{ config("web.mail.footer_contact_us.mail") }}</i></a>
                                                            </p>
                                                            <p style="font-weight: bold;font-size: 0.9rem;padding: 0px;margin: 0 0 5px 0px;">{{ config("web.mail.footer_warm_regards_heading") }},<br> {{ config("web.mail.footer_warm_regards_text") }}</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0;border-radius: 0;">
                                                            <p style="margin: 0;padding: 0;border-bottom: 1px solid #8080806e;"></p>
                                                        </td>
                                                    </tr>
                                                    @if(null != config("web.mail.footer_links") && [] != config("web.mail.footer_links"))
                                                    <tr>
                                                        <td style="border-radius: 6px;padding: 5px 0px;font-size:0.9rem;">
                                                            @foreach(config("web.mail.footer_links") as $k => $v)
                                                                <a style="color: #96588a;font-weight: normal;text-decoration: unset;width: {{ 100 / count(config("web.mail.footer_links")) }}%;float: left;text-align: center;" target="_blank" href="{{ route($v['url']) }}"><span>{{ Str::ucfirst($v['label']) }}</span></a>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @php
                                                        /*
                                                    <tr>
                                                        <td colspan="2" valign="middle" style="padding:0 20px;text-align: center;">
                                                            <p style="margin: 0;font-size: 15px;border-top: 1px solid #8080803b;padding: 2px 0px;">
                                                                <img src="http://127.0.0.1:8000/assets/img/payment.png" alt="">
                                                            </p>
                                                        </td>
                                                    </tr>
                                                        */
                                                    @endphp
                                                    <tr>
                                                        <td colspan="2" valign="middle" style="padding:0px;text-align: center;">
                                                            <p style="margin: 0;font-size: 1rem;border-top: 1px solid #8080803b;padding: 5px 0px;"> {{ $in_data["store"]["copyright_text"] }} </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    {{-- End Footer --}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>

