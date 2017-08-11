<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="script/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="script/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="script/modernizr.min.js"></script>

        <link href="script/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="script/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="script/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">

        <!--        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
                <link rel="stylesheet" href="https://pingendo.github.io/templates/blank/theme.css" type="text/css"> </head>-->

    <body>
        <div class="navbar navbar-default navbar-static-top no-print">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#navbar-ex-collapse">
                        <span class="sr-only">Toggle navigation</span> <span
                            class="icon-bar"></span> <span class="icon-bar"></span> <span
                            class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="navbar-ex-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="analisa.php">Analisa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="upload.php">Upload</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" class="">
                            <div class="form-group"> <label>Kalimat</label>
                                <input value="<?= isset($_POST['kalimat']) ? $_POST['kalimat'] : '' ?>" name="kalimat" type="text" class="form-control" placeholder="Kalimat"> </div>
                            <button type="submit" class="btn btn-primary">Analisa</button>
                            <?php
                            if (isset($_POST['kalimat'])) {

                                class SentimenDB extends SQLite3 {

                                    function __construct() {
                                        $this->open('sentimen.db');
                                    }

                                }

                                $db = new SentimenDB();
                                if ($db) {
                                    $p = $n = $pt = $nt = 0.0;
                                    $sql = "SELECT SUM(p) as pt,SUM(n) as nt FROM sentimen;";
                                    $ret = $db->query($sql);
                                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                        $pt = $row['pt'];
                                        $nt = $row['nt'];
                                    }
                                    $sql = "SELECT COUNT(s) AS s, SUM(p) AS p, SUM(n) AS n FROM (SELECT s, (SUM(p) > SUM(n)) AS p, (SUM(n) > SUM(p)) AS n FROM sentimen GROUP BY s) stm;";
                                    $ret = $db->query($sql);
                                    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                        $p = ($row['p']) / ($row['s']);
                                        $n = ($row['n']) / ($row['s']);
                                    }

                                    $kalimat = preg_split('/\s+/', preg_replace('/[^A-Za-z0-9\s]/', '', strtolower($_POST['kalimat'])));
                                    foreach ($kalimat as $kata) {
                                        $sql = "SELECT SUM(p) as p FROM sentimen where w like '$kata';";
                                        $ret = $db->query($sql);
                                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                            $p *= ($row['p']) / ($pt);
                                        }
                                        $sql = "SELECT SUM(n) as n FROM sentimen where w like '$kata';";
                                        $ret = $db->query($sql);
                                        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
                                            $n *= ($row['n']) / ($nt);
                                        }
                                    }
                                    $db->close();
                                    ?>
                                    <div class="form-group"> <label>Sentimen</label>
                                        <input value="<?= (($p == $n) ? "NETRAL" : (($p > $n) ? "POSITIF" : "NEGATIF")) . " ($p/$n)" ?> " name="sentimen" type="text" class="form-control" placeholder="Sentimen" readonly="readonly"> </div>
                                        <?php
                                    }
                                }
                                ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<!--        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
        <script src="https://pingendo.com/assets/bootstrap/bootstrap-4.0.0-alpha.6.min.js"></script>-->
    </body>

</html>