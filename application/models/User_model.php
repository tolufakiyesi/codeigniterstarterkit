<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function resolve_user_login($email, $password) {

        $this->db->select('password');
        $this->db->from('users');
        $this->db->where('email', $email);
        $hash = $this->db->get()->row('password');

        return $this->verify_password_hash($password, $hash) ;

    }

    public function get_user_id_from_email($email) {

        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('email', $email);

        return $this->db->get()->row('id');

    }

    public function get_username_from_user_id($user_id) {

        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('id', $user_id);

        return $this->db->get()->row('username');

    }

    public function create_user($user, $password) {
        $user['password'] = $this->user_model->hash_password($password);

        return $this->db->insert('users', $user);

    }

    public function get_user($user_id) {

        $this->db->from('users');
        $this->db->where('id', $user_id);
        return $this->db->get()->row();

    }

    public function get_users() {

        $this->db->from('users');
        return $this->db->get()->result();

    }

    public function update($user_id, $update_data, $password){
        $this->db->select('password');
        $this->db->from('users');
        $this->db->where('id', $user_id);
        $hash = $this->db->get()->row('password');

        if ($this->verify_password_hash($password, $hash)){
            $this->db->where('id', $user_id);
            return $this->db->update('users', $update_data);
        }
        return false;
    }

    private function hash_password($password) {

        return password_hash($password, PASSWORD_BCRYPT);

    }

    private function verify_password_hash($password, $hash) {

        return password_verify($password, $hash);

    }

    public function search($query){
        $this->db->from('journals');
        $this->db->select('title');
        $this->db->like('title', $query);
        $this->db->or_like('fulltitle', $query);

        return $this->db->get()->result();

    }



}