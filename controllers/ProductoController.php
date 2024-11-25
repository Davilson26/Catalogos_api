<?php
// controllers/UsuarioController.php
require_once __DIR__ . '/../models/Productos.php';

class ProductoController
{
    private $productoModel;

    public function __construct($pdo)
    {
        $this->productoModel = new Productos($pdo);
    }

    public function getAll()
    {
        return $this->productoModel->get();
    }

    public function getDetail($id)
    {
        return $this->productoModel->getById($id);
    }

    public function create($data)
    {
        if (!isset($data['nombre'], $data['precio'], $data['stock'], $data['descripcion'], $data['categoria_id'])) {
            return "Error: Faltan campos requeridos.";
        }
        return $this->productoModel->create($data);
    }

    public function update($data, $id)
    {
        return $this->productoModel->update($data, $id);
    }

    public function delete($id)
    {
        return $this->productoModel->delete($id);
    }

    public function getSearch($search)
    {
        return $this->productoModel->getBySearch($search);
    }

    public function getByCategoria($id)
    {
        return $this->productoModel->getByCategoriaId($id);
    }

    public function adjustStock($product_id, $quantity)
    {
        return $this->productoModel->updateStock($product_id, $quantity);
    }
}
