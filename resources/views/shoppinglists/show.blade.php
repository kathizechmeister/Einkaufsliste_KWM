<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>


<ul>
    <h1>{{$shoppinglist->title }}</h1>
    <p><b>Tatsächliche Kosten: </b>{{ $shoppinglist->costs}}</p>
    <p><b>Deadline:</b> {{ $shoppinglist->deadline}}</p>
    <p><b>Ersteller:</b> {{ $shoppinglist->user_id}}</p>
    <p><b>Helper:</b> {{ $shoppinglist->helper_idx}}</p>
</ul>


</body>
</html>