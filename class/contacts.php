<?php
    class Contact{

        // conn
        private $conn;

        // table
        private $dbTable = 'tbl_contactos';

        // col
        public $id;
        public $nombre;
        public $apellido;
        public $email;
        public $telefono1;
        public $telefono2;

        // db conn
        public function __construct($db){
            $this->conn = $db;
        }

        // GET Contacts
        public function getContacts(){
            $sqlQuery = "SELECT id, nombre, apellido, email, telefono1, telefono2 FROM " . $this->dbTable . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // CREATE Contact
        public function createContact(){
            $sqlQuery = "INSERT INTO
                        ". $this->dbTable ."
                    SET
                        nombre = :nombre,
                        apellido = :apellido,
                        email = :email, 
                        telefono1 = :telefono1, 
                        telefono2 = :telefono2";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->nombre=htmlspecialchars(strip_tags($this->nombre));
            $this->apellido=htmlspecialchars(strip_tags($this->apellido));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->telefono1=htmlspecialchars(strip_tags($this->telefono1));
            $this->telefono2=htmlspecialchars(strip_tags($this->telefono2));
        
            // bind data
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":apellido", $this->apellido);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":telefono1", $this->telefono1);
            $stmt->bindParam(":telefono2", $this->telefono2);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }       

        // DELETE Contact
        function deleteContact(){
            $sqlQuery = "DELETE FROM " . $this->dbTable . " WHERE id = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }

    }
