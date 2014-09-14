<?php

class Document_Db{
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

    public function remove($where){
        return $this->_document_db->remove($where);
    }
}
