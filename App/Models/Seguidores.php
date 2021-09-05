<?php

namespace App\Models;
use MF\Model\Model;

class Seguidores extends model {

    private $id;
    private $id_usuario;
    private $id_usuario_seguindo;

    public function __set($atr, $value) {
        $this->$atr = $value;
    }

    public function __get($atr) {
        return $this->$atr;
    }

    public function seguir($id_user_follow) {
        $query = 
        "
            INSERT INTO tb_seguidores (id_usuario, id_usuario_seguindo) VALUES (:id_usuario, :id_usuario_seguindo)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario_seguindo', $id_user_follow);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return true;
    }

    public function deixarDeSeguir($id_user_follow) {
        $query = 
        "
            DELETE FROM tb_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_usuario_seguindo', $id_user_follow);
        $stmt->execute();

        return true;
    }

}