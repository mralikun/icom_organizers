        <table border="1">
            <tr>
                <td >name</td>
                <td>from</td>
                <td>to</td>
                <td>venue</td>
                <td>organizer</td>
            </tr>
            @foreach($conferance_array as $conferance)

            <tr rowspan="{{count($conferance['organizers'])+1}}">
                <td>{{$conferance['name']}}</td>
                <td>{{$conferance['from']}}</td>
                <td>{{$conferance['to']}}</td>
                <td>{{$conferance['venue']}}</td>
            </tr>
                @foreach($conferance['organizers'] as $organizer)

                <tr>
                        <td>{{$organizer->name}}</td>
                        <td>{{$organizer->email}}</td>
                        <td>{{$organizer->phone}}</td>
                        <td>{{$organizer->id_number}}</td>
                </tr>
                @endforeach


            @endforeach

        </table>

