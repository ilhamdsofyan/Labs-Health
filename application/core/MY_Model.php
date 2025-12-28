<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 * 
 * @category	Libraries
 * @author		Ilham D. Sofyan
 */
class MY_Model extends MY_Orm
{
	use DatatableTrait;

	public $tableName = '';
	public $primaryKey = 'id';

	/* ==========================
     * INTERNAL CACHE
     * ========================== */
    protected static $_attributesCache = [];

    /* ==========================
     * BASIC GETTERS (STDCLASS)
     * ========================== */

    /**
     * WARNING:
     * Returns stdClass, NOT ORM entity.
     */
    public function get($where = null)
    {
        $this->db->from($this->tableName);

        if ($where !== null) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    is_array($value)
                        ? $this->db->where_in($field, $value)
                        : $this->db->where($field, $value);
                }
            } else {
                $this->db->where($this->primaryKey, $where);
            }
        }

        if (!empty($this->soft_delete)) {
            $this->db->where(['deleted_at' => null]);
        }

        return $this->db->get()->row() ?: [];
    }

    /**
     * WARNING:
     * Returns stdClass[], NOT ORM entity collection.
     */
    public function getAll($where = [], $limit = null)
    {
        $this->db->from($this->tableName);

        if (!empty($where)) {
            foreach ($where as $field => $value) {
                is_array($value)
                    ? $this->db->where_in($field, $value)
                    : $this->db->where($field, $value);
            }
        }

        if (!empty($this->soft_delete)) {
            $this->db->where(['deleted_at' => null]);
        }

        if ($limit) {
            $this->db->limit($limit);
        }

        return $this->db->get()->result() ?: [];
    }

    /* ==========================
     * INSERT / DELETE
     * ========================== */

    public function CiInsert(array $data)
    {
        return $this->db->insert($this->tableName, $data)
            ? $this->db->insert_id()
            : false;
    }

    public function insertBatch(array $data)
    {
        $this->db->insert_batch($this->tableName, $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($where = [])
    {
        if (!is_array($where)) {
            $where = [$this->primaryKey => $where];
        }

        if (!empty($this->soft_delete)) {
            $this->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => $this->session->userdata('identity')->id ?? null,
            ], $where);
        } else {
            $this->db->delete($this->tableName, $where);
        }

        return $this->db->affected_rows();
    }

    /* ==========================
     * ATTRIBUTES
     * ========================== */

    public function attributes()
    {
        $class = get_class($this);

        if (!isset(self::$_attributesCache[$class])) {
            self::$_attributesCache[$class] = $this->db->list_fields($this->tableName);
        }

        return self::$_attributesCache[$class];
    }

    public function setAttributes($values)
    {
        if (!is_array($values)) {
            return;
        }

        $attrs = array_flip($this->attributes());

        foreach ($values as $name => $value) {
            if (isset($attrs[$name])) {
                $this->$name = $value;
            }
        }
    }

    public function formName()
    {
        $ref = new ReflectionClass($this);

        if (PHP_VERSION_ID >= 70000 && $ref->isAnonymous()) {
            throw new RuntimeException('Anonymous model must define formName()');
        }

        return $ref->getShortName();
    }

}
