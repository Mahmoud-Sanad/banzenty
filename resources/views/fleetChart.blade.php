@extends('layouts.admin')
@section('content')
<div class="container-fluid mt--8">
    <h3>Wallet : {{$myWallet->money}} EGP </h3>
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
              <canvas id="money" class="chart-canvas"></canvas>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>


@endsection
<script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
<script src="https://fastly.jsdelivr.net/npm/chart.js"></script>


@javascript('users', $users)

<script>
  $(function() {


    $('.date_filter').change(function() {
      let value = $(this).val();
      //alert(value)

      $('#dateForm').submit();
    })

    var x = {
      "message": "success",
      "data": users
    }
    var ctx = document.getElementById('users');
    var names = []
    var num = []
    x.data.forEach(e => {
      names.push(e.name)
      num.push(e.total_litres)
    })
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: names,
        datasets: [{
          label: '# of litres',
          data: num,
          borderWidth: 1,
          backgroundColor : ['rgba(0,255,0,0.5)'],
          hoverBackgroundColor: ['rgba(54, 162, 235, 0.8)']
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
    var x = {
      "message": "success",
      "data": users
    }
    var ctx = document.getElementById('money');
    var names = []
    var num = []
    x.data.forEach(e => {
      names.push(e.name)
      num.push(e.total_money)
    })
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: names,
        datasets: [{
          label: '# of EGP',
          data: num,
          borderWidth: 1,
          backgroundColor : ['rgba(255,0,0,0.5)'],
          hoverBackgroundColor: ['rgba(54, 162, 235, 0.8)']
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
