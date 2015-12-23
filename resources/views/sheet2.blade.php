
<table border="1">
    <tr>
        <td>Name</td>
        <td>Start of the conference</td>
        <td>End of the conference</td>
        <td>Place of conference</td>
        <td>Organizer name</td>
        <td>Organizer email</td>
        <td>Organizer phone</td>
        <td>Organizer ID number</td>
        <td>Grade in all tasks</td>
        <td>Grade in this task</td>
        <td>Working field</td>
    </tr>
    <tr>
        <td rowspan="{{count($data['organizer'])+1}}">{{$data['name']}}</td>
        <td rowspan="{{count($data['organizer'])+1}}">{{$data['from']}}</td>
        <td rowspan="{{count($data['organizer'])+1}}">{{$data['to']}}</td>
        <td rowspan="{{count($data['organizer'])+1}}">{{$data['venue']}}</td>
    </tr>
        @foreach($data['organizer'] as $organizer)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$organizer['name']}}</td>
                <td>{{$organizer['email']}}</td>
                <td>{{$organizer['phone']}}</td>
                <td>{{$organizer['id_number']}}</td>
                <td>{{$organizer['organizer_grade_in_alltasks']}}</td>
                <td>{{$organizer['organizer_grade_in_thistask']}}</td>
                <td>{{$organizer['working_field']}}</td>
            </tr>
        @endforeach

</table>

