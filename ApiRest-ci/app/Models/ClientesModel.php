<?php   
    namespace App\Models;

    use CodeIgniter\Model;

    class ClientesModel extends Model{
        protected $table = "clientes";
        protected $allowedFields = ['nombres', 'apellidos','email', 'idCliente', 'secret_key'];
        protected $useTimestamps = true;
        protected $createdField = 'created_at';
        protected $updatedField = 'updated_at';
    }