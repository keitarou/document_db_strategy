<?php
interface Document_Db_Interface{

    public function connect();

    public function close();

    public function find($id);

    public function create($id);

    public function push($id, $key, $value);

    public function remove($where);

    public function count($where);

}


