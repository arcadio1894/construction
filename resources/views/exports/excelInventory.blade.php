<!DOCTYPE html>
<html lang="en">
<head>
    <style>

        body {
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        #table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        #table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #sumary tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }

        #table th,
        #table td {
            text-align: center;
        }

        #table th {
            padding: 5px 10px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: bold;
            background-color: #7A8DC5;
            font-size: 1.2em;
        }

        #table .desc {
            text-align: left;
        }

        #table td {
            padding: 5px;
            text-align: center;
        }

        #table td.desc {
            vertical-align: top;
        }

        #table td.unit,
        #table td.qty,
        #table td.total {
            font-size: 1em;
            text-align: right;
        }

        .center {
            text-align: center;
        }
    </style>

</head>
<body>
<h1>{{ $title }}</h1>
<table id="table">
    <thead>
        <tr>
            <th width="80px" style="background-color: #7A8DC5; font-size: 14px">Código</th>
            <th width="600px" style="background-color: #7A8DC5; font-size: 14px">Material</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px">Stock Sistema</th>
            <th width="100px" style="background-color: #7A8DC5; font-size: 14px">Stock Físico</th>
            <th width="200px" style="background-color: #7A8DC5; font-size: 14px">Ubicaciones</th>
        </tr>
    </thead>
    <tbody>
    @for ( $i = 0; $i<count($materials); $i++ )
        @if ( ($i+1) % 2 == 0)
        <tr>
            <th width="80px">{{ $materials[$i]['code'] }}</th>
            <th width="600px">{{ $materials[$i]['material'] }}</th>

            @if( $materials[$i]['stock_current'] == 0 )
                <th width="100px" style="color: red">{{ $materials[$i]['stock_current'] }}</th>
            @else
                <th width="100px">{{ $materials[$i]['stock_current'] }}</th>
            @endif

            <th width="100px">{{ $materials[$i]['inventory'] }}</th>
            <th width="200px">{{ $materials[$i]['location'] }}</th>
        </tr>
        @else
            <tr>
                <th width="80px" style="background-color: #D0E4F7">{{ $materials[$i]['code'] }}</th>
                <th width="600px" style="background-color: #D0E4F7">{{ $materials[$i]['material'] }}</th>
                @if( $materials[$i]['stock_current'] == 0 )
                    <th width="100px" style="background-color: #D0E4F7; color: red">{{ $materials[$i]['stock_current'] }}</th>
                @else
                    <th width="100px" style="background-color: #D0E4F7">{{ $materials[$i]['stock_current'] }}</th>
                @endif
                <th width="100px" style="background-color: #D0E4F7">{{ $materials[$i]['inventory'] }}</th>
                <th width="200px" style="background-color: #D0E4F7">{{ $materials[$i]['location'] }}</th>
            </tr>
        @endif
    @endfor
    </tbody>
</table>
</body>
</html>