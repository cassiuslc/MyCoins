<?php

namespace App\Repositories\Auth;
use App\Models\User;
use App\Interfaces\Auth\UserInterface;

class UserReposiotry implements UserInterface
{
    public function index(){
        return User::all();
    }

    public function getById($id){
       return User::findOrFail($id);
    }

    public function store(array $data){
       return User::create($data);
    }

    public function update(array $data,$id){
       return User::whereId($id)->update($data);
    }

    public function delete($id){
       User::destroy($id);
    }
}
