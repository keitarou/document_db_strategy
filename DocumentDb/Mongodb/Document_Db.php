<?php
class Document_Db_Mongodb implements Document_Db_Interface{

    private $_connection = null;
    private $_db         = null;
    private $_collection = null;

    public function get_host(){
        return '127.0.0.1';
    }

    public function get_port(){
        return '27017';
    }

    public function get_dbname(){
        return 'hoge';
    }

    public function get_collectionname(){
        return 'piyo';
    }

    public function get_scheme(){
        return [
            'col1' => null,
            'col2' => [],
            'col3' => [],
            'col4' => [],
        ];
    }

    public function connect(){
        $this->_connection  = new Mongo($this->get_host(). ":". $this->get_port());
        $this->_db          = $this->_connection->selectDB($this->get_dbname());
        $this->_collection  = $this->_db->selectCollection($this->get_collectionname());
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
        if(!array_key_exists($key, $this->get_scheme())){
            throw new Exception('not exist key in scheme');
            return false;
        }

        $where = ['col1' => $id];
        $push  = ['$push' => 
            [$key  => $value]
        ];

        return $this->_collection->update($where, $push);
    }

}

