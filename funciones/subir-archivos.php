<?php if (!isset($_SESSION)) {session_start();}

    $subida = new Subida();
    $subida->CargarModulo("");
    $subida->CargarCarpeta($_POST['carpeta']);
    $subida->Subir();
    class Subida
    {
        public function CargarModulo($modulo) {$this->modulo = $modulo;}
        public function CargarCarpeta($carpeta) {$this->carpeta = $carpeta;}
        public function Subir()
        {
            if(isset($_POST["submit"]))
            {
                $target_dir = $this->modulo . $this->carpeta;
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                echo '<div class="foto-carnet" id="nueva-foto"><img src="'.$target_file.'"/></div><br/>';
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                
                if (file_exists($target_file)) 
                {
                    $razon = "ya existe";
                    $uploadOk = 0;
                }
                
                if ($_FILES["fileToUpload"]["size"] > 20000000) 
                {
                    $razon = "es muy grande";
                    $uploadOk = 0;
                }
                
                if ($uploadOk == 0) 
                {
                    echo '<div class="mensaje-rojo">';
                        echo "Perdon pero su archivo no fue subido por que ".$razon.".<br/>";
                    echo '</div>';
                } 
                else 
                {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
                    {
                        echo '<div class="mensaje-verde">';
                            echo "El archivo ". basename($_FILES["fileToUpload"]["name"]). " ha sido subido exitosamente ".'<br/>';
                            echo "en la url ".$this->modulo.$this->carpeta;
                        echo '</div>';
                    } 
                    else 
                    {
                        echo '<div class="mensaje-rojo">';                    
                            echo "Perdon, hubo algun error al subir su archivo. Por favor intente de nuevo.<br/>";
                        echo '</div>';
                    }
                }
            }
        }
    }
?>