<?php
class Document_Db_Mongodb implements Document_Db_Interface{

    private $_connection = null;
    private $_db         = null;
    private $_collection = null;

    private function _get_host(){
        return '127.0.0.1';
    }

    private function _get_port(){
        return '27017';
    }

    private function _get_dbname(){
        return 'hoge';
    }

    private function _get_collectionname(){
        return 'piyo';
    }

    private function _get_scheme(){
        return [
            'col1' => null,
            'col2' => [],
            'col3' => [],
            'col4' => [],
        ];
    }

    public function connect(){
        $this->_connection  = new Mongo($this->_get_host(). ":". $this->_get_port());
        $this->_db          = $this->_connection->selectDB($this->_get_dbname());
        $this->_collection  = $this->_db->selectCollection($this->_get_collectionname());
    }

    public function close(){
        $this->_collection  = null;
        $this->_db          = null;
        return $this->_connection->close();
    }

    public function find($id){
        $where = array('col1' => $id);

        $doc = $this->_collection->findOne($where);
        if(is_null($doc)){
            $this->create($id);
        }
        $doc = $this->_collection->findOne($where);

        return $doc;
    }

    public function create($id){
        $scheme = ['col1' => $id];
        return $this->_collection->insert($scheme);
    }

    public function push($id, $key, $value){
        if(!array_key_exists($key, $this->_get_scheme())){
            throw new Exception('not exist key in scheme');
        }

        $where = ['col1' => $id];
        $push  = ['$push' => 
            [$key  => $value]
        ];

        return $this->_collection->update($where, $push);
    }

    public function remove($where=false){
        if($where === false){
            throw new Exception('not set $where');
        }

        return $this->_collection->remove($where);
    }

}

