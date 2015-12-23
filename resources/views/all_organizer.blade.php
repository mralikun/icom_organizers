
<style>
    table {
        border:5px solid black;
    }
    #line{
        background-color: #d9d9d9;
    }
</style>
<table>

    <tr>
        <td>#</td>
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
    <?php $id=0; ?>
    @foreach($all_organizers as $organizer)
    <tr>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$id+1}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['name']}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['email']}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['address']}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['phone']}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['date_of_birth']}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['id_number']}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['organizer_grade_in_alltasks']}}</td>
        <td rowspan="{{count($organizer['conferences'])+1}}">{{$organizer['workingfields']}}</td>

    </tr>
        @foreach($organizer['conferences'] as $conference)
            <tr>
                <td></td>
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
       <?php $id++  ?>
        <tr id="line">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>

   @endforeach
</table>


