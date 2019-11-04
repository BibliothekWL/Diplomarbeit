<html lang="eng">

<body>
<h1>create new book</h1>

<form method="post" action="/books">
    {{csrf_field()}}
    <div>
        <input type="text" name="title" placeholder="book title">
    </div>

    <div>
        <input type="number" name="author_id" placeholder="author_id">
    </div>

    <div>
        <input type="text" name="systematik" placeholder="systematik">
    </div>

     <div>
        <input type="text" name="medium" placeholder="medium">
    </div>

    <div>
        <input type="text" name="content" placeholder="content">
    </div>

    <div>
        <input type="number" name="BNR" placeholder="BNR">
    </div>

    <div>
        <button type="submit">add Book</button>
    </div>
</form>
</body>
</html>
