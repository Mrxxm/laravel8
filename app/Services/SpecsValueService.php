<?php


namespace App\Services;


interface SpecsValueService
{
    /*
     * 后台
     */
    public function search(array $data) : array;

    public function list(array $data) : array ;

    public function add(array $fields) : void;

    public function update(int $id, array $fields) : void;

    public function delete(int $id) : void;

    public function handleGoodsSkies(array $gIds, string $flagValue) : array;
}
