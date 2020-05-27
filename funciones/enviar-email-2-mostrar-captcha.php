<?php 
// if (!isset($_SESSION)) {session_start();}
/**
 * @author  Jose Rodriguez <jose.rodriguez@exec.cl>
 * @license GPLv3
 * @link    http://code.google.com/p/cool-php-captcha
 * @package captcha
 * @version 0.3
 */
session_start();
$captcha = new SimpleCaptcha();
$captcha->CreateImage();
class SimpleCaptcha 
{    
    public $width  = 150;
    public $height = 40;
    public $wordsFile = 'words/en.php';
    public $resourcesPath = '../librerias/resources';
    public $minWordLength = 5;
    public $maxWordLength = 5;
    public $session_var = 'captcha';
    public $backgroundColor = array(255, 255, 255);
    public $colors = array(
        array(22,163,35), // green
    );
    public $shadowColor = null; //array(0, 0, 0);
    public $lineWidth = 0;
    public $fonts = array(
        'Antykwa'  => array('spacing' => 0, 'minSize' => 27, 'maxSize' => 30, 'font' => 'AntykwaBold.ttf'),
        'Candice'  => array('spacing' => 0,'minSize' => 28, 'maxSize' => 31, 'font' => 'Candice.ttf'),
        'DingDong' => array('spacing' => 0, 'minSize' => 24, 'maxSize' => 30, 'font' => 'Ding-DongDaddyO.ttf'),
        'Duality'  => array('spacing' => 0, 'minSize' => 30, 'maxSize' => 38, 'font' => 'Duality.ttf'),
        'Heineken' => array('spacing' => 0, 'minSize' => 24, 'maxSize' => 34, 'font' => 'Heineken.ttf'),
        'Jura'     => array('spacing' => 0, 'minSize' => 28, 'maxSize' => 32, 'font' => 'Jura.ttf'),
        'StayPuft' => array('spacing' => 0,'minSize' => 28, 'maxSize' => 32, 'font' => 'StayPuft.ttf'),
        'Times'    => array('spacing' => 0, 'minSize' => 28, 'maxSize' => 34, 'font' => 'TimesNewRomanBold.ttf'),
        'VeraSans' => array('spacing' => 0, 'minSize' => 20, 'maxSize' => 28, 'font' => 'VeraSansBold.ttf'),
    );
    public $Yperiod    = 12;
    public $Yamplitude = 14;
    public $Xperiod    = 11;
    public $Xamplitude = 5;
    public $maxRotation = 5;
    public $scale = 3;
    public $blur = false;
    public $debug = false;
    public $imageFormat = 'jpeg';
    public $im;
    public function __construct($config = array()) {
    }
    public function CreateImage() {
        $ini = microtime(true);
        $this->ImageAllocate();
        $text = $this->GetCaptchaText();
        $fontcfg  = $this->fonts[array_rand($this->fonts)];
        $this->WriteText($text, $fontcfg);
        $_SESSION[$this->session_var] = $text;
        if (!empty($this->lineWidth)) 
        {
            $this->WriteLine();
        }
        $this->WaveImage();
        if ($this->blur && function_exists('imagefilter')) 
        {
            imagefilter($this->im, IMG_FILTER_GAUSSIAN_BLUR);
        }
        $this->ReduceImage();
        if ($this->debug) 
        {
            imagestring($this->im, 1, 1, $this->height-8,
                "$text {$fontcfg['font']} ".round((microtime(true)-$ini)*1000)."ms",
                $this->GdFgColor
            );
        }
        $this->WriteImage();
        $this->Cleanup();
    }
    protected function ImageAllocate() {
        if (!empty($this->im)) 
        {
            imagedestroy($this->im);
        }
        $this->im = imagecreatetruecolor($this->width*$this->scale, $this->height*$this->scale);
        $this->GdBgColor = imagecolorallocate($this->im,
            $this->backgroundColor[0],
            $this->backgroundColor[1],
            $this->backgroundColor[2]
        );
        imagefilledrectangle($this->im, 0, 0, $this->width*$this->scale, $this->height*$this->scale, $this->GdBgColor);
        $color           = $this->colors[mt_rand(0, sizeof($this->colors)-1)];
        $this->GdFgColor = imagecolorallocate($this->im, $color[0], $color[1], $color[2]);
        if (!empty($this->shadowColor) && is_array($this->shadowColor) && sizeof($this->shadowColor) >= 3) 
        {
            $this->GdShadowColor = imagecolorallocate($this->im,
                $this->shadowColor[0],
                $this->shadowColor[1],
                $this->shadowColor[2]
            );
        }
    }
    /**
     * @return string Text
     */
    protected function GetCaptchaText() {
        $text = $this->GetDictionaryCaptchaText();
        if (!$text) 
        {
            $text = $this->GetRandomCaptchaText();
        }
        return $text;
    }
    /**
     * Random text generation
     *
     * @return string Text
     */
    protected function GetRandomCaptchaText($length = null) {
        if (empty($length)) 
        {
            $length = rand($this->minWordLength, $this->maxWordLength);
        }
        $words  = "abcdefghijlmnopqrstvwyz";
        $vocals = "aeiou";
        $text  = "";
        $vocal = rand(0, 1);
        for ($i=0; $i<$length; $i++) {
            if ($vocal) {
                $text .= substr($vocals, mt_rand(0, 4), 1);
            } else {
                $text .= substr($words, mt_rand(0, 22), 1);
            }
            $vocal = !$vocal;
        }
        return $text;
    }
    /**
     * @param boolean $extended Add extended "fake" words
     * @return string Word
     */
    function GetDictionaryCaptchaText($extended = false) {
        if (empty($this->wordsFile)) {
            return false;
        }
        if (substr($this->wordsFile, 0, 1) == '/') 
        {
            $wordsfile = $this->wordsFile;
        } 
        else 
        {
            $wordsfile = $this->resourcesPath.'/'.$this->wordsFile;
        }
        if (!file_exists($wordsfile)) 
        {
            return false;
        }
        $fp     = fopen($wordsfile, "r");
        $length = strlen(fgets($fp));
        if (!$length) 
        {
            return false;
        }
        $line   = rand(1, (filesize($wordsfile)/$length)-2);
        if (fseek($fp, $length*$line) == -1) 
        {
            return false;
        }
        $text = trim(fgets($fp));
        fclose($fp);
        if ($extended) 
        {
            $text   = preg_split('//', $text, -1, PREG_SPLIT_NO_EMPTY);
            $vocals = array('a', 'e', 'i', 'o', 'u');
            foreach ($text as $i => $char) 
            {
                if (mt_rand(0, 1) && in_array($char, $vocals)) 
                {
                    $text[$i] = $vocals[mt_rand(0, 4)];
                }
            }
            $text = implode('', $text);
        }
        return $text;
    }
    protected function WriteLine() {

        $x1 = $this->width*$this->scale*.15;
        $x2 = $this->textFinalX;
        $y1 = rand($this->height*$this->scale*.40, $this->height*$this->scale*.65);
        $y2 = rand($this->height*$this->scale*.40, $this->height*$this->scale*.65);
        $width = $this->lineWidth/2*$this->scale;

        for ($i = $width*-1; $i <= $width; $i++) 
        {
            imageline($this->im, $x1, $y1+$i, $x2, $y2+$i, $this->GdFgColor);
        }
    }
    protected function WriteText($text, $fontcfg = array()) {
        if (empty($fontcfg)) 
        {
            $fontcfg  = $this->fonts[array_rand($this->fonts)];
        }
        $fontfile = $this->resourcesPath.'/fonts/'.$fontcfg['font'];
        $lettersMissing = $this->maxWordLength-strlen($text);
        $fontSizefactor = 1+($lettersMissing*0.09);
        $x      = 20*$this->scale;
        $y      = round(($this->height*27/40)*$this->scale);
        $length = strlen($text);
        for ($i=0; $i<$length; $i++) 
        {
            $degree   = rand($this->maxRotation*-1, $this->maxRotation);
            $fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize'])*$this->scale*$fontSizefactor;
            $letter   = substr($text, $i, 1);
            if ($this->shadowColor) 
            {
                $coords = imagettftext($this->im, $fontsize, $degree,
                    $x+$this->scale, $y+$this->scale,
                    $this->GdShadowColor, $fontfile, $letter);
            }
            $coords = imagettftext($this->im, $fontsize, $degree,
                $x, $y,
                $this->GdFgColor, $fontfile, $letter);
            $x += ($coords[2]-$x) + ($fontcfg['spacing']*$this->scale);
        }
        $this->textFinalX = $x;
    }
    protected function WaveImage() {
        $xp = $this->scale*$this->Xperiod*rand(1,3);
        $k = rand(0, 100);
        for ($i = 0; $i < ($this->width*$this->scale); $i++) 
        {
            imagecopy($this->im, $this->im,
                $i-1, sin($k+$i/$xp) * ($this->scale*$this->Xamplitude),
                $i, 0, 1, $this->height*$this->scale);
        }
        $k = rand(0, 100);
        $yp = $this->scale*$this->Yperiod*rand(1,2);
        for ($i = 0; $i < ($this->height*$this->scale); $i++) {
            imagecopy($this->im, $this->im,
                sin($k+$i/$yp) * ($this->scale*$this->Yamplitude), $i-1,
                0, $i, $this->width*$this->scale, 1);
        }
    }
    protected function ReduceImage() {
        $imResampled = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($imResampled, $this->im,
            0, 0, 0, 0,
            $this->width, $this->height,
            $this->width*$this->scale, $this->height*$this->scale
        );
        imagedestroy($this->im);
        $this->im = $imResampled;
    }
    protected function WriteImage() {
        if ($this->imageFormat == 'png' && function_exists('imagepng')) 
        {
            header("Content-type: image/png");
            imagepng($this->im);
        } 
        else 
        {
            header("Content-type: image/jpeg");
            imagejpeg($this->im, null, 100);
        }
    }
    protected function Cleanup() {
        imagedestroy($this->im);
    }
}

?>
