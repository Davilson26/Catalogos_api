<?php
// models/productos.php
class Productos
{
    private $pdocatalogo;

    public function __construct($pdocatalogo)
    {
        $this->pdocatalogo = $pdocatalogo;
    }

    public function get()
    {
        try {
            $stmt = $this->pdocatalogo->query("SELECT * FROM productos");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }

    // Obtener productos por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear productos
    public function create($data)
    {
        try {
            $stmt = $this->pdocatalogo->prepare("INSERT INTO productos (nombre, precio, stock, descripcion, categoria_id)
                                         VALUES (:nombre, :precio, :stock, :descripcion, :categoria_id)");
            // Bind parameters
            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':precio', $data['precio']);
            $stmt->bindParam(':stock', $data['stock']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':categoria_id', $data['categoria_id']);
            $stmt->execute();
            return "productos guardado correctamente";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Actualizar productos
    public function update($data, $id)
    {
        $sql = "UPDATE productos SET nombre = :nombre, precio = :precio, stock = :stock, descripcion = :descripcion, categoria_id = :categoria_id WHERE id = :id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'descripcion' => $data['descripcion'],
            'categoria_id' => $data['categoria_id'],
        ]);
        return "productos actualizado correctamente";
    }

    // Eliminar productos
    public function delete($id)
    {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return "producto eliminado correctamente";
    }

    public function getBySearch($search)
    {
        // Consulta SQL para buscar en múltiples columnas con LIKE
        $sql = "SELECT * FROM productos 
                WHERE id = :search
                OR nombre LIKE :likeSearch 
                OR precio = :search
                OR stock = :search
                OR descripcion LIKE :likeSearch 
                OR categoria_id = :search";

        // Preparar la sentencia
        $stmt = $this->pdocatalogo->prepare($sql);

        // Ejecutar la sentencia, utilizando el string para la búsqueda exacta y parcial
        $stmt->execute([
            'search' => $search,
            'likeSearch' => '%' . $search . '%'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos por ID
    public function getByCategoriaId($id)
    {
        $sql = "SELECT * FROM productos WHERE categoria_id = :categoria_id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute(['categoria_id' => $id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStock($product_id, $quantity)
    {
        // Buscar el producto por ID
        $sql = "SELECT * FROM productos WHERE id = :product_id";
        $stmt = $this->pdocatalogo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return ['error' => 'Producto no encontrado'];
        }

        // Verificar stock suficiente
        if ($product['stock'] < $quantity) {
            return ['error' => 'Stock insuficiente'];
        }

        // Calcular nuevo stock
        $newStock = $product['stock'] - $quantity;

        // Actualizar el stock en la base de datos
        $updateSql = "UPDATE productos SET stock = :newStock WHERE id = :product_id";
        $updateStmt = $this->pdocatalogo->prepare($updateSql);
        $updateStmt->execute(['newStock' => $newStock, 'product_id' => $product_id]);

        return 'Stock ajustado con éxito';
    }
}
