<?php
defined('BASEPATH') OR exit('No direct script access allowed');

trait DatatableTrait
{
    /* ==========================
     * REQUIRED BY MODEL
     * ========================== */

    abstract protected function queryAll($builder);

    public $datatable_columns = [];
    public $datatable_search  = [];
    public $datatable_order   = ['id' => 'asc'];

    /* ==========================
     * DATATABLE CORE
     * ========================== */

    protected function buildDatatableQuery()
    {
        $this->db->from($this->tableName);

        if (!empty($this->soft_delete)) {
            $this->db->where(['deleted_at' => null]);
        }

        $searchValue = $_POST['search']['value'] ?? null;

        if ($searchValue && $this->datatable_search) {
            $this->db->group_start();
            foreach ($this->datatable_search as $i => $field) {
                $i === 0
                    ? $this->db->like($field, $searchValue)
                    : $this->db->or_like($field, $searchValue);
            }
            $this->db->group_end();
        }

        $order = $_POST['order'][0] ?? null;

        if ($order) {
            $idx = (int) $order['column'];
            $dir = strtolower($order['dir']) === 'desc' ? 'desc' : 'asc';
            $col = $this->datatable_columns[$idx] ?? null;

            if ($col) {
                $this->db->order_by($col, $dir);
            }
        } elseif ($this->datatable_order) {
            $this->db->order_by(
                key($this->datatable_order),
                current($this->datatable_order)
            );
        }
    }

    /* ==========================
     * PUBLIC API
     * ========================== */

    public function getDatatables()
    {
        $this->buildDatatableQuery();

        $length = $_POST['length'] ?? -1;
        $start  = $_POST['start'] ?? 0;

        if ($length != -1) {
            $this->db->limit((int)$length, (int)$start);
        }

        return $this->queryAll($this->db);
    }

    public function countDatatablesFiltered()
    {
        $this->buildDatatableQuery();
        return $this->db->get()->num_rows();
    }

    public function countDatatablesAll()
    {
        $this->db->from($this->tableName);

        if (!empty($this->soft_delete)) {
            $this->db->where(['deleted_at' => null]);
        }

        return $this->db->count_all_results();
    }
}
