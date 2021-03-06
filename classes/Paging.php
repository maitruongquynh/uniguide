<?php
    class Paging {
        public $objUrl;
        private $_records;
        private $_max_pp;
        private $_numb_of_pages;
        private $_current;
        private $_offset = 0;
        public static $_key = 'pg';
        public $_Url;
        
        public function __construct($objUrl = null, $rows = null, $max = 10) {
            $this->objUrl = is_object($objUrl) ? $objUrl : new Url() ;
            $this->_records = $rows;
            $this->_numb_of_records = count($this->_records);
            $this->_max_pp = $max;
            $this->_Url = $this->objUrl->getCurrent(array(self::$_key, 'call'));
            $current = $this->objUrl->get(self::$_key);
            $this->_current = !empty($current) ? $current : 1;
            $this->numberOfPages();
            $this->getOffset();
        }
        
        private function numberOfPages() {
            $this->_numb_of_pages = ceil($this->_numb_of_records/$this->_max_pp);
        }
        
        private function getOffset() {
            $this->_offset = ($this->_current - 1) * $this->_max_pp;
        }
        //tong so thanh phan o tat ca cac trang truoc trang hien tai
        
        public function getRecords() {
            $out = array();
            if($this->_numb_of_pages > 1) {
                $last = ($this->_offset + $this->_max_pp);
                for ($i = $this->_offset; $i < $last; $i++) {
                    if ($i < $this->_numb_of_records) {
                        $out[] = $this->_records[$i];
                    }
                }
            } else {
                $out = $this->_records;
            }
            return $out;
        }
        //ham nay lay ra so thanh phan duoc trinh bay ra trong 1 trang
        
        public function getLinks() {
            if($this->_numb_of_pages > 1) {
                $out = array();
                if($this->_current > 1) {
                    $out[] = '<a href="/'.root().$this->_Url.PAGE_EXT.'">First</a>';
                    //property Url duoc goi tu function get Url o tren, da~ bo attribute pg di roi, nen khong co pg, ma mac dinh k co pg
                    //tuc la trang 1
                } else {
                    $out[] = "<span>First</span>";
                    //neu trang dang xem la trang so 1 thi khong can de link ma de span de css lam noi bat
                }
                
                if($this->_current > 1) {
                    $id = ($this->_current - 1);
                    $Url = $id > 1 ? $this->_Url."/".self::$_key."/".$id.PAGE_EXT : $this->_Url.PAGE_EXT;
                    $out[] = '<a href="/'.root().$Url.'">Previous</a>';
                } else {
                    $out[] = "<span>Previous</span>";
                }
                
                if($this->_current != $this->_numb_of_pages) {
                    $id = ($this->_current + 1);
                    $Url = $this->_Url."/".self::$_key."/".$id.PAGE_EXT;
                    $out[] = '<a href="/'.root().$Url.'">Next</a>';
                } else {
                    $out[] = "<span>Next</span>";
                }
                
                if($this->_current != $this->_numb_of_pages) {
                    $Url = $this->_Url."/".self::$_key."/".$this->_numb_of_pages;
                    $out[] = '<a href="/'.root().$Url.'">Last</a>';
                } else {
                    $out[] = "<span>Last</span>";
                }
                
                return "<li>" . implode("</li><li>", $out) . "</li>";
            }
        }
        
        public function getPaging() {
            $links = $this->getLinks();
            if(!empty($links)) {
                $out = "<ul class=\"paging\">" . $links . "</ul>";
                return $out;
            }
        }
    }
?>