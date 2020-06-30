<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="description" content="Login page example">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Aliensera</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="description" content="Page with empty content">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700"> --}}
        <style type="text/css" media="screen">
            .kt-page--loading * {
                -webkit-transition: none !important;
                transition: none !important; 
            }
            .col-form-label {
                padding-top: calc(0.65rem + 1px);
                padding-bottom: calc(0.65rem + 1px);
                margin-bottom: 0;
                font-size: inherit;
                line-height: 1.5; 
            }
            b, strong {
                font-weight: bold;
            }
            .kt-checkbox-list {
                padding: 0 0;
            }
            table {
                border-collapse: collapse;
                direction: ltr;
                text-align: left;
            }
            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
                background-color: transparent;
            }
            .table-bordered {
                border: 1px solid #ebedf2;
            }
            .table th, .table td {
                padding: 0.75rem;
                vertical-align: top;
                border-top: 1px solid #ebedf2;
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #ebedf2;
            }
            body{
                overflow-x: hidden;
                font-family: DejaVu Sans, sans-serif;
                font-size: 15px;
                direction: ltr;
                text-align: left;
            }
            .kt-form__body{
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
                background: #FFF;
                text-align: left;
            }
        </style>
        {{-- @include('Layouts.head') --}}
    </head>
    <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize kt-page--loading text-left" dir="ltr">
        <div class="kt-form__body">
            <div class="kt-form__section kt-form__section--first">
                <div class="kt-wizard-v1__review">
                    {{var_dump($course)}}
                </div>
            </div>
        </div>
    </body>
</html>
