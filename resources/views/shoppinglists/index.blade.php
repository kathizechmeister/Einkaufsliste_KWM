<!DOCTYPE html>
<html >
<head >
    <title ></title >
</head >
<body >
<h1 > Einkaufslisten </h1 >
<ul >
    @foreach($shoppinglists as $shoppinglist)
        <li ><a href= "shoppinglists/{{$shoppinglist->id }} " >
        {{$shoppinglist->title }}</a ></li >
        <li ><a href= "shoppinglists/{{$shoppinglist->id }} " >
        {{$shoppinglist->title }}</a ></li >
    @endforeach
</ul >
</body >
</html >