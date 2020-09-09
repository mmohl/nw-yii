<?php

use app\assets\CashierAsset;

CashierAsset::register($this);
$this->title = 'Kasir';

$this->params['breadcrumbs'] = [];
?>

<style>
    table tfoot tr td:first-child {
        font-weight: bold;
    }

    table#invoice-detail tbody {
        display: block;
        height: 250px;
        overflow: auto;
    }

    table#invoice-detail thead,
    table#invoice-detail tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    table#invoice-detail tfoot tr td {
        width: 120px;
    }

    table#table-customer-list tbody tr:hover {
        cursor: pointer;
    }
</style>
<div class="row">
    <!-- datatable -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <table id="table-customer-list" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Pemesan</th>
                            <th>No. Meja</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- detail invoice -->
    <div class="col-md-5">
        <div class="card">
            <div class="panel-body">
                <table class="table no-border" id="invoice-detail">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Qty</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Sub Total</td>
                            <td><input type="text" readonly class="form-control inputs" id="input-sub-total"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Pajak</td>
                            <td><input type="text" readonly class="form-control inputs" id="input-tax"></td>
                        </tr>
                        <!-- <tr>
                            <td colspan="2">Sebelum Rounding</td>
                            <td><input type="text" readonly class="form-control inputs" id="input-before-rounding"></td>
                        </tr> -->
                        <!-- <tr>
                            <td colspan="2">Nilai Rounding</td>
                            <td><input type="text" readonly class="form-control inputs" id="input-rounding"></td>
                        </tr> -->
                        <tr>
                            <td colspan="2">Total</td>
                            <td><input type="text" readonly class="form-control inputs" id="input-total"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Bayar</td>
                            <td><input type="text" class="form-control inputs" id="input-payment"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Total Kembali</td>
                            <td><input type="text" readonly class="form-control inputs" id="input-changes"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Abaikan</td>
                            <td><input type="checkbox" class="form-control inputs pull-left" id="input-ignored"></td>
                        </tr>
                        <tr>
                            <td colspan="3"><button disabled class="btn btn-primary btn-sm" type="button" id="btn-invoice-pay">Bayar</button></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>