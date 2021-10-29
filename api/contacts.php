<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../class/contacts.php';

$database = new Db();
$db = $database->getConnection();


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        $items = new Contact($db);

        $stmt = $items->getContacts();
        $itemCount = $stmt->rowCount();


        echo json_encode($itemCount);

        if ($itemCount > 0) {

            $userArr = array();
            $userArr["body"] = array();
            $userArr["itemCount"] = $itemCount;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $e = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "apellido" => $apellido,
                    "email" => $email,
                    "telefono1" => $telefono1,
                    "telefono2" => $telefono2
                );

                array_push($userArr["body"], $e);
            }
            echo json_encode($userArr);
        } else {
            http_response_code(404);
            echo json_encode(
                array("message" => "Data not found.")
            );
        }
        break;
    case 'POST':
        $item = new Contact($db);

        $data = json_decode(file_get_contents("php://input"));

        // validate 
        if(empty($data->nombre) or empty($data->apellido) or empty($data->email) or empty($data->telefono1)) {
            echo json_encode("Error: all fields are required.");
            return;
        }
        
        $item->nombre = $data->nombre;
        $item->apellido = $data->apellido;
        $item->email = $data->email;
        $item->telefono1 = $data->telefono1;
        $item->telefono2 = $data->telefono2;

        if ($item->createContact()) {
            echo json_encode("Contact add.");
        } else {
            echo json_encode("Contact was not add.");
        }
        break;

    case 'DELETE':
        $item = new Contact($db);

        $data = json_decode(file_get_contents("php://input"));

        $item->id = $data->id;

        if ($item->deleteContact()) {
            echo json_encode("Contact deleted.");
        } else {
            echo json_encode("Not deleted");
        }
}
