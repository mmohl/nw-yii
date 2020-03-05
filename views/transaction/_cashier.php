<?php

use app\assets\CashierAsset;

CashierAsset::register($this);
?>

<style>
    table tfoot tr td:first-child {
        font-weight: bold;
    }
</style>
<div class="row">
    <!-- datatable -->
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-body">
                <table id="table-customer-list" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Pemesan</th>
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
        <div class="panel panel-default">
            <div class="panel-wrapper collapse in" aria-expanded="true">
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
                            <tr>
                                <td colspan="2">Sebelum Rounding</td>
                                <td><input type="text" readonly class="form-control inputs" id="input-before-rounding"></td>
                            </tr>
                            <tr>
                                <td colspan="2">Nilai Rounding</td>
                                <td><input type="text" readonly class="form-control inputs" id="input-rounding"></td>
                            </tr>
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
                                <td colspan="3"><button disabled class="btn btn-primary btn-sm" type="button" id="btn-invoice-pay">Bayar</button></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>