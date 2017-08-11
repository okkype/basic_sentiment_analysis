<?php

$url = 'http://qa.forca.id:82/crm-ws/query';
$data = array(
    'uuid' => '1bb65fb5-890f-4ad4-aea7-d47e75a1fd33',
    'query' => 'select adu."name", cmce.created, cmce.characterdata from cm_chatentry cmce left join ad_user adu on adu.ad_user_id = cmce.createdby'
    );

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

$ar_results = json_decode(gzuncompress($result));

// var_dump($ar_results);
?>
<table border="1">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Komentar</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach ($ar_results as $ar_result) {
    ?>
        <tr>
            <td><?= $ar_result->name ?></td>
            <td><?= $ar_result->created ?></td>
            <td><?= $ar_result->characterdata ?></td>
        </tr>
    <?php
}
?>
    </tbody>
</table>
<?php
