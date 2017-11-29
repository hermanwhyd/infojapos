<?php
namespace JAPOS\Resource;

/**
 * Class Resource
 * @package JAPOS
 */
class JamaahResource extends AbstractResource {
    
    public function get($id = null) {
        if ($id === null) {
            $sql = "select id, nama_lengkap, tempat_lahir, tanggal_lahir from jamaah";
            
            try {
                $stmt = $this->entityManager->prepare($sql);
                $stmt->execute();
                
                return $stmt->fetchAll();
            } catch(PDOException $e) {
                return array("error" => array("code" => "SQL.ERR.001", "message" => $e->getMessage()));
            }
        } else {
            $sql = "select id, nama_lengkap, tempat_lahir, tanggal_lahir from jamaah where id=:id";
            try {
                $stmt = $this->entityManager->prepare($sql);
                $stmt->bindParam("id", $id);
                $stmt->execute();
                
                return $stmt->fetchObject();
            } catch(PDOException $e) {
                return array("error" => array("code" => "SQL.ERR.002", "message" => $e->getMessage()));
            }
        }

        return false;
    }
}