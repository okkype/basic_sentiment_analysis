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
                        <li class="nav-item">
                            <a class="nav-link" href="analisa.php">Analisa</a>
                        </li>
                        <li class="nav-item active">
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
                        <div class="alert alert-warning" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            <h4 class="alert-heading">Perhatian!</h4>
                            <?php
                            if (isset($_FILES['jsonfile'])) {

                                class SentimenDB extends SQLite3 {

                                    function __construct() {
                                        $this->open('sentimen.db');
                                    }

                                }

                                $db = new SentimenDB();
                                if (!$db) {
                                    echo '<p>' . $db->lastErrorMsg() . '<p>';
                                } else {
                                    $textjson = file_get_contents($_FILES['jsonfile']['tmp_name']);
                                    $json_array = json_decode($textjson, true);
                                    foreach ($json_array as $json_value) {
                                        $raw = strtolower($json_value['w']);
                                        $kalimat = preg_split('/\s+/', preg_replace('/[^A-Za-z0-9\s]/', '', $raw));
                                        foreach ($kalimat as $kata) {
                                            $sql = "INSERT INTO sentimen(w, p, n, s) SELECT '$kata', 0, 0, '$raw' WHERE NOT EXISTS(SELECT 1 FROM sentimen WHERE w like '$kata');";
                                            if ($json_value['s'] == '+') {
                                                $sql .= "UPDATE sentimen SET p = (SELECT SUM(p) + 1 FROM sentimen WHERE w LIKE '$kata') WHERE w like '$kata';";
                                            } elseif ($json_value['s'] == '-') {
                                                $sql .= "UPDATE sentimen SET n = (SELECT SUM(n) + 1 FROM sentimen WHERE w LIKE '$kata') WHERE w like '$kata';";
                                            }
                                            $ret = $db->exec($sql);
                                            if (!$ret) {
                                                echo '<p>' . $db->lastErrorMsg() . '<p>';
                                            } else {
                                                echo "<p>Kata $kata telah tersimpan...</p>";
                                            }
                                        }
                                    }
                                    $db->close();
                                }
                            } else {
                                ?>
                                <p>
                                    File JSON untuk learning data dengan format data {"s":"[+|-]","w":"KALIMAT"} misal:
                                </p>
                                <pre>
            [
                {
                    "s":"+",
                    "w":"anda sangat tampan"
                },
                {
                    "s":"-",
                    "w":"anda sangat jelek"
                }
            ]
                                </pre>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <form method="POST" enctype="multipart/form-data" class="">
                            <div class="form-group">
                                <input type="file" name="jsonfile" id="jsonfile" accept=".json" /> </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
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