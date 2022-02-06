<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8"/>
    <title>
        @section('title')
            {{ config('app.name') }} ::
        @show
    </title>

    <style>
        body {
            background:#f1f1f1;
        }

        .portlet {
            background-color: #26a69a;
            border-style: none solid solid;
            border-width: 0 1px 1px;
            border-color: #26a69a;
        }

        .portlet-title {
            border: 0 none;
            color: #ffffff;
            font-weight: 400;
            padding: 10px 15px;
            font-family: Arial;
            font-size: 14px;
        }

        .portlet-body {
            background-color: #fff;
            padding: 10px;
            font-family: Arial;
            font-size: 14px;
        }
    </style>
</head>

<body>

<table border='0' cellpadding='0' cellspacing='0' class='portlet' width='940' align='center'>
    <tr height='1'>
        <td width='100%' class="portlet-title"><strong>{{ config('app.name') }}</strong></td>
    </tr>
    <tr>
        <td class='portlet-body'>
            @yield('content')
        </td>
    </tr>
</table>

</body>

</html>