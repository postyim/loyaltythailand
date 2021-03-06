<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 *  @Auther : Adisorn Lamsombuth
 *  @Email : postyim@gmail.com
 *  @Website : esabay.com 
 */

/**
 * Description of products_model
 *
 * @author R-D-6
 */
class Users_wholesale_model extends CI_Model {

    function get_listall() {
        $this->load->library('datatables');
        $this->load->helper('settings/useful');

        $this->datatables->select(
                'users.id as id, '
                . 'nick_name, '
                . 'groups.name as group_name, '
                . 'email, '
                . 'first_name, '
                . 'last_name, '
                . 'company, '
                . 'phone, '
                . 'created_on, '
                . 'last_login, '
                . 'active'
                . '');
        $this->datatables->from('users');
        $this->datatables->join('users_groups', 'users_groups.user_id=users.id', 'inner');
        $this->datatables->join('groups', 'groups.id=users_groups.group_id', 'inner');
        $this->datatables->where('users_groups.user_parent', $this->ion_auth->get_user_id());
        $this->datatables->where('groups.name', 'members');
        $this->datatables->or_where('groups.name', 'seller');
        $link = "<div class=\"dropdown\">";
        $link .= "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"javascript:;\"><span class=\"fa fa-pencil-square-o\"></span ></a>";
        $link .= "<ul class=\"dropdown-menu\" role=\"menu\" aria-labelledby=\"dLabel\">";
        $link .= "<li><a href=\"javascript:;\" rel=\"settings/backend/users/edit/$1\" class=\"link_dialog\" title=\"Edit Users\">Edit Users</a></li>";
        $link .= "<li><a href=\"javascript:;\" rel=\"settings/backend/result_users_wholesale/delete/$1\" class=\"link_dialog delete\" title=\"Delete Users\">Delete Users</a></li>";
        $link .= "</ul>";
        $link .= "</div>";

        $this->datatables->edit_column('id', $link, 'id');
        $this->datatables->edit_column('first_name', '$1 $2', 'first_name, last_name');
        $this->datatables->edit_column('active', '$1', 'check_disabled(active,1)');
        $this->datatables->edit_column('created_on', '$1', 'date("d-m-Y h:i:s",created_on)');
        $this->datatables->edit_column('last_login', '$1', 'date("d-m-Y h:i:s",last_login)');
        $this->datatables->unset_column('last_name');
        return $this->datatables->generate();
    }

    function get_group($arr = array()) {
        $this->db->select('*');
        $this->db->from('groups');
        if (count($arr) > 0) {
            $this->db->where_in('name', $arr);
        }
        $query = $this->db->get();
        $arr_cat = array(
            '' => 'Please select'
        );
        foreach ($query->result() as $val) {
            $arr_cat[$val->id] = $val->name;
        }

        return $arr_cat;
    }

    public function get_option_groups($id) {
        $query = $this->db->get_where('users_groups', array('user_id' => $id));
        if ($query->num_rows() > 0) {
            $data = $query;
        } else {
            $data = null;
        }
        return $data;
    }

    public function get_edit($id) {
        $this->db->select('users.*,users_groups.group_id');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id=users.id', 'inner');
        $this->db->where('users.id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_user_parent($id_array) {
        $this->db->select('users.id, users.username');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id=users.id', 'inner');
        $this->db->join('groups', 'groups.id=users_groups.group_id', 'inner');
        $this->db->where_in('groups.name', $id_array);
        $query = $this->db->get();
        $arr_cat = array(
            '' => 'Please select'
        );
        foreach ($query->result() as $val) {
            $arr_cat[$val->id] = $val->username;
        }

        return $arr_cat;
    }

    public function get_group_menu($id) {
        $this->db->select('menu_id');
        $this->db->from('menus_groups');
        $this->db->where('group_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function add() {
        $parent_id = $this->Ion_auth_model->get_group_id($this->ion_auth->get_user_id());
        $password = $this->Ion_auth_model->hash_password(trim($this->input->post('password')), FALSE);
        $data = array(
            'first_name' => trim($this->input->post('first_name')),
            'last_name' => trim($this->input->post('last_name')),
            'nick_name' => trim($this->input->post('nick_name')),
            'password' => $password,
            'email' => trim($this->input->post('email')),
            'ip_address' => $this->input->ip_address(),
            'created_on' => time(),
            'active' => ($this->input->post('active', TRUE) ? 1 : 0)
        );
        $this->db->insert('users', $data);
        $user_id = $this->db->insert_id();
        $this->db->insert('users_groups', array('user_id' => $user_id, 'group_id' => $this->input->post('group_id'), 'user_parent' => $parent_id));
        $this->db->insert('users_branch', array('user_id' => $user_id, 'user_parent' => $parent_id));
        $rdata = array(
            'status' => TRUE,
            'redirect' => 'settings/backend/users',
            'message' => 'Save data success.'
        );

        return $rdata;
    }

    public function group_add() {
        $data = array(
            'name' => trim($this->input->post('name')),
            'description' => trim($this->input->post('description'))
        );
        $this->db->insert('groups', $data);
        $group_id = $this->db->insert_id();
        if ($this->input->post('menu')) {
            foreach ($this->input->post('menu') as $item) {
                $data2 = array(
                    'menu_id' => $item,
                    'group_id' => $group_id
                );
                $this->db->insert('menus_groups', $data2);
            }
        }
        $rdata = array(
            'status' => TRUE,
            'redirect' => 'settings/backend/users/group',
            'message' => 'Save data success.'
        );

        return $rdata;
    }

    function edit() {
        $this->db->update('users_groups', array('group_id' => $this->input->post('group_id')), array('user_id' => $this->input->post('user_id')));
        $data = array(
            'first_name' => trim($this->input->post('first_name')),
            'last_name' => trim($this->input->post('last_name')),
            'nick_name' => trim($this->input->post('nick_name')),
            'active' => ($this->input->post('active', TRUE) ? 1 : 0),
            'updated_on' => time()
        );
        $this->db->update('users', $data, array('id' => $this->input->post('user_id')));
        $rdata = array(
            'status' => TRUE,
            'redirect' => 'settings/backend/users',
            'message' => 'Save data success.'
        );

        return $rdata;
    }

    public function group_edit() {
        $this->ion_auth->update_group($this->input->post('id'), trim($this->input->post('name')), trim($this->input->post('description')));
        if ($this->input->post('menu')) {
            $this->db->delete('menus_groups', array('group_id' => $this->input->post('id')));
            foreach ($this->input->post('menu') as $item) {
                $this->db->insert('menus_groups', array('menu_id' => $item, 'group_id' => $this->input->post('id')));
            }
        }
        $rdata = array(
            'status' => TRUE,
            'redirect' => 'settings/backend/users/group',
            'message' => 'Save data success.'
        );

        return $rdata;
    }

    public function delete() {
        $this->ion_auth->delete_user($this->uri->segment(5));
        $rdata = array(
            'status' => TRUE,
            'redirect' => 'settings/backend/users',
            'message' => 'Save data success.'
        );

        return $rdata;
    }

    public function group_delete() {
        $this->ion_auth->delete_group($this->uri->segment(5));
        $rdata = array(
            'status' => TRUE,
            'redirect' => 'settings/backend/users/group',
            'message' => 'Save data success.'
        );

        return $rdata;
    }

}
