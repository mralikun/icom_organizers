<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Teamleader Notification</title>
        <style>

            tr {
                margin: 10px 25px;
            }

            th , td {
                padding: 10px;
            }

        </style>
    </head>

    <body>
        <h2>ICOM Organizers</h2>
        @if(isset($flag))
            @if($flag == 'yes')
            <p>Organizer {{$organizer_name}}[<em><strong>ID: {{$organizer_id}}</strong></em>] has joined your team to participate as organizer.</p>
            @else
            <p>Organizer {{$organizer_name}} has refused the task.</p>
            @endif

        @else
        <p>Organizer {{$organizer_name}} has been sent an invitation to join your team and we are awaiting the reply.</p>
        @endif

        <div class="contact-info">
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Contacting Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>E-mail</th>
                        <td>{{$organizer_email}}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{$organizer_phone}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>

</html>