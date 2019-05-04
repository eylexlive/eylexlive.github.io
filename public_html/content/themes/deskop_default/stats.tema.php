<?php
@$link = get("link") ? get("link") : false;
if(!$link){
    go(URL);
    exit;
}
$get_stats = query("SELECT * FROM stats WHERE stats_slug='$link'");
if (rows($get_stats) > 0){
    $get_stats = row($get_stats);

    $stats_json = json_decode($get_stats["stats_json"],true);
    $mys = new HBM(
        $stats_json['settings']['mysql']['host'],
        $stats_json['settings']['mysql']['user'],
        $stats_json['settings']['mysql']['pass'],
        $stats_json['settings']['mysql']['db']
    );

    $mysql_table = $stats_json['settings']['mysql']['table'];
    $sirala = !empty($stats_json['settings']['siralama'])?"ORDER BY ".$stats_json['settings']['siralama']." DESC":null;
    $limit = !empty($stats_json['settings']['limit'])?"LIMIT ".$stats_json['settings']['limit']:null;
    $g = $mys->query("SELECT * FROM $mysql_table $sirala $limit");
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><?=$get_stats["stats_adi"]?></div>
        <table class="table table-striped">
            <thead>
            <tr>
                <?php foreach ($stats_json["table"] as $value): ?>
                    <th><?=$value["title"]?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($g as $key => $value): ?>
                <tr>
                    <?php foreach ($stats_json["table"] as $key => $v): ?>
                        <td><?=str_ireplace("[GET]",$value[$key],$v["build"])?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php }else{ go(URL); } ?>