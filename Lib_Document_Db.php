<?php

require './DocumentDb/Interface/Document_Db.php';
require './DocumentDb/Mongodb/Document_Db.php';

class Lib_Document_Db{
    private $_document_db = null;

    public function __construct($document_db){
        if(!($document_db instanceof Document_Db_Interface)){
            throw new Exception('error');
        }
        $this->_document_db = $document_db;
    }

    public function connect(){
        return $this->_document_db->connect();
    }

    public function close(){
        return $this->_document_db->close();
    }

    public function find($id){
        return $this->_document_db->find($id);
    }

    public function create($id){
        return $this->_document_db->create($id);
    }

    public function push($id, $key, $value){
        return $this->_document_db->push($id, $key, $value);
    }
}

$lib = new Lib_Document_Db(new Document_Db_Mongodb());
$lib->connect();
// $lib->create('12345');
// $lib->close();
$lib->push('9876', 'col4', 1999);
