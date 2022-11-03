<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/phonebook/lib/status_message.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/phonebook/lib/mysql_connect.php');

    class phonebook {
        public $contact_id;
        public $contact_name;
        public $contact_company;
        public $contact_phone;
        public $contact_address;
        public $contact_email;
        public $contact_image;


        public function __construct() {
            $db = new config();
            $this->db = $db->connect();
        }
        
        private function fetch_all($query) {
            $data = [];
            while($row = $query->fetch_assoc()) {
                array_push($data,(object) $row);
            }
            return $data;
        }

        private function first_row($query) {
            $data = (object) $query->fetch_assoc();
            return $data;   
        }

        public function get_contact_list() {
            $query = $this->db->query("SELECT * FROM tbl_contacts");
            $query = $this->fetch_all($query);

            if(!$query){
                return $this->db->error;
            }

            return $query;
        }


        public function save_phonebook() {
            try {
                if(empty($this->contact_name) || 
                    empty($this->contact_company) || 
                    empty($this->contact_phone) || 
                    empty($this->contact_address) || 
                    empty($this->contact_email)) {
                        throw new Exception(REQUIRED_FIELD);
                }
                echo "INSERT INTO tbl_contacts(
                    contact_name, contact_company, contact_phone,
                    contact_address, contact_email, contact_image
                )  VALUES(
                    '$this->contact_name', '$this->contact_company', '$this->contact_phone',
                    '$this->contact_address', '$this->contact_email', '$this->contact_image')";
                    
                if(empty($this->contact_id)) {
                    $query = $this->db->query("INSERT INTO tbl_contacts(
                            contact_name, contact_company, contact_phone,
                            contact_address, contact_email, contact_image
                        )  VALUES(
                            '$this->contact_name', '$this->contact_company', '$this->contact_phone',
                            '$this->contact_address', '$this->contact_email', '$this->contact_image')
                    ");
            
                    if(!$query) {
                        throw new Exception(REQUIRED_FIELD);
                    }

                    return array('error'=>false, 'message' => SUCCESS);

                } else {
                    // $contact_row = $this->db->query("SELECT contact_image FROM tbl_contacts WHERE tbl_contacts.ID='$this->ID'");
                    // $contact_row = $this->first_row($contact_row);
                    $image = $this->contact_image;
                    $query = $this->db->query("UPDATE tbl_contacts SET
                        contact_name = '$this->contact_name', contact_company = '$this->contact_company',
                        contact_phone = '$this->contact_phone', contact_address = '$this->contact_address',
                        contact_email = '$this->contact_email', contact_image = '$this->contact_image'
                        WHERE contact_id ='$this->contact_id' ");

                    if(!$query) {
                        throw new Exception(REQUIRED_FIELD);
                    }

                    return array('error'=>false, 'message' => SUCCESS, 'contact_image'=>$image);
                }
    
            } catch(Exception $e) {
                return array('error'=>true, 'message' => (string)$e->getMessage());
            }

        }

        public function delete() {
            try {
                if(empty($this->contact_id) ) {
                    throw new Exception(REQUIRED_FIELD);
                }

                $contact_row = $this->db->query("SELECT contact_image FROM tbl_contacts WHERE contact_id='$this->contact_id'");
                $contact_row = $this->first_row($contact_row);

                $query = $this->db->query("DELETE FROM tbl_contacts WHERE contact_id='$this->contact_id'");
            
                if(!$query){
                    throw new Exception(REQUIRED_FIELD);
                }

                return array('error'=>false, 'message' => 'DELETED', 'Image_name'=>$contact_row->Image_name);

            } catch(Exception $e) {
                return array('error'=>true, 'message' => (string)$e->getMessage());
            }
        }
    }
?>