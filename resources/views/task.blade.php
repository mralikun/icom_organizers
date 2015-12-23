
<form method="post" action="/users/1">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <label>title</label>
    <input type="text" name="title">
    </br>
    <label>description</label>
    <input type="text" name="description">
    </br>
    <label>date</label>
    <input type="text" name="date">
    </br>
    <label>type</label>
    <input type="text" name="type">
    </br>
    <label>organizer_id</label>
    <input type="text" name="organizer_id">
    </br>
    <input type="submit" name="add" value="task">

</form>