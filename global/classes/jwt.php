<?php
class JWT {
    private $db, $authenticated = false;

    function __construct() {
        $this->db = new DBRow("user_sessions", "id");
    }

    function load($token) {
        $this->db->search("token", $token);
        if ($this->db->exists()) {
            if ($this->db->get("ip_addr") == get_user_ip() || true) {
                $this->authenticated = true;
            } else {
                $this->db = new DBRow("user_sessions", "id");
            }
        }
    }

    function initialize($user_id, $data) {
        $this->db->set_data(array(
            "user_id" => $user_id,
            "token" => generate_new_jwt_token(),
            "ip_addr" => get_user_ip(),
            "data" => json_encode($data),
            "created_date" => time()
        ));
        if ($this->db->save()) {
            $this->authenticated = true;
        }
    }

    function is_authenticated() {
        return ($this->db->exists() && $this->authenticated);
    }

    function get($key) {
        return $this->db->get($key);
    }

    // function set_session($key, $value) {
    //     if (!$this->is_authenticated()) return '';
    //     $session_data = json_decode($this->db->get("data"), true);
    //     if ($key) {
    //         $session_data[$key] = $value;
    //         $this->db->set("data", json_encode($session_data));
    //     }
    // }

    // function get_session($key) {
    //     if (!$this->is_authenticated()) return '';
    //     $session_data = json_decode($this->db->get("data"), true);
    //     return @$session_data[$key];
    // }

    function set_session_data($data) {
        if (!$this->is_authenticated()) return '';
        $session_data = json_decode($this->db->get("data"), true);
        foreach ($data as $key => $value) {
            $session_data[$key] = $value;
        }
        $this->db->set("data", json_encode($session_data));
    }
    
    function get_session_data () {
        if (!$this->is_authenticated()) return '';
        $session_data = json_decode($this->db->get("data"), true);
        return $session_data;
    }

    function __destruct () {
        GLOBAL $USER;
        if (is_object($USER)) {
            $this->set_session_data($USER->get_data());
            $this->db->save();
        }
    }
}