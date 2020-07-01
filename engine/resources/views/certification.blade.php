<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Aliensera - Certification</title>
        <link href="{{ asset('/css/bootstrap.min.css')}}" rel="stylesheet">
        <style type="text/css" media="screen">
            @font-face {
                font-family: Cairo;
                src: url("{{ asset('/fonts/Cairo-Regular.ttf') }}") /* TTF file for CSS3 browsers */
            }
            body,
            html {
                background-color: #fff;
                font-family: 'Cairo', sans-serif;
                position: relative;
            }

            p {
                margin: 0 0 2px !important;
            }

            #bannerProp {
                width: 75px;
                height: 890px;
            }

            .header-large{
                font-size: 350%;
                font-weight: 600;
                color: #967726;
                padding-top: 100px !important;
            }

            .light-bold {
                font-weight: 500;
                font-size: 13px;
            }

            p.certifies,
            p.details{
                font-size: 24px;
                color: #967726;
            }

            p.name{
                font-size: 36px;
                font-weight: bold;
                letter-spacing: 2px;
                color: #22637d;
                display: block;
                width: 500px;
                margin: auto !important;
                margin-top: 40px !important;
                margin-bottom: 40px !important;
                border-bottom: 1px solid #DDD;
                padding-bottom: 8px;
            }

            p.course{
                margin-bottom: 80px !important;
            }

            .medium-font{
                font-size: 15px!important;
            }

            .margin-prop{
                margin-bottom: 20px;
            }

            #logoProp {
                width: 85px;
                height: 75px;
            }

            #footerLogo {
                height: 40px;
                margin-right: 10px;
            }

            #hospLogo {
                height: 55px;
            }

            #signLogo {
                width: 350px;
            }

            #smallFont {
                font-size: 60%;
            }

            #specMargin {
                margin-bottom: 10px;
            }

            b.first{
                padding-bottom: 8px;
                border-bottom: 1px solid #DDD;
                display: block;
                width: 150px;
                margin: auto;
                margin-bottom: 10px;
                font-size: 20px;
                color: #22637d;
            }

            b.second{
                font-size: 24px;
                width: 150px;
                color: #967726;
            }

            .col-xs-1{
                padding-left: 0;
            }

            .container-fluid{
                border: 3px solid #DDD;
                border-radius: 5px;
                position: relative;
                height: 890px;
                overflow: hidden;
            }
            
            .col-xs-11{
                height: 890px;
            }

            .first-row .footer{
                position: absolute;
                top: calc(100% - 20px);
                bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-1">
                    <img id="bannerProp" src="{{ URL::to('/images/left_banner.png') }}">
                </div>
                <div class="col-xs-11">
                    <div class="row text-center first-row">
                        <div class="col-xs-12">
                            <p class="header-large">Certificate of Completion</p>
                        </div>
                        <div class="col-xs-12">
                            <p class="light-bold certifies">This certifies that</p>
                        </div>
                        <div class="col-xs-12 text-center">
                            <p class="light-bold name"> {{ $student }}</p>
                        </div>
                        <div class="col-xs-12">
                            <p class="details">had completed the necessary</p>
                            <p class="details"> courses of studies and passed</p>
                            <p class="course name">the {{ $course }} Exams</p>
                        </div>
                        <div class="col-xs-12 footer">
                            <div class="col-xs-6">
                                <b class="first">{{ $date }}</b>
                                <b class="second">Date</b>
                            </div>
                            <div class="col-xs-6">
                                <b class="first">{{ $instructor }}</b>
                                <b class="second">Instructor Name</b>
                            </div>
                            <img class="pull-right" id="footerLogo" src="{{ URL::to('/images/logo.jpeg') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
