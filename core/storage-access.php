<?php

class dl_b_StorageAccess {

    public $tablename;

    public function __construct($tableName) {

        global $wpdb;

        $this->tableName = $tableName;
        
    }

    public function getById($id) {

        global $wpdb;

        $sql = $wpdb->prepare("SELECT * FROM `$this->tableName` WHERE id = %s;", $id);

        $results = $wpdb->get_row($sql);

        return $results;
    }

    public function getAll() {
        global $wpdb;

        $sql = "SELECT * FROM `$this->tableName`;";
        $results = $wpdb->get_results($sql);

        return $results;
    }

    public function update($resource) {

        global $wpdb;

        $ressourceArray = get_object_vars($resource);
        
        if ($resource->id == 0) {

            $wpdb->insert($this->tableName, $ressourceArray);
            
            return $wpdb->insert_id;
            
        } else {

            $where = $wpdb->prepare(" WHERE id = %s", $ressourceArray->id);

            $wpdb->update($this->tableName, $ressourceArray, array('id' => $resource->id) );

        }
    }

    public function delete($id) {

        global $wpdb;
        $wpdb->query($wpdb->prepare("DELETE FROM `$this->tableName` WHERE id = %d", $id));
    }
}
