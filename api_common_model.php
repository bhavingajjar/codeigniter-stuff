<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Bhavin Gajjar
 * User: Bhavin
 * Date: 25-Jun-2018
 */
class Api_common_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('UTC');
    }

    public function get_data($table_name, $where = null, $select = null, $isRow = false, $orderBy = null, $limit = null, $offset = null)
    {
        $select = ($select != null) ? $select : '*';
        if ($isRow) {
            if (!empty($orderBy)) {
                return $this->db->select($select)->order_by($orderBy)->get_where($table_name, $where, $limit, $offset)->row();
            } else {
                return $this->db->select($select)->get_where($table_name, $where, $limit, $offset)->row();
            }
        } else {
            if (!empty($orderBy)) {
                return $this->db->select($select)->order_by($orderBy)->get_where($table_name, $where, $limit, $offset)->result();
            } else {
                return $this->db->select($select)->get_where($table_name, $where, $limit, $offset)->result();
            }
        }
    }

    public function insert_data($table_name, $data)
    {
        $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function insert_data_batch($table_name, $data)
    {
        return $this->db->insert_batch($table_name, $data);
    }

    /*
     * where is an array
     */
    public function update_data($table_name, $data, $where)
    {
        return $this->db->update($table_name, $data, $where);
    }

    /*
     * where is a string*/
    public function update_data_batch($table_name, $data, $where)
    {
        return $this->db->update_batch($table_name, $data, $where);
    }

    public function delete_data($table_name, $where)
    {
        return $this->db->delete($table_name, $where);
    }

    public function query($query, $isRow = false,$type=null)
    {
        if ($isRow) {
            return $this->db->query($query)->row();
        } else {
            if($type=='null')
                return $this->db->query($query)->result_array();
            else
                return $this->db->query($query)->result();                
        }
    }

    /*
     * join is an array
     * $response = $this->api_common_model->join('front_officeman_courses', [
                        [
                            'table_name' => 'front_officeman_topics',
                            'relationship' => 'front_officeman_topics.course_id = front_officeman_courses.course_id'
                        ]
                    ], [
                        'front_officeman_courses.curriculum_id' => $this->curriculum_id['curriculum_id']
                    ]);
     * */
public function join($table_name, $joins, $where = null, $select = null, $type = 'LEFT', $isRow = false, $orderBy = null,$limit = null)
    {
        $select = ($select != null) ? $select : '*';
        $this->db->select($select,FALSE);
        $this->db->from($table_name);
        foreach ($joins as $key => $join) {
            $this->db->join($join['table_name'], $join['relationship'], $type);
        }
        $this->db->where($where);
        if(!empty($orderBy))
            $this->db->order_by($orderBy);
        if($limit != null)
            $this->db->limit($limit);
        if ($isRow) {
            return $query = $this->db->get()->row();
        } else {
            return $query = $this->db->get()->result();
        }
    }

    public function join_update($table_name, $joins, $setdata, $where = null, $updatetable)
    {
        return $this->db->join($table_name, $joins)->set($setdata)->where($where)->update($updatetable);
    }

}
