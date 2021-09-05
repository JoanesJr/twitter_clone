<?php

namespace App\Models;
use MF\Model\Model;

class Usuario extends model {
    private $id;
    private $name;
    private $email;
    private $password;

    public function __set($atr, $value) {
        $this->$atr = $value;
    }

    public function __get($atr) {
        return $this->$atr;
    }

    public function saveRegister() {
        $query = "INSERT INTO tb_usuarios (name, email, password) values (:name, :email, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $this->__get('name'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue('password', $this->__get('password'));
        $stmt->execute();

        return $this;
    }

    public function verifyRegister() {
        $verify = true;
        if (strlen($this->__get('name')) < 3) {
            $verify = false;
        };

        if (strlen($this->__get('email')) < 3) {
            $verify = false;
        };

        if (strlen($this->__get('password')) < 8 || $this->__get('password') == $this->__get('name') ){
            $verify = false;
        };

        return $verify;
    }

    public function recoveryRegister() {
        $query = "SELECT name, email FROM tb_usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();
        $stmtCount = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (count($stmtCount) == 0) {
            return true;
        } else {
            return false;
        };
    }

    public function auth() {
        $query = "SELECT id, name, email FROM tb_usuarios WHERE email = :email AND password = :password";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':password', $this->__get('password'));
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($user['id']) && !empty($user['name'])) {
            $this->__set('id', $user['id']);
            $this->__set('name', $user['name']);
        }

        return $this;
    }

    public function getAll() {
        $query = "
        SELECT 
            u.id, u.name, u.email,
            (
                SELECT
                    COUNT(*)
                FROM
                    tb_seguidores as s
                WHERE
                    s.id_usuario = :id AND s.id_usuario_seguindo = u.id
            ) as us
        FROM
             tb_usuarios as u
        WHERE
             name LIKE :nome AND id != :id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('name').'%');
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function nameUser() {
        $query =
        "
            SELECT name FROM tb_usuarios WHERE id = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function numberTweets() {
        $query =
        "
            SELECT count(*) as number_tweets FROM tb_tweets WHERE id_usuario = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function numberFollowing() {
        $query =
        "
            SELECT count(*) as number_following FROM tb_seguidores WHERE id_usuario = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function numberFollowers() {
        $query =
        "
            SELECT count(*) as number_followers FROM tb_seguidores WHERE id_usuario_seguindo = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}