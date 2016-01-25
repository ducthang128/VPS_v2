<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Video_info_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }

    public function bytesToSize1024($bytes,$roundup=0) {
        if ($bytes > 0){
            $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
            $e = floor(log($bytes)/log(1024));
            if ($roundup==1){
                return sprintf('%.0f '.$s[$e], ceil($bytes/pow(1024, floor($e))));
            }
            else
            {
                return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
            }

        }else{
            return 'n/a Byte';
        }

    }

    private function file_crc($file,$intval=true) {
        $file_string = file_get_contents($file);
        $crc = crc32($file_string);
        if ($intval){
            return $crc;
        }else{
            return sprintf("%u", $crc);
        }
    }

    public function get_video_info($video_path,$video_name){
        $ffmpeg_path = 'ffmpeg';
        $duration = array();
        $bitrate = 0;
        $vwidth = 320;
        $vheight = 240;
        $owidth = 0;
        $oheight = 0;
        $sfrequency = 0;
        $audio_bitrate = 0;
        $video_file = $video_path.$video_name;

        $ffmpeg_output = array();
        $ffmpeg_cmd = $ffmpeg_path . " -i '" . $video_file . '\' 2>&1 | cat | egrep -e \'(Duration|Stream)\'';
        @exec($ffmpeg_cmd, $ffmpeg_output);
        // if file not found just return null
        if(sizeof($ffmpeg_output) == 0)return null;
        //print_r($ffmpeg_output);
        foreach($ffmpeg_output as $line){
        $ma = array();
        // get duration and video bitrate
        if(strpos($line, 'Duration:') !== false){
            preg_match('/(?<hours>\d+):(?<minutes>\d+):(?<seconds>\d+)\.(?<fractions>\d+)/', $line, $ma);
            $duration = array(
                'raw' => $ma['hours'] . ':' . $ma['minutes'] . ':' . $ma['seconds'] . '.' . $ma['fractions'],
                'hours' => intval($ma['hours']),
                'minutes' => intval($ma['minutes']),
                'seconds' => intval($ma['seconds']),
                'fractions' => intval($ma['fractions']),
//                'rawSeconds' => intval($ma['hours']) * 60 * 60 + intval($ma['minutes']) * 60 + intval($ma['seconds']) + (intval($ma['fractions']) != 0 ? 1 : 0)
                'rawSeconds' => intval($ma['hours']) * 60 * 60 + intval($ma['minutes']) * 60 + intval($ma['seconds'])
                );

            preg_match('/bitrate:\s(?<bitrate>\d+)\skb\/s/', $line, $ma);
                if (isset($ma['bitrate']) && $ma['bitrate'] > 0){
                    $bitrate = $ma['bitrate'];
                }
            }

            // get video size
            if(strpos($line, 'Video:') !== false){
                preg_match('/(?<width>\d+)x(?<height>\d+)/', $line, $ma);
                $owidth = $ma['width'];
                $oheight = $ma['height'];
                $vwidth = $owidth;
                $vheight = $oheight;
            }

            // get audio quality
            if(strpos($line, 'Audio:') !== false){
                preg_match('/,\s(?<sfrequency>\d+)\sHz,/', $line, $ma);
                $sfrequency = $ma['sfrequency'];

                preg_match('/,\s(?<bitrate>\d+)\skb\/s/', $line, $ma);
                if (isset($ma['bitrate']) && $ma['bitrate'] > 0){
                    $audio_bitrate = $ma['bitrate'];
                }
            }
            // get file checksum CRC32
            $file_CRC = $this->file_crc($video_file);
        }

        // end of image size detection
        // return all information about video and
        // data about new size with originally proportion
        return array(
            'filename' => $video_name,
            'filepath' => $video_file,
            'srcWidth' => $owidth,
            'srcHeight' => $oheight,
            'duration' => $duration,
            'bitrate' => $bitrate,
            'audioBitrate' => $audio_bitrate,
            'audioSampleFrequency' => $sfrequency,
            'checksum' => $file_CRC
            );
        }


}
?>