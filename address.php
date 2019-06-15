<?php
include "includes/connect.php";

$sql = "SELECT * FROM addresses";
$result = $con->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Address Table</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="/js/jquery-3.4.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
</head>
<body>
<div class="container">
    <?php
    include "includes/header.php";
    ?>
    <br/><br/>
    <table id="example" class="table">
        <thead>
        <tr>
            <th></th>
            <th>Address ID</th>
            <th>Home Number</th>
            <th>Street</th>
            <th>Street Alternate Name</th>
            <th>Street Type</th>
            <th>City</th>
            <th>Distinct</th>
            <th>Region</th>
            <th>Longitude</th>
            <th>Latitude</th>
        </tr>
        </thead>
        <tbody>

        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td></td>
                    <td><?= $row['address_id'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= $row['street'] ?></td>
                    <td><?= $row['street_name'] ?></td>
                    <td><?= $row['street_type'] ?></td>
                    <td><?= $row['adm'] ?></td>
                    <td><?= $row['adm1'] ?></td>
                    <td><?= $row['adm2'] ?></td>
                    <td><?= $row['cord_y'] ?></td>
                    <td><?= $row['cord_x'] ?></td>
                </tr>
                <?php
            }
        }
        $con->close();
        ?>
        </tbody>
    </table>
    <br/><br/>

    <script>
        $(document).ready(function () {
            var t = $('#example').DataTable({
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }],
                "order": [[1, 'asc']]
            });

            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
</div>
</body>
</html>