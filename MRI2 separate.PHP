<?php
require 'config.php';

$db;
$defaultStartTime = date('Y-m-d H:i:s', strtotime('-1 hour'));
$defaultEndTime = date('Y-m-d H:i:s');
$startTime = isset($_GET['start']) ? $_GET['start'] : $defaultStartTime;
$endTime = isset($_GET['end']) ? $_GET['end'] : $defaultEndTime;

$sql = "SELECT `Temperature`, `Humidity`, `date time` FROM `MRI2` WHERE `date time` BETWEEN '$startTime' AND '$endTime' ORDER BY `date time` DESC";
$result = $db->query($sql);
if (!$result) {
  { echo "Error: " . $sql . "<br>" . $db->error; }
}

// Calculate temperature and humidity statistics
$temperatureData = [];
$humidityData = [];
while ($row = mysqli_fetch_assoc($result)) {
  $temperatureData[] = $row['Temperature'];
  $humidityData[] = $row['Humidity'];
}

$maxTemperature = !empty($temperatureData) ? max($temperatureData) : '-';
$minTemperature = !empty($temperatureData) ? min($temperatureData) : '-';
$avgTemperature = !empty($temperatureData) ? array_sum($temperatureData) / count($temperatureData) : '-';
$maxHumidity = !empty($humidityData) ? max($humidityData) : '-';
$minHumidity = !empty($humidityData) ? min($humidityData) : '-';
$avgHumidity = !empty($humidityData) ? array_sum($humidityData) / count($humidityData) : '-';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>MRI Room AC Control</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center">
        <h1>Real Time RH and temperature </h1>
        <p><a href="#">mohamedIyad</a></p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="chart-container">
          <div id="chart_trend" class="chart"></div>
        </div>
      </div>

      <div class="col-lg-500">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Temperature and Humidity Statistics</h5>
            <div class="row">
              <div class="col-sm-05">
                <p class="card-text">Temperature:</p>
                <ulclass="list-group">
                  <li class="list-group-item">Maximum: <?php echo $maxTemperature; ?></li>
                  <li class="list-group-item">Minimum: <?php echo $minTemperature; ?></li>
                  <li class="list-group-item">Average: <?php echo $avgTemperature; ?></li>
                </ul>
              </div>
              <div class="col-sm-05">
                <p class="card-text">Humidity:</p>
                <ul class="list-group">
                  <li class="list-group-item">Maximum: <?php echo $maxHumidity; ?></li>
                  <li class="list-group-item">Minimum: <?php echo $minHumidity; ?></li>
                  <li class="list-group-item">Average: <?php echo $avgHumidity; ?></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <form class="form-inline justify-content-center" method="GET" action="">
          <div class="form-group mb-2">
            <label for="start">Start Time:</label>
            <input type="datetime-local" class="form-control" id="start" name="start" value="<?php echo date('Y-m-d\TH:i', strtotime($startTime)); ?>">
          </div>
          <div class="form-group mx-sm-3 mb-2">
            <label for="end">End Time:</label>
            <input type="datetime-local" class="form-control" id="end" name="end" value="<?php echo date('Y-m-d\TH:i', strtotime($endTime)); ?>">
          </div>
          <button type="submit" class="btn btn-primary mb-2">Update</button>
          <button type="button" class="btn btn-secondary mb-2" onclick="resetDateTime()">Reset</button>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Temperature</th>
              <th scope="col">Humidity</th>
              <th scope="col">Date Time</th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 1; mysqli_data_seek($result, 0); while($row = mysqli_fetch_assoc($result)) {?>
              <tr>
                <th scope="row"><?php echo $count; ?></th>
                <td><?php echo $row['Temperature']; ?></td>
                <td><?php echo $row['Humidity']; ?></td>
                <td><?php echo $row['date time']; ?></td>
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
        width: 1600,
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

    function resetDateTime() {
      var defaultStartTime = new Date(new Date().getTime() - 3600000).toISOString().slice(0, -8);
      var defaultEndTime = new Date().toISOString().slice(0, -8);
      document.getElementById('start').value = defaultStartTime;
      document.getElementById('end').value = defaultEndTime;
      document.querySelector('form').submit(); // Submit the form
    }
  </script>
</body>
</html>
