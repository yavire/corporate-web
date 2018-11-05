
<?php
 
    include(NAKAI_DIR . 'libs/yavSingleton.php');

    class yavDatabase extends yavSingleton {   

        protected $label;
        protected $pdo;

        public function setConnection()
        {

            $pdo = null;
            $error = null;

            require(NAKAI_DIR.'tienda-online/config/settings.inc.php');
            $dbname = _DB_NAME_;
            $dbServer =  _DB_SERVER_;
            $user = _DB_USER_;
            $pass = _DB_PASSWD_;
            $tns = 'mysql:host=' . $dbServer .';dbname=' . $dbname;


            try {
                //$conexion = new PDO('mysql:host=localhost:3307;dbname=bitnami_prestashop', 'bn_prestashop', '5200c3ccb9');
                /*$conexion = new PDO($tns, $user, $pass);

                print "Hola\n";
	
                $sql = "SELECT id_currency FROM prstshp_currency";
                $result = $conexion->query($sql);
                print_r($result);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                    }
                } else {
                    echo "0 results";
                }*/

                // Create connection


	        } catch (PDOException $e) {
                $error = $e->getMessage();
                print $error;
	        }

            return $error;
            
        }

        public function getConnection()
        {
            return $this->pdo;
        }

        public function selectQuery($conn, $sql, $sqlData, &$error) {

            try {
                
                //print_r($sqlData);
                //print_r($sql);

                $statement = $conn->prepare($sql);
                $statement->execute($sqlData);
                $resul = $statement->fetch();

                /*echo "***********";

                print_r($resul);

                echo "***********";

                /*foreach ($resultados as $fila) {
                 	echo $fila['ID'] . ' - ' . $fila['nombre'] . '<br />';
                 }*/

                 return $resul;

            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
                $error = $e->getMessage();
            }

        }


        public function insertQuery() {

            try {
                // echo "Conexion OK";

                // Metodo Query
                // $resultados = $conexion->query("SELECT * FROM usuarios WHERE id = 5");

                // foreach ($resultados as $fila) {
                // 	echo $fila['ID'] . ' - ' . $fila['nombre'] . '<br />';
                // }

                // Prepared Statements
                $statement = $conexion->prepare('INSERT INTO usuarios VALUES(null, "Jose")');
                $statement->execute();

                $resultados = $statement->fetchAll();
                foreach($resultados as $usuario){
                    echo $usuario['nombre'] . '<br>';
                }


            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }

        }



    }

?>