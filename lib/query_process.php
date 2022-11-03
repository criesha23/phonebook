<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/phonebook/lib/config.php');

    $phonebook = new phonebook();
    try {
        $contact_id = isset($_POST['contact_id']) ? $_POST['contact_id'] : NULL;
        $action = isset($_POST['action']) ? $_POST['action'] : NULL;
        
        $phonebook->contact_id = isset($_POST['contact_id']) ? $_POST['contact_id'] : NULL;
        $phonebook->contact_name = isset($_POST['contact_name']) ? $_POST['contact_name'] : NULL;
        $phonebook->contact_company = isset($_POST['contact_company']) ? $_POST['contact_company'] : NULL;
        $phonebook->contact_phone = isset($_POST['contact_phone']) ? $_POST['contact_phone'] : NULL;
        $phonebook->contact_address = isset($_POST['contact_address']) ? $_POST['contact_address'] : NULL;
        $phonebook->contact_email = isset($_POST['contact_email']) ? $_POST['contact_email'] : NULL;
        $phonebook->contact_image = isset($_POST['contact_image']) ? $_POST['contact_image'] : NULL;

        $path = $_SERVER['DOCUMENT_ROOT']."/phonebook/public/images/";

        if($action=='save') {
            if(!empty($_FILES['contact_image']['name'])) {
                $file_name = time().'-'.str_replace(' ','_',$_FILES['contact_image']['name']);
                $tmp = $_FILES['contact_image']['tmp_name'];
                $path = $path.strtolower($file_name); 
                $phonebook->contact_image = $file_name;
                
                
                if(!$response['error']) {
                    if(!empty(@$response['contact_image'])) {
                        $clearpath = $_SERVER['DOCUMENT_ROOT']."/phonebook/public/images/";
                        unlink($clearpath.$response['contact_image']);
                    }
                    move_uploaded_file($tmp,$path);
                }
            }   
            var_dump($phonebook);
            $response = $phonebook->save_phonebook();
            header("Location:".base_url());
            exit;
        }

        if($action=='delete') {
            $response = $phonebook->delete();
            if(!$response['error']) {
                unlink($path.$response['contact_image']);
            }
            echo json_encode($response);
            exit;
        }
        

    } catch(Exception $e) {
        echo json_encode(array('error'=>true, 'message' => (string)$e->getMessage()));
    }
?>  