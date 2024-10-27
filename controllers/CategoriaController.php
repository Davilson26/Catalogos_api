<?php
// controllers/UsuarioController.php
require_once __DIR__ . '/../models/Categorias.php';

class CategoriaController
{
    private $categoriaModel;

    public function __construct($pdo)
    {
        $this->categoriaModel = new Categorias($pdo);
    }

    public function getAll()
    {
        return $this->categoriaModel->get();
    }

    public function getDetail($id)
    {
        return $this->categoriaModel->getById($id);
    }

    public function create($data)
    {
        if (!isset($data['nombre'])) {
            return "Error: Faltan campos requeridos.";
        }
        return $this->categoriaModel->create($data);
    }

    public function update($data, $id)
    {
        return $this->categoriaModel->update($data, $id);
    }

    public function delete($id)
    {
        return $this->categoriaModel->delete($id);
    }
}
