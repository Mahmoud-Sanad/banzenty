@extends('layouts.admin')
@section('content')
<div class="container-fluid mt--8">
  <div class="row">
    <div class="col-xl-4">
      <div class="card bg-gradient-default shadow">
        <div class="card-header bg-transparent">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="text-uppercase text-muted ls-1 mb-1">{{ trans('cruds.charts.fields.performance') }}</h6>
              <h2 class="text-black mb-0">{{ trans('cruds.charts.fields.top_users') }}</h2>
            </div>
          </div>
        </div>
        <div class="card-body">
          <!-- Chart -->
          <div class="chart">
            <!-- Chart wrapper -->
            <canvas id="users" class="chart-canvas"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <div class="card shadow">
        <div class="card-header bg-transparent">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="text-uppercase text-muted ls-1 mb-1">{{ trans('cruds.charts.fields.performance') }}</h6>
              <h2 class="mb-0">{{ trans('cruds.charts.fields.top_stations') }}</h2>
            </div>
          </div>
        </div>
        <div class="card-body">
          <!-- Chart -->
          <div class="chart">
            <canvas id="stations" class="chart-canvas"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt--7">
  <div class="row">
    <div class="col-xl-8 mb-5 mb-xl-0">
      <div class="card shadow">
        <div class="card-header bg-transparent">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="text-uppercase text-muted ls-1 mb-1">{{ trans('cruds.charts.fields.performance') }}</h6>
              <h2 class="mb-0">{{ trans('cruds.charts.fields.services') }}</h2>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class='col-sm-4'>
              <form method="get" id="dateForm">
                <input type="date" id='date_from' name='date_from' value="{{request()->date_from}}" class="form-control date_filter">
                <input type="date" id='date_to' name='date_to' value="{{request()->date_to}}" class="form-control date_filter">
              </form>
            </div>
          </div>
          <!-- Chart -->
          <canvas id="services" class="chart-canvas"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script src="https://fastly.jsdelivr.net/npm/chart.js"></script>


@javascript('users', $users)
@javascript('stations', $stations)
@javascript('services', $services)
<script>
  $(function() {


    $('.date_filter').change(function() {
      let value = $(this).val();
      //alert(value)

      $('#dateForm').submit();
    })
    console.log(services);
    console.log(stations);
    var x = {
      "message": "success",
      "data": users
    }
    var ctx = document.getElementById('users');
    var names = []
    var num = []
    x.data.forEach(e => {
      names.push(e.name)
      num.push(e.orders_count)
    })
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: names,
        datasets: [{
          label: '# of orders',
          data: num,
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    var y = {
      "message": "success",
      "data": stations
    }
    var ctx = document.getElementById('stations');
    var names = []
    var num = []
    y.data.forEach(e => {
      names.push(e.name.en)
      num.push(e.orders_count)
    })
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: names,
        datasets: [{
          label: '# of orders',
          data: num,
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    var s = {
      "message": "success",
      "data": services
    }
    var ctx = document.getElementById('services');
    var names = []
    var num = []
    var usage = []
    console.log(s.data, 'data');
    s.data.forEach(e => {
      let element = [
        e.name.en,
        e.total_price
      ]

      names.push(element)
      //num.push(e.total_price)
      usage.push(e.orders_count)
    })
    console.log(num);
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: names,
        datasets: [{
          label: '# of usage',
          data: usage,
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });


  })
</script>
