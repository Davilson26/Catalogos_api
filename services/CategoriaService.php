<?php
// services/Categoriaservice.php
require_once __DIR__ . '/../vendor/econea/nusoap/src/nusoap.php';
require_once __DIR__ . '/../config/catalogo_db.php';
require_once __DIR__ . '/../controllers/CategoriaController.php';

// Configuración del servicio SOAP
$namespace = "Categorias";
$server = new soap_server();
$server->configureWSDL('ServicioCategorias', $namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

// Definir el tipo complejo (o modelo de datos) para el Categoria
$server->wsdl->addComplexType(
    'Categoria',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nombre' => array('name' => 'nombre', 'type' => 'xsd:string'),
    )
);


// Registrar método SOAP para ver todos los Categorias
$server->register(
    'VerCategorias',
    array(),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Ver todos los Categorias'
);

// Registrar método SOAP para ver detalle de un Categoria
$server->register(
    'VerCategoria',
    array('id' => 'tns:int'),
    array('return' => 'xsd:Array'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Detalle la categoria'
);

// Registrar método SOAP para crear un Categoria
$server->register(
    'CrearCategoria',
    array('data' => 'tns:Categoria'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Crear un registro'
);

// Registrar método SOAP para actualizar un Categoria
$server->register(
    'ActualizarCategoria',
    array('data' => 'tns:Categoria', 'id' => 'xsd:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Actualizar registro'
);

// Registrar método SOAP para eliminar un Categoria
$server->register(
    'EliminarCategoria',
    array('id' => 'tns:int'),
    array('return' => 'xsd:string'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Eliminar un registro'
);


// Función que llama al controlador para ver todos los Categorias
function VerCategorias() {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->getAll();
}

// Función que llama al controlador para obtener el detalle de uh Categoria
function VerCategoria($id) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->getDetail($id);
}

// Función que llama al controlador para crear Categoria
function CrearCategoria($data) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->create($data);
}

// Función que llama al controlador para actualizar Categoria
function ActualizarCategoria($data, $id) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->update($data, $id);
}


// Función que llama al controlador para eliminar Categoria
function EliminarCategoria($id) {
    $pdo = getConnection();
    $controller = new CategoriaController($pdo);
    return $controller->delete($id);
}

//-----------------------

// Procesar solicitud SOAP
$POST_DATA = file_get_contents("php://input");
$server->service($POST_DATA);
exit();
?>
