<?php
require 'config.php';

$db;
$defaultStartTime = date('Y-m-d H:i:s', strtotime('-1 hour'));
$defaultEndTime = date('Y-m-d H:i:s');
$startTime = isset($_GET['start']) ? $_GET['start'] : $defaultStartTime;
$endTime = isset($_GET['end']) ? $_GET['end'] : $defaultEndTime;

// Check if the "Reset" button is clicked
if (isset($_GET['reset'])) {
  $startTime = $defaultStartTime;
  $endTime = $defaultEndTime;
}

$sql = "SELECT `Temperature`, `Humidity`, `number`, `date time`, `Temperature2`, `Humidity2` FROM `MRI2` WHERE `date time` BETWEEN '$startTime' AND '$endTime' ORDER BY `date time` DESC";
$result = $db->query($sql);
if (!$result) {
  { echo "Error: " . $sql . "<br>" . $db->error; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>TEMPERATURE AND RH MONITORING</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .chart {
      width: 100%;
      min-height: 450px;
    }
    .row {
      margin:0 !important;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h1>TEMPERATURE AND RH MONITORING </h1>
        <p><a href="#">mohamedIyad</a></p>
      </div>
      <div class="clearfix"></div>

      <div id="chart_trend" class="chart"></div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <form class="form-inline justify-content-center" method="GET" action="">
          <div class="form-group mb-2">
            <label for="start">Start Time:</label>
            <input type="datetime-local" class="form-control" id="start" name="start" value="<?php echo $startTime; ?>">
          </div>
          <div class="form-group mx-sm-3 mb-2">
            <label for="end">End Time:</label>
            <input type="datetime-local" class="form-control" id="end" name="end" value="<?php echo $endTime; ?>">
          </div>
          <button type="submit" class="btn btn-primary mb-2">Update</button>
          <button type="submit" class="btn btn-secondary mb-2" name="reset">Reset</button> <!-- Add the Reset button -->
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Temperature</th>
              <th scope="col">Humidity</th>
              <th scope="col">Date Time</th>
              <th scope="col">Temperature2</th>
              <th scope="col">Humidity2</th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 1; while($row = mysqli_fetch_assoc($result)) {?>
              <tr>
                <td><?php echo $row['Temperature']; ?></td>
                <td><?php echo $row['Humidity']; ?></td>
                <td><?php echo $row['date time']; ?></td>
                <td><?php echo $row['Temperature2']; ?></td>
                <td><?php echo $row['Humidity2']; ?></td>
              </tr>
              <?php $count++; ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('datetime', 'Date Time');
      data.addColumn('number', 'Temperature');
      data.addColumn('number', 'Humidity');

      data.addRows([
        <?php mysqli_data_seek($result, 0); while($row = mysqli_fetch_assoc($result)) {?>
          [new Date('<?php echo $row['date time'];?>'), <?php echo $row['Temperature'];?>, <?php echo $row['Humidity'];?>],
        <?php } ?>
      ]);

      var options = {
        title: 'Temperature and Humidity Trend',
        width: 1300,
        height: 480,
        curveType: 'function',
        legend: { position: 'bottom' },
        hAxis: {
          format: 'dd MMM yyyy HH:mm',
          gridlines: { count: 15 }
        },
        vAxes: {
          0: { title: 'Temperature (°C)' },
          1: { title: 'Humidity (%)' }
        },
        series: {
          0: { targetAxisIndex: 0 },
          1: { targetAxisIndex: 1 }
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_trend'));
      chart.draw(data, options);
    }
    
    // Auto-refresh the page every 15 seconds
    setInterval(function() {
      location.reload();
    }, 15000);
  </script>
</body>
</html>
