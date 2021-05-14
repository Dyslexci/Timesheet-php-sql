
<?php

$connect = new PDO("mysql:host=localhost;dbname=testingdadwork", "root", "");

$start_date_error = '';
$end_date_error = '';

if(isset($_POST["export"]))
{
    if(empty($_POST["start_date"]))
    {
        $start_date_error = '<label class="text-danger">Start Date is required</label>';
    }
    else if(empty($_POST["end_date"]))
    {
        $end_date_error = '<label class="text-danger">End Date is required</label>';
    }
    else
    {
        $file_name = 'Order Data.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/csv;");

        $file = fopen('php://output', 'w');

        $header = array("Username", "Client", "Job Number", "Job Reference", "Coworker Names", "Van Registry", "Date", "Start Time", "Finish Time", "Total Hours");

        fputcsv($file, $header);

        $query = "
        SELECT * FROM timesheet 
        WHERE day_date >= '".$_POST["start_date"]."' 
        AND day_date <= '".$_POST["end_date"]."' 
        ORDER BY username DESC
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row)
        {
        $data = array();
        $data[] = $row["username"];
        $data[] = $row["client"];
        $data[] = $row["job_number"];
        $data[] = $row["job_reference"];
        $data[] = $row["coworker_id"];
        $data[] = $row["van_reg"];
        $data[] = $row["day_date"];
        $data[] = $row["start_time"];
        $data[] = $row["end_time"];
        $data[] = $row["total_hours"];
        fputcsv($file, $data);
    }
    fclose($file);
    exit;
    }
}

$query = "
SELECT * FROM timesheet 
ORDER BY username DESC;
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<html>
 <head>
  <title>Daterange Mysql Data Export to CSV in PHP</title>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
 </head>
 <body>
  <div class="container box">
   <h1 align="center">Timesheet Display and Download</h1>
   <br />
   <div class="table-responsive">
    <br />
    <div class="row">
     <form method="post">
      <div class="input-daterange">
       <div class="col-md-4">
        <input type="text" name="start_date" class="form-control" readonly />
        <?php echo $start_date_error; ?>
       </div>
       <div class="col-md-4">
        <input type="text" name="end_date" class="form-control" readonly />
        <?php echo $end_date_error; ?>
       </div>
      </div>
      <div class="col-md-2">
       <input type="submit" name="export" value="Export" class="btn btn-info" />
      </div>
     </form>
    </div>
    <br />
    <table class="table table-bordered table-striped">
     <thead>
      <tr>
       <th>Employee Name</th>
       <th>Client</th>
       <th>Job Number</th>
       <th>Job Reference</th>
       <th>Coworker Names</th>
       <th>Van Registry</th>
       <th>Date</th>
       <th>Start Time</th>
       <th>End Time</th>
       <th>Total Hours</th>
      </tr>
     </thead>
     <tbody>
      <?php
      foreach($result as $row)
      {
       echo '
       <tr>
        <td>'.$row["username"].'</td>
        <td>'.$row["client"].'</td>
        <td>'.$row["job_number"].'</td>
        <td>$'.$row["job_reference"].'</td>
        <td>'.$row["coworker_id"].'</td>
        <td>'.$row["van_reg"].'</td>
        <td>'.$row["day_date"].'</td>
        <td>'.$row["start_time"].'</td>
        <td>'.$row["end_time"].'</td>
        <td>'.$row["total_hours"].'</td>
       </tr>
       ';
      }
      ?>
     </tbody>
    </table>
    <br />
    <br />
   </div>
  </div>
 </body>
</html>

<script>

$(document).ready(function(){
 $('.input-daterange').datepicker({
  todayBtn:'linked',
  format: "yyyy-mm-dd",
  autoclose: true
 });
});

</script>