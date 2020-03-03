<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Qty</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->items as $item) : ?>
            <tr>
                <td><?= $item->name ?></td>
                <td><?= $item->qty ?></td>
                <td><?= number_format($item->price, 0, '', '.') ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>