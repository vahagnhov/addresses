<?php
include "includes/connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calculate Distance</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css">
    <link href="/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/select2.min.js"></script>
</head>
<body>
<div class="container">
    <?php
    include "includes/header.php";
    ?>
    <br/><br/>
    <form method="post">

        <select class="s-example-basic-single" name="first">
            <option value="0">Type Address</option>
            <?php
            $sql = "SELECT id, adm, street, address FROM addresses";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <option
                            value="<?= $row['id'] ?>" <?php if (isset($_POST['first']) && $_POST['first'] == $row['id']) echo 'selected'; ?> ><?= $row['adm'] . " " . $row['street'] . " " . $row['address'] ?></option>
                <?php }
            } ?>
        </select>

        <br/><br/>
        <input type="submit" name="calculate_button" class="button" value="Calculate Distance"/>
    </form>
    <br/><br/>
    <?php

    function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        //Latitude x,Longitude y
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round(($miles * 1.609344), 1);
        } else if ($unit == "N") {
            return round(($miles * 0.8684), 1);
        } else {
            return round($miles, 1);
        }
    }

    if (isset($_POST['calculate_button'])) {

        $first_id = $_POST['first'];
        $sql = "SELECT * FROM addresses WHERE id=" . $first_id . " LIMIT 1";
        $result = $con->query($sql);
        $row = mysqli_fetch_assoc($result);
        $long1 = $row['cord_y'];
        $lat1 = $row['cord_x'];
        $first_all_address = $row['adm'] . " " . $row['street'] . " " . $row['address'];

        if ($long1 !== 0 && $lat1 != 0) {


            $sql = "SELECT * FROM addresses";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                ?>
                <h1>Typed address - <?php echo $first_all_address; ?></h1>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Distance < 5 Km</th>
                        <th>Distance From 5 Km to 30 Km</th>
                        <th>Distance more than 30 Km</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $arraySmallDistance = [];
                    $arrayMiddleDistance = [];
                    $arrayBigDistance = [];
                    while ($row = $result->fetch_assoc()) {
                        $address = $row['adm'] . " " . $row['street'] . " " . $row['address'] . "<br/>";
                        if ($lat1 != $row['cord_x'] && $long1 != $row['cord_y']) {
                            $distance = distance($lat1, $long1, $row['cord_x'], $row['cord_y'], "K");

                            if ($distance < 5) {
                                $smallDistance = "{$address} (" . $distance . " km)";
                                if ($smallDistance !== '') {
                                    $arraySmallDistance[] .= $smallDistance;
                                }

                            } elseif ($distance >= 5 && $distance <= 30) {
                                $middleDistance = "{$address} (" . $distance . " km)";
                                if ($middleDistance !== '') {
                                    $arrayMiddleDistance[] .= $middleDistance;
                                }

                            } elseif ($distance > 30) {
                                $bigDistance = "{$address} (" . $distance . " km)";
                                if ($bigDistance !== '') {
                                    $arrayBigDistance[] .= $bigDistance;
                                }
                            }
                            ?>

                            <?php
                        }
                    }

                    $countSmall = count($arraySmallDistance);
                    $countMiddle = count($arrayMiddleDistance);
                    $countBig = count($arrayBigDistance);
                    $maxCount = max($countSmall, $countMiddle, $countBig);

                    for ($i = 0; $i < $maxCount; $i++) {

                        ?>

                        <tr>
                            <td> <?php if ($arraySmallDistance[$i] != '') {
                                    echo $arraySmallDistance[$i];
                                } else {
                                    echo '...';
                                }
                                ?></td>
                            <td> <?php if ($arrayMiddleDistance[$i] != '') {
                                    echo $arrayMiddleDistance[$i];
                                } else {
                                    echo '...';
                                }
                                ?></td>
                            <td> <?php if ($arrayBigDistance[$i] != '') {
                                    echo $arrayBigDistance[$i];
                                } else {
                                    echo '...';
                                }
                                ?></td>
                        </tr>

                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <br/> <br/>

                <?php
            }

        } else {
            echo "<p class='error_message'>Please type address</p>";
        }
    }

    ?>
    <script>
        $(document).ready(function () {
            $('.s-example-basic-single').select2();
        });
    </script>
    <div>
</body>
</html>