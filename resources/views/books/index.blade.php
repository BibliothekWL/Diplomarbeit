<html lang="eng">

<body>
<h1>books</h1>
<ul>

@foreach ($books as $book)
<li> {{$book->title}} </li>
@endforeach
</ul>
</body>
</html>
