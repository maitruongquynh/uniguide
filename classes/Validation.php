<?php
    class Validation {
        private $objForm;
        
        public $_errors = array();
        
        public $_errorsMessages = array();
        
        public $_message = array(
            "first_name" => "Please provide your first name.",
			"last_name" => "Please provide your last name.",
			"address_1" => "Please provide the first line of your address.",
			"address_2" => "Please provide the second line of your address.",
			"town" => "Please provide your town name.",
			"country" => "Please provide your county name.",
			"post_code" => "Please provide your post code.",
			"country" => "Please select your country.",
			"email" => "Please provide your valid email address.",

			"login" => "User name and/or password were incorrect.",
			"password" => "Please choose your password.",
			"confirm_password" => "Please confirm your password",
			"password_mismatch" => "Passwords did not match.",
			"email_duplicate" => "This email has already been registered.",
			
			"name" => "Please provide a name.",
            "short_form" => "Please provide a short form.",
			"name_duplicate" => "This name is already taken.",
            "src" => "Empty source code."
        );
        
        public $_expected = array();
        //de cho vao nhung~ thanh phan trong form can duoc dem vao xu ly
        
        public $_required = array();
        
        public $_special = array();
        
        public $_post = array();
        
        public $_post_remove = array();
        
        public $_post_format = array();
        
        public function __construct($objForm = null) {
            $this->objForm = is_object($objForm) ? $objForm : new Form();
        }
        
        public function process() {
            if($this->objForm->isPost()) {
                //neu da co cac thanh phan trong array post va trong array required co ten cac field can phai dien
                $this->_post = !empty($this->_post) ? $this->_post : $this->objForm->getPostArray($this->_expected);
                //chi lay tu array post cac thanh phan co key nam trong array expected 
                //lay vao trong array post cua objValid
                if(!empty($this->_post)) {
                    foreach($this->_post as $key => $value) {
                    //luc nay da lay xong cac thanh phan trong array post
                        $this->check($key, $value); 
                        //tien hanh kiem tra tung thanh phan trong array post
                        //thanh phan email nam trong array special
                        //nen khi vong lap chay toi thanh phan email se chay quay ham checkSpecial, tuc la chay qua ham isEmail\
                        //neu ten key co trong array required nhung gia tri lay tu post la rong thi phai bao loi
                        //cho vao array error
                    }
                }
            }
        }
        
        public function check($key, $value) {
            if(!empty($this->_special) && array_key_exists($key, $this->_special)) {
                $this->checkSpecial($key, $value);
            } else {
                if(in_array($key, $this->_required) && Helper::isEmpty($value)) {
                //neu 
                    $this->add2Errors($key);
                }
            }
        }
        
        public function add2Errors($key = null, $value = null) {
            if(!empty($key)) {
                $this->_errors[] = $key; //them vao thanh phan tiep theo, index la so, khong phai co key rieng
                if(!empty($value)) {
                    $this->_errorsMessages['valid_'.$key] = $this->wrapWarn($value); 
                    //value dung de tao ra validation message rieng khac voi message da co san trong array cua object
                } elseif (array_key_exists($key, $this->_message)) {
                    $this->_errorsMessages['valid_'.$key] = $this->wrapWarn($this->_message[$key]);
                }
            }
            
        }
        
        public function checkSpecial($key, $value) {
            switch($this->_special[$key]) {
                case('email'):
                if(!$this->isEmail($value)) {
                    $this->add2Errors($key);
                }
                break;
            }
        }
        
        public function isEmail($email = null) {
            if(!empty($email)) {
                $result = filter_var($email, FILTER_VALIDATE_EMAIL);
                return !$result ? false : true;
            }
            return false;
        }
        
        public function isValid($array = null) {
            //phai cho ham nay chay thi process moi duoc chay
            //sau khi process chay xong thi se dua het error vao trong array error
            if(!empty($array)) {
                $this->_post = $array;
            }
            $this->process();
            if (empty($this->_errors) && !empty($this->_post)) {
                //remove all unwanted fields
                if(!empty($this->_post_remove)) {
                    //neu co thanh phan nao trong post remove, tuc la thanh phan nay la mot field trong form nhung khi xu ly khong can dung den
                    //thi xoa ra khoi array post
                    foreach($this->_post_remove as $value) {
                        unset($this->_post[$value]);
                    }
                }
                //format all required field
                if(!empty($this->_post_format)) {
                    foreach($this->_post_format as $key => $value) {
                        $this->format($key, $value);
                    }
                }
                return true;
            }
            return false;
        }
        
        public function format($key, $value) {
            switch($value) {
                case 'password':
                $this->_post[$key] = Login::string2hash($this->_post[$key]);
                break;
            }
        }
        
        public function validate($key) {
            if(!empty($this->_errors) && in_array($key, $this->_errors)) {
                return $this->wrapWarn($this->_message[$key]);
            }
            //method nay de hien thi loi~ cu the cua mot field
            //duoc goi ra ngay truoc field do trong form
        }
        
        
        public function wrapWarn($mess = null) {
            if(!empty($mess)) {
                return "<span class=\"warn\">{$mess}</span>";
            }
        }     
    }
?>