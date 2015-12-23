
<table border="1">
    <tr>
        <td>Name</td>
        <td>Email</td>
        <td>Address</td>
        <td>Phone</td>
        <td>Date of birth</td>
        <td>ID number</td>
        <td>Organizer grade in all tasks</td>
        <td>Working fields</td>
        <td>Name of conference</td>
        <td>Start of the conference</td>
        <td>End of the conference</td>
        <td>Place of conference</td>
        <td>Grade</td>
        <td>Working days</td>
    </tr>
    <tr>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['name']}}</td>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['email']}}</td>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['address']}}</td>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['phone']}}</td>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['date_of_birth']}}</td>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['id_number']}}</td>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['organizer_grade_in_alltasks']}}</td>
        <td rowspan="{{count($data['conferences'])+1}}">{{$data['workingfields']}}</td>
    </tr>
    @foreach($data['conferences'] as $conference)
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$conference['name']}}</td>
            <td>{{$conference['from']}}</td>
            <td>{{$conference['to']}}</td>
            <td>{{$conference['venue']}}</td>
            <td>{{$conference['organizer_grade_in_this_conference']}}</td>
            <td>{{$conference['working_days']}}</td>
        </tr>
    @endforeach

</table>