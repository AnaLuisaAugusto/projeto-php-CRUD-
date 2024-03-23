<?php
namespace App;
require "../vendor/autoload.php";
use App\Model\Cliente;
use App\Repository\ClienteRepository;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $requiredFields = ['nome', 'email', 'cidade', 'estado'];

        if (!isValid($data, $requiredFields)) {
            http_response_code(400);
            echo json_encode(["error" => "Dados de entrada inválidos."]);
            break;
        }

        $cliente = new Cliente();
        
        $cliente->setNome($data->nome)
        ->setEmail($data->email)
        ->setCidade($data->cidade)
        ->setEstado($data->estado);

        $repository = new ClienteRepository();
        $success = $repository->insertCliente($cliente);
        if ($success) {
            http_response_code(200);
            echo json_encode(["message" => "Dados inseridos com sucesso."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Falha ao inserir dados."]);
        }
        break;
    case 'GET':
        $cliente = new Cliente();
        $repository = new ClienteRepository();

        if (isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                if ($id === false) {
                    http_response_code(400); 
                    echo json_encode(['error' => 'O valor do ID fornecido não é um inteiro válido.']);
                    exit;
                } else {
                    $cliente->setClienteId($id);
                    $result = $repository->getById($cliente);
                }
            } else {
            $result = $repository->getAll();
        }

        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Nenhum dado encontrado."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $requiredFields = ['id', 'nome', 'email', 'cidade', 'estado'];

        if (!isValid($data, $requiredFields)) {
            http_response_code(400);
            echo json_encode(["error" => "Dados de entrada inválidos."]);
            break;
        }

        $cliente = new Cliente();
        $repository = new ClienteRepository();

        $cliente->setClienteId($data->id)
        ->setNome($data->nome)
        ->setEmail($data->email)
        ->setCidade($data->cidade)
        ->setEstado($data->estado);

        $sucess = $repository->updateCliente($cliente);
        if($sucess){
            http_response_code(201);
            echo json_encode(["mensagem" => "Dados atualizados com sucesso"]);
        }else{
            http_response_code(500);
            echo json_encode(["message" => "Falha ao inserir dados."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $requiredFields = ['id'];

        if (!isValid($data, $requiredFields)) {
            http_response_code(400);
            echo json_encode(["error" => "Dados de entrada inválidos."]);
            break;
        }

        $cliente = new Cliente();
        $repository = new ClienteRepository();

        $cliente->setClienteId($data->id);
        $result = $repository->getById($cliente);
        if($result){
            $repository->deleteCliente($cliente);
            http_response_code(200);
            echo json_encode(["mensagem" => "Dado apagado com sucesso"]);
        }else{
            http_response_code(404);
            echo json_encode(['error' => "Nenhum dado encontrado."]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método não permitido."]);
        break;
}

function isValid($data, $requiredFields) {
    foreach ($requiredFields as $field) {
        if (!isset($data->$field)) {
            return false;
        }
    }
    return true;
}
