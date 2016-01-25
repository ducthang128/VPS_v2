<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class File_slipt_merge extends CI_Model {
        public function __construct() {
        parent::__construct();
        $Class = $this->router->fetch_class();
        $Action = $this->router->fetch_method();
//        if ($Class != 'welcome' && $Action != 'remote_control'){
//            if (!$this->ion_auth->logged_in()){
//                return null;
//            }
//            $this->user_info = (array)$this->ion_auth->user()->row();
//            $this->group_info = (array)$this->ion_auth->group($this->user_info['id'])->row();
//        }
    }

    private function write_log($log_name,$content=''){
        $current_time = time().": ";
        if(!file_exists($log_name)){
            $fp = fopen($log_name,"w+");
            fwrite($fp,"Start log");
            fclose($fp);
        }
        return file_put_contents($log_name, $current_time.$content."\n", FILE_APPEND | LOCK_EX);
    }

    private function time_elapsed($secs){
        $bit = array(
            ' năm'        => $secs / 31556926 % 12,
            ' tuần'        => $secs / 604800 % 52,
            ' ngày'        => $secs / 86400 % 7,
            ' giờ'        => $secs / 3600 % 24,
            ' phút'    => $secs / 60 % 60,
            ' giây'    => $secs % 60
            );
        $ret[] = 'Thời gian đã qua: ';
        foreach($bit as $k => $v){
            if($v > 1)$ret[] = $v . $k;
            if($v == 1)$ret[] = $v . $k;            }
        array_splice($ret, count($ret)-1, 0, ',');
        return join(' ', $ret);
    }

    private function microtime_float(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public function split_file($file_name='') {
        if (!file_exists($file_name)){
            return "false";
        }
        $start_time = $this->microtime_float();
        $path_parts = pathinfo($file_name);
        $part_location = $path_parts['dirname'].'/'.$path_parts['filename'].'/';
        $log_name = $path_parts['dirname'].'/info.log';
        $this->write_log($log_name, 'Start slipt file: '.$file_name.' to multi-part.');
        $handle = fopen($file_name, 'r');
        if ($handle == false){ return false; }
        $file_size = filesize($file_name);
        $part_size_max = 0.5 * 1024 * 1024; //1 MB
        $parts_num = floor($file_size/$part_size_max);
        $parts_num = ( $parts_num > 100 ) ? 100 : $parts_num;
        $parts_size = floor($file_size/$parts_num);
        $modulus=$file_size % $parts_num;
        for($i=0;$i<$parts_num;$i++) {
            if($modulus!=0 & $i==$parts_num-1){
                $parts[$i] = fread($handle,$parts_size+$modulus);
            }else{
                $parts[$i] = fread($handle,$parts_size);
            }
            if ($parts[$i] == false){ return false; }
        }
        //close file handle
        $file_closed = fclose($handle);
        if ($file_closed == false){ return false; }
        //writing to splited files
        if (!file_exists($part_location)) {
            mkdir($part_location, 0777, true);
        }
        for($i=0;$i<$parts_num;$i++) {
            $name_part = $path_parts['filename'].'_splited_'.$i.'.'.$path_parts['extension'];
            $part_file_name = $part_location.$name_part;
            if (file_exists($part_file_name)){
                unlink($part_file_name);
            }
            $handle = fopen($part_file_name, 'w+');
            if ($handle == false){ return false; }
            $write_part = fwrite($handle,$parts[$i]);
            if ($write_part == false){ return false; }
            $crc_part = crc32($parts[$i]);
            $this->write_log($log_name, 'Finish write a part of file: '.$part_file_name.' - CRC value: '.strval($crc_part));
            $exit_part = $this->db->get_where('multipart_clips', array('checksum' => $crc_part,'name_part' => $name_part,));
            $data = array(
                    'checksum' => $crc_part,
                    'soure_part' => $file_name,
                    'path_part' => $part_file_name,
                    'name_part' => $name_part,
                    'num_part' => $i,
                    'total_part' => $parts_num
                );
            if ($exit_part->num_rows() > 0){//update
                $this->db->where('name_part',$name_part)->update('multipart_clips', $data);
            }else{//add new
                $this->db->insert('multipart_clips', $data);
            }
        //close file handle
        $file_closed = fclose($handle);
        if ($file_closed == false){ return false; }
        }
        $finish_time = $this->microtime_float();
        $this->write_log($log_name, 'Finish slipt file, location saved: '.$part_location);
        $this->write_log($log_name, 'Time elapsed: '. abs($finish_time - $start_time). '(s).');
        return $part_location;
    }//end of function split_file

    /*------------------------------------------------------------------
    - MERGE -
    - This function merges splited files that are splited with above -
    - split_file function. -
    --------------------------------------------------------------------*/
    public function merge_file($file_folder='', $file_name = '') {
        $start_time = $this->microtime_float();
        $part_list = array();
        if (is_dir($file_folder)){
            if ($dh = opendir($file_folder)){
                while (($file = readdir($dh)) !== false){
                    $sFileName = strval($file);
                    if (strpos($sFileName,'splited_') !== false) {
                        array_push($part_list,$file_folder.$sFileName);
                    }
                }
                sort($part_list);
            }
            closedir($dh);
        }else{
            return false;
        }
        $parts_num = sizeof($part_list);
        $path_parts = pathinfo($part_list[0]);
        $suffix = explode('_',$path_parts['filename']);
        for($i=0;$i<$parts_num;$i++) {
            $suffix = array_diff($suffix, array("splited", strval($i)));
        }
        $log_name = $path_parts.'info.log';
        $this->write_log($log_name, 'Start joint file in folder '.$file_folder.' to one-part.');
        $content='';
        //put splited files content into content
        for($i=0;$i<$parts_num;$i++) {
            $part_name = $path_parts['dirname'].'/'.$suffix[0].'_splited_'.$i.'.'.$path_parts['extension'];
            $file_size = filesize($part_name);
            $handle = fopen($part_name, 'r');
            if ($handle == false){ return false; }
            $read_part = fread($handle, $file_size);
            if ($read_part == false){ return false; }
            $content .= $read_part;
        }
        //write content to merged file
        if ($file_name == ''){
            $file_name = $suffix[0];
        }
        $file_write = $path_parts['dirname'].'/merged_'.$file_name.'.'.$path_parts['extension'];
        if (file_exists($file_write)){
            unlink($file_write);
        }

        $handle=fopen($file_write, 'w+');
        if ($handle == false){ return false; }
        $this->write_log($log_name, 'File name to saved: '.$file_folder.'/'.$file_name.' to one-part.');
        $write_file = fwrite($handle, $content);
        if ($write_file == false){ return false; }
        $finish_time = $this->microtime_float();
        $this->write_log($log_name, 'Finish slipt file, location saved: '.$file_write);
        $this->write_log($log_name, 'Time elapsed: '. abs($finish_time - $start_time). '(s.)');
        return $file_write;
    }//end of function merge_file
}
?>