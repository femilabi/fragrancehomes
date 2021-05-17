<?php
class Auth
{
    public static $JWT = "JWT";
    public static $SESSION = "SESSION";
    private $db, $authenticated = false;
    private $mode;
    private $data = [];

    function __construct($mode)
    {
        $this->db = new DBRow("user_sessions", "id");
        if ($mode) $this->mode = $mode;
        else $this->mode = self::$SESSION;
        $this->load();
    }

    private function match_ip($ip)
    {
        $user_ip = get_user_ip();
        $ips = explode('.', $ip);
        $user_ips = explode('.', $user_ip);
        return array_slice($ips, 0, 3) == array_slice($user_ips, 0, 3);
    }

    function load()
    {
        global $USER;
        if ($this->mode == self::$JWT) {
            $headers = getallheaders();
            $header = @$headers['Authorization'];
            if ($header) {
                $token = explode(" ", $header);
                $token = @$token[1];
                if ($token) {
                    $this->db->search("token", $token);
                    if ($this->db->exists()) {
                        $this->data = (array) @json_decode($this->db->get('data'), true);
                        if ($this->match_ip($this->db->get("ip_addr"))) {
                            $USER = new User($this->db->get('user_id'));
                            if ($USER->exists()) {
                                $this->authenticated = true;
                            }
                        } else {
                            $this->db = new DBRow("user_sessions", "id");
                        }
                    }
                }
            }
        } elseif ($this->mode == self::$SESSION) {
            if (!(isset($_SESSION['timeoutlastvisit']) && (time() - $_SESSION['timeoutlastvisit']) < (60 * 24))) {
                session_destroy();
                session_start();
            }
            $_SESSION['timeoutlastvisit'] = time();

            if (isset($_SESSION[USER_SESSION_HOLDER]) && is_array($_SESSION[USER_SESSION_HOLDER]) && isset($_SESSION[USER_SESSION_HOLDER]['id'])) {
                $USER = new User($_SESSION[USER_SESSION_HOLDER]['id']);
                if ($USER->exists()) {
                    $this->authenticated = true;
                }
            }
        }
    }

    function jwt_authorize($user_id, $data)
    {
        $token  = generate_new_jwt_token();
        $this->data = array_merge($this->data, $data);
        $this->db->set_data(array(
            "user_id" => $user_id,
            "token" => $token,
            "ip_addr" => get_user_ip(),
            "data" => json_encode($this->data),
            "created_date" => time()
        ));
        if ($this->db->save()) {
            $this->authenticated = true;
            return $token;
        }
    }

    function is_authenticated()
    {
        return $this->authenticated;
    }

    function set_mode($mode)
    {
        if ($mode == self::$JWT || $mode == self::$SESSION) $this->mode = $mode;
    }

    function _get($key)
    {
        return $this->db->get($key);
    }

    function set($key, $value)
    {
        if ($this->mode == self::$JWT && $this->is_authenticated()) {
            if ($key) {
                $this->data[$key] = $value;
                $this->db->set("data", json_encode($this->data));
                $this->db->save();
            }
        } else {
            $_SESSION[$key] = $value;
            return true;
        }
    }

    function get($key)
    {
        if ($this->mode == self::$JWT) {
            if (!$this->is_authenticated()) return '';
            return @$this->data[$key];
        } elseif ($this->mode == self::$SESSION) {
            return @$_SESSION[$key];
        }
    }

    function set_data($data)
    {
        if ($this->mode == self::$JWT && $this->is_authenticated()) {
            foreach ($data as $key => $value) {
                $this->data[$key] = $value;
            }
            $this->db->set("data", json_encode($this->data));
            $this->db->save();
        } elseif ($this->mode == self::$SESSION) {
            $_SESSION = array_merge($_SESSION, $data);
        }
    }

    function get_data()
    {
        if ($this->mode == self::$JWT && $this->is_authenticated()) {
            return $this->data;
        } elseif ($this->mode == self::$SESSION) {
            return $_SESSION;
        }
    }
}
