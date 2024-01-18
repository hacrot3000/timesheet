<?php

/*
 */

namespace App\Models;

use CodeIgniter\Model;

/**
 * Description of Users
 *
 * @author duongtc
 */
abstract class BaseModel extends Model
{

    //protected $table              = 'settings';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = 'array';
    protected $useSoftDeletes     = true;
    //protected $allowedFields      = ['code', 'title', 'unit', 'quantity', 'alert_quantity'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    protected function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public function convertDate($datetime, $dateOnly = false)
    {
        if (empty($datetime))
        {
            return "";
        }
        if ($dateOnly)
        {
            $time    = '';
            $datetmp = explode('-', $datetime);
        }
        else
        {
            $date    = explode(' ', $datetime);
            $time    = ' ' . $date[1];
            $datetmp = explode('-', $date[0]);

            $datetmp[0] = substr($datetmp[0], 2);
        }
        return $datetmp[2] . '/' . $datetmp[1] . '/' . $datetmp[0] . $time;
    }

    public function beginTransaction()
    {
        $this->db->query('START TRANSACTION');
    }

    public function commitTransaction()
    {
        $this->db->query('COMMIT');
    }

    public function rollbackTransaction()
    {
        $this->db->query('ROLLBACK');
    }
    

    public function __call($name, $arguments)
    {
        $column  = '';
        $oneOnly = false;
        if ($this->startsWith($name, 'findFirstBy'))
        {
            $column  = strtolower(substr($name, strlen('findFirstBy')));
            $oneOnly = true;
        }
        elseif ($this->startsWith($name, 'findBy'))
        {
            $column = strtolower(substr($name, strlen('findBy')));
        }

        if (empty($column))
        {
            return parent::__call($name, $arguments);
        }

        if (count($arguments) > 1)
        {
            $this->whereIn($column, $arguments);
        }

        if (is_array($arguments[0]))
        {
            if (count($arguments[0]) > 1)
            {
                $this->whereIn($column, $arguments[0]);
            }
            else
            {
                $this->where($column, $arguments[0]);
            }
        }
        else
        {
            $this->where($column, $arguments[0]);
        }
        if ($oneOnly)
            return $this->first();
        return $this->findAll();
    }

}
