<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Task Finish Notification</title>
     <style>
          body {
               font-family: Arial, sans-serif;
               color: #333;
               line-height: 1.6;
          }

          .container {
               width: 80%;
               margin: 0 auto;
               padding: 20px;
               border: 1px solid #ddd;
               background-color: #f9f9f9;
          }

          .header {
               text-align: center;
               margin-bottom: 20px;
          }

          .header h1 {
               font-size: 24px;
               color: #4CAF50;
          }

          .project-table {
               width: 100%;
               border-collapse: collapse;
               margin-top: 20px;
          }

          .project-table th,
          .project-table td {
               padding: 10px;
               text-align: left;
               border: 1px solid #ddd;
          }

          .project-table th {
               background-color: #515454;
               color: white;
          }

          .footer {
               text-align: center;
               margin-top: 20px;
               font-size: 14px;
          }

          .reminder {
               font-weight: bold;
               color: red;
               margin-bottom: 10px;
          }
     </style>
</head>

<body>
     <div class="container">
          <div class="header">
               <h1>Task Finish Notification</h1>
          </div>

          @foreach ($taskData as $projectId => $tasks)
               <h2>Project: {{ $tasks->first()->project->name }}</h2>

               <table class="project-table">
                    <thead>
                         <tr>
                              <th>Task Number</th>
                              <th>Task Name</th>
                              <th>Finish Date</th>
                              <th>Reminder</th>
                         </tr>
                    </thead>
                    <tbody>
                         @php
                              $sortedTasks = $tasks->sortBy(function ($task) {
                                  $finishDate = Carbon\Carbon::parse($task->finish_date);
                                  $hoursToFinish = Carbon\Carbon::now()->diffInHours($finishDate, false);

                                  return $hoursToFinish;
                              });
                         @endphp

                         @foreach ($sortedTasks as $task)
                              <tr>
                                   <td>{{ $task->task_number }}</td>
                                   <td>{{ $task->task }}</td>
                                   <td>{{ Carbon\Carbon::parse($task->finish_date)->format('d-m-Y') }}</td>
                                   <td>
                                        <span class="reminder">Berakhir hari ini</span>
                                   </td>
                              </tr>
                         @endforeach

                    </tbody>
               </table>
          @endforeach

          <div class="footer">
               Regards, <br>
               {{ config('app.app_name') }}
          </div>
     </div>
</body>

</html>
