<!DOCTYPE html>
<html>
<head>
    <title></title>

    <!--
	You can put your custom CSS if you wish
    -->
</head>
<body>
    <h3><p>TO: {{$content['email']}}</p></h3>
    <h2><p>{{$content['note']}}</p></h2>
    <h2><p>Request ID: {{$content['req_id']}}</p></h2>
    
    @if (($content['remark'])!='')
        <h3><p>Remark: </p></h3>
        <p>{{$content['remark']}}</p>
    @endif
</body>
</html>