<?php

require './DocumentDb/Interface/Document_Db.php';
require './DocumentDb/Mongodb/Document_Db.php';
require './DocumentDb/Document_Db.php';

class Bad_Class{
}

class Test_Document_Db extends PHPUnit_Framework_TestCase {

    protected $lib;

    const ID = 'hoge12345';

    protected function setUp()
    {
        $this->lib = new Document_Db(new Document_Db_Mongodb());
        $this->lib->connect();

        $this->lib->create(self::ID);
    }

    protected function tearDown()
    {
        // all delete
        $ret = $this->lib->remove([]);

        $this->lib->close();
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function コンストラクタで渡すインスタンスはDocument_Db_Interfaceが実装されていないといけない(){
        new Document_Db(new Bad_Class());
    }

    /**
     * @test
     */
    public function 指定したパラメータのドキュメントが生成される(){
        $id = 'fuga12345';
        $ret = $this->lib->find($id);

        $this->assertSame($id, $ret['col1']);
    }

    /**
     * @test
     */
    public function 重複したパラメータのドキュメントは生成されない(){

        $this->lib->create(self::ID);
        $this->lib->create(self::ID);
        $this->lib->create(self::ID);
        $this->lib->create(self::ID);
        $this->lib->create(self::ID);

        $count = $this->lib->count([]);
        $this->assertSame(1, $count);
    }

    /**
     * @test
     */
    public function 配列に対する要素の追加ができる(){

        $this->lib->push(self::ID, 'col2', 1);
        $this->lib->push(self::ID, 'col2', 2);
        $this->lib->push(self::ID, 'col2', 3);

        $ret = $this->lib->find(self::ID);
        $this->assertSame([1, 2, 3], $ret['col2']);
    }

    /**
     * @test
     */
    public function 重複する値は配列の追加ができない(){

        $this->lib->push(self::ID, 'col2', 1);
        $this->lib->push(self::ID, 'col2', 2);
        $this->lib->push(self::ID, 'col2', 3);
        $this->lib->push(self::ID, 'col2', 3); // ←

        $ret = $this->lib->find(self::ID);
        $this->assertSame([1, 2, 3], $ret['col2']);
    }

    /**
     * @test
     * @expectedException Exception
     * @expectedExceptionMessage not exist key in scheme
     */
    public function 定義されていないkeyに対してpushはできない(){
        $this->lib->push(self::ID, 'col10', 1);
    }


    /**
     * @test
     */
    public function 検索結果が存在しない場合は検索対象の文字列でドキュメントを作成してかえしてくれる(){
        $id = 'fuga12345';
        $ret = $this->lib->find($id);
        $this->assertSame($id, $ret['col1']);
    }

    /**
     * @test
     */
    public function removeメソッドでドキュメントを削除することができる(){

        for($i=1; $i<=10; $i++){
            $ret = $this->lib->create($i);
        }

        // 11
        $before = $this->lib->count([]);

        $this->lib->remove([]);

        // 0
        $after = $this->lib->count([]);

        $this->assertSame(0, $before - $after - 11);
    }

}

