<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <title>Task Assignment Invitation</title>
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
        <p>Dear {{$organizer_name}}, You been selected as a nominee to participate with us as <mark><em><strong><u>{{$workingfields}}</u></strong></em></mark> organizer, Please check the details below.</p>
        <div>
           
           @if(isset($conference_name))
           
           <table>
               <thead>
                   <tr>
                       <th colspan="2">Conference Information</th>
                   </tr>
               </thead>
               <tbody>
                   <tr>
                       <th>Conference Name</th>
                       <td>{{$conference_name}}</td>
                   </tr>
                   <tr>
                       <th>Venue</th>
                       <td>{{$conference_venue}}</td>
                   </tr>
                   <tr>
                       <th>From</th>
                       <td>{{$conference_from}}</td>
                   </tr>
                   <tr>
                       <th>To</th>
                       <td>{{$conference_to}}</td>
                   </tr>
               </tbody>
           </table>
           
           @endif
           
            <table>
                <thead>
                    <tr>
                        <th colspan="2">Task Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Title</th>
                        <td>{{$title}}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{$description}}</td>
                    </tr>
                    <tr>
                        <th>From</th>
                        <td>{{$task_from}}</td>
                    </tr>
                    <tr>
                        <th>To</th>
                        <td>{{$task_to}}</td>
                    </tr>
                </tbody>
            </table>
            
        </div>
        <div class="text-center">
            <h4>Do you accept this assignment ?<a href="{{asset('/task/mailresponse/yes/'.$token_mail)}}">Yes</a><a href="{{asset('/task/mailresponse/no/'.$token_mail)}}">No</a></h4>
        </div>
    </body>
</html>