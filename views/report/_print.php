<h1 style="text-align: center;">Laporan Penjualan</h1>
<table width="100%">
    <thead>
        <tr>
            <th style="text-align: center;">Tanggal</th>
            <th style="text-align: center;">No. Nota/Bill</th>
            <th style="text-align: center;">Jumlah Bill</th>
            <th style="text-align: center;">Omzet</th>
            <th style="text-align: center;">Pajak 10%</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $day => $groupOrders) : ?>
            <tr>
                <td><?= $day ?></td>
                <td><?= !$groupOrders ? '-' : $day . date('-m-Y') ?></td>
                <td style="text-align: right;" ><?= !$groupOrders ? '-' : $groupOrders->count() ?></td>
                <td style="text-align: right;" ><?= !$groupOrders ? '-' : number_format($groupOrders->reduce(fn($prev, $next) => $prev += $next->getOrderAmount(), 0), 0, ',', '.') ?></td>
                <td style="text-align: right;" ><?= !$groupOrders ? '-' : number_format($groupOrders->reduce(fn($prev, $next) => $prev += $next->getOrderTax(), 0), 0, ',', '.') ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: center;font-weight: bolder;"><h5>Total</h5></td>
            <td style="text-align: right; justify-content: center;font-weight: bolder;">
                <?= 
                    number_format($orders->reduce(function($prev, $group){
                        if (!$group) return $prev + 0;
                        $totalGroup = $group->reduce(function($p, $n){
                            return $p += $n->getOrderAmount();
                        }, 0);
                        return $prev + $totalGroup;
                        }, 0), 0, ',', '.')
                ?>
            </td>
            <td style="text-align: right;font-weight: bolder;">
                <?= 
                    number_format($orders->reduce(function($prev, $group){
                        if (!$group) return $prev + 0;
                        $totalGroup = $group->reduce(function($p, $n){
                            return $p += $n->getOrderTax();
                        }, 0);
                        return $prev + $totalGroup;
                        }, 0), 0, ',', '.')
                ?>
            </td>
        </tr>
    </tfoot>
</table>