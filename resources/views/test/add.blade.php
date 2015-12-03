<form method="POST" action="/save" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <label>name</label>
    <input type="text" name="name">
    <input type="submit" name="add" value="add">

</form>