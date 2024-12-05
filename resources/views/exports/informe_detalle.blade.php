
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle ejectadas</title>
</head>
<body>
    <table>
        <thead>
            @if(COUNT($data) != 0)
                    <tr>
                        @foreach ($data[0] as $key => $value)
                            @if($key != "ID_AUDITORIA" && $key != "ID_ESTADO" && $key != "latitud" && $key != "longitud")
                                <th style="
                                height:30px;
                                text-align: center;
                                font-weight: bold;
                                background-color: #44505c;
                                color:#ffffff;
                                ">{{$key}}</th>
                            @endif
                        @endforeach
                    </tr>
            @endif
        </thead>
        <tbody>
            @foreach ($data as $key_data => $value_data)
                    <tr>
                        @foreach (get_object_vars($value_data) as $k => $v)
                            @if($k != "ID_AUDITORIA" && $k != "ID_ESTADO" && $k != "latitud" && $k != "longitud")
                                <td>{{$v}}</td>
                            @endif
                        @endforeach
                    </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
