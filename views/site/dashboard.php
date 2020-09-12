<?php
// $this->registerJsFile = '';
$this->title = 'Dashboard';

$this->params['breadcrumbs'] = [];

$this->registerJsFile(
    '@web/js/dashboard.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

$this->registerJsFile(
  '@web/concept/assets/vendor/charts/c3charts/c3.min.js',
  ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
  '@web/concept/assets/vendor/charts/c3charts/d3-5.4.0.min.js',
  ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
  '@web/concept/assets/vendor/charts/c3charts/C3chartjs.js',
  ['depends' => [\yii\web\JqueryAsset::class]]
);

$this->registerJsFile('@web/libraries/chart.js/dist/Chart.min.js');
$this->registerCssFile("@web/libraries/chart.js/dist/Chart.min.css");
?>

<div class="ecommerce-widget">

  <div class="row">
    <!-- ============================================================== -->
    <!-- sales  -->
    <!-- ============================================================== -->
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
      <div class="card border-3 border-top border-top-primary">
        <div class="card-body">
          <h5 class="text-muted">Penjualan</h5>
          <div class="metric-value d-inline-block">
            <h1 id="data-sales" class="mb-1">0</h1>
          </div>
          <div class="metric-label d-inline-block float-right font-weight-bold" id="data-sales-percentage"></div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end sales  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- new customer  -->
    <!-- ============================================================== -->
    <!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
      <div class="card border-3 border-top border-top-primary">
        <div class="card-body">
          <h5 class="text-muted">Pembeli Baru</h5>
          <div class="metric-value d-inline-block">
            <h1 id="data-new-customers" class="mb-1">0</h1>
          </div>
          <div class="metric-label d-inline-block float-right text-success font-weight-bold">
            <span class="icon-circle-small icon-box-xs text-success bg-success-light"><i class="fa fa-fw fa-arrow-up"></i></span><span class="ml-1">10%</span>
          </div>
        </div>
      </div>
    </div> -->
    <!-- ============================================================== -->
    <!-- end new customer  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- visitor  -->
    <!-- ============================================================== -->
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
      <div class="card border-3 border-top border-top-primary">
        <div class="card-body">
          <h5 class="text-muted">Pembeli</h5>
          <div class="metric-value d-inline-block">
            <h1 id="data-customers" class="mb-1">0</h1>
          </div>
          <div class="metric-label d-inline-block float-right font-weight-bold" id="data-customers-percentage"></div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end visitor  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- total orders  -->
    <!-- ============================================================== -->
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
      <div class="card border-3 border-top border-top-primary">
        <div class="card-body">
          <h5 class="text-muted">Jumlah Pesanan</h5>
          <div class="metric-value d-inline-block">
            <h1 id="data-orders" class="mb-1">0</h1>
          </div>
          <div class="metric-label d-inline-block float-right font-weight-bold" id="data-orders-percentage"></div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end total orders  -->
    <!-- ============================================================== -->
  </div>

  <div class="row">
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
      <div class="card">
        <h5 class="card-header">Penjualan berdasarkan menu</h5>
        <div class="card-body">
          <div id="c3chart_category" style="height: 335px;"></div>
        </div>
      </div>
    </div>
    <!-- recent orders  -->
    <!-- ============================================================== -->
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
      <div class="card">
        <h5 class="card-header">Pesanan Terbaru</h5>
        <div class="card-body p-0" style="overflow: auto; min-height: 375px; max-height: 374px; overflow: auto;">
          <div class="table-responsive">
            <table class="table" id="table-unpaid-orders">
              <thead class="bg-light">
                <tr class="border-0">
                  <th class="border-0">#</th>
                  <th class="border-0">Kode Pesanan</th>
                  <th class="border-0">Pemesan</th>
                  <th class="border-0">No. Meja</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end recent orders  -->
  </div>
</div>