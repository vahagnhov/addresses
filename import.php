<?php
include "includes/connect.php";
include "functions/functions.php";

$output = '';
if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

    $valid_extension = array('xml');
    $file_data = explode('.', $_FILES['file']['name']);
    $file_extension = end($file_data);
    if (in_array($file_extension, $valid_extension)) {
        $data = simplexml_load_file($_FILES['file']['tmp_name']);
        $array = (array)$data;
        $count = count($array['addresses']);
        for ($i = 0; $i < $count; $i++) {

            $addresses = (array)$array['addresses'][$i];
            $address_id = clear_string($addresses['addresses_id']);
            $address = clear_string($addresses['addresses_address']);
            $street = clear_string($addresses['addresses_street']);
            $street_name = clear_string($addresses['addresses_street_name']);
            $street_type = clear_string($addresses['addresses_street_type']);
            $adm = clear_string($addresses['addresses_adm']);
            $adm1 = clear_string($addresses['addresses_adm1']);
            $adm2 = clear_string($addresses['addresses_adm2']);
            $cord_y = clear_string($addresses['addresses_cord_y']);
            $cord_x = clear_string($addresses['addresses_cord_x']);

            $sql = "INSERT INTO addresses (address_id, address, street, street_name, street_type, adm, adm1, adm2, cord_y, cord_x)
            VALUES ('" . $address_id . "', '" . $address . "', '" . $street . "', '" . $street_name . "', '" . $street_type . "', '" . $adm . "', '" . $adm1 . "', '" . $adm2 . "', '" . $cord_y . "', '" . $cord_x . "');";

            mysqli_query($con, $sql);

        }
        mysqli_close($con);
        $output = 'XML file successfully imported';
        $status = 'Yes';

    } else {
        $output = 'Invalid file';
        $status = 'No';
    }
} else {
    $output = 'Please Select XML file';
    $status = 'No';
}
echo json_encode(['output' => $output, 'status' => $status]);
?>