<?php namespace App\Http\Models;

abstract class BaseModel
{

    public function __construct()
    {
        $this->db = new \mysqli('localhost', 'application', 'application', 'homestead');
    }

    public function execute($sql)
    {
        $statement = $this->db->prepare($sql);
        if ($statement == false) { return false; }
        $statement->execute();
        $statement->close();
        return true;
    }

    public function selectOne($sql)
    {
        $statement = $this->db->prepare($sql);
        if ($statement == false) { return; }
        $statement->execute();
        $statement->store_result();
        $statement->bind_result($result);
        $statement->fetch();
        $statement->close();
        return $result;
    }

    public function selectRow($sql)
    {
        $result = $this->db->query($sql);
        if($result == false) { return; }
        $return = $result->fetch_array(MYSQLI_ASSOC);
        $result->free();
        return $return;
    }

    public function selectAll($sql)
    {
        $result = $this->db->query($sql);
        if($result == false) { return; }
        $return = array();
        foreach(range(1, $result->num_rows) as $row)
        { $return[] = $result->fetch_array(MYSQLI_ASSOC); }
        $result->free();
        return $return;
    }

    public function multiQuery($sql)
    {
        $mysqli = $this->db;
        if ($mysqli->multi_query($sql)) {
            do {
                if ($result = $mysqli->store_result()) {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $return[] = $row;
                    }
                    $result->free();
                }
                if (!$mysqli->more_results()) {
                    return $return;
                }
            } while ($mysqli->next_result());
        }
    }
}

?>
