
@foreach($messages as $message)
  <a href="/update/{{$message->id}}">{{$message->message}}</a>
    </br>
    @endforeach
