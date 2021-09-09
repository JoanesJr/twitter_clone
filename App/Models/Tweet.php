<?php

namespace App\Models;
use MF\Model\Model;

class Tweet extends model {
    private $id;
    private $id_user;
    private $tweet;
    private $date;

    public function __set($atr, $value) {
        $this->$atr = $value;
    }

    public function __get($atr) {
        return $this->$atr;
    }

    //salvar
    public function saveTweet() {
        $query = "INSERT INTO tb_tweets (id_usuario, tweet) VALUES (:id_user, :tweet)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->bindValue(':tweet', $this->__get('tweet'));
        $stmt->execute();

        return $this;
    }

    //recuperar
    public function getAll() {
        $query = "
            SELECT 
                t.id, t.id_usuario, t.tweet, u.name, u.image,DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
            FROM 
                tb_tweets AS t
                LEFT JOIN tb_usuarios as u on (t.id_usuario = u.id)
            WHERE 
                t.id_usuario = :id_user
                OR
                    t.id_usuario in (
                        SELECT 
                            id_usuario_seguindo from tb_seguidores
                        WHERE
                            id_usuario = :id_user
                    ) 
            ORDER BY
                t.data DESC
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_user', $this->__get('id_user'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function deleteTweet() {
        $query = 
        "
            DELETE FROM tb_tweets WHERE id = :id_tweet;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_tweet', $this->__get('id'));
        $stmt->execute();

        return true;
    }
}