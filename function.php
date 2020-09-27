<?php

    // RISOLUZIONE PROBLEMA HEADER

    ob_start();

    // INIZIO SESSIONE

    session_start();

    if (array_key_exists('id', $_COOKIE)) {
        
        $_SESSION['id'] = (int)$_COOKIE['id'];

    } 

    // CONNESSIONE DATABASE

    $servername = '';
    $username = '';
    $password = '';
    $dbname = '';

    $link = new mysqli($servername, $username, $password, $dbname);

    if ($link->connect_errno) {

        die ('Connessione fallita: ' . $link->connect_error);
        
    }

    // STILE PER GLI ERRORI

    function alertStyle() {
        echo '<style type="text/css">';
        echo '
        @import url("https://fonts.googleapis.com/css?family=Lato:400&display=swap");

        * {
            font-family: "Lato", sans-serif;
        }

        .alertsuccess {
            height: 84px;
            width: 90%;
            border-radius: 10px;
            box-sizing: border-box;
            margin: 10px auto;
            font-size: 21px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgb(217, 236, 219);
            border: 1px solid rgb(202, 229, 205);
            color: rgb(42, 85, 42);
        }

        .alerterror {
            height: 84px;
            width: 90%;
            border-radius: 10px;
            box-sizing: border-box;
            margin: 10px auto;
            font-size: 21px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgb(243, 216, 218);
            border: 1px solid rgb(238, 200, 203);
            color: rgb(105, 35, 38);
        }
        
        #return_home, #return {
            cursor: pointer;
            height: 84px;
            width: 90%;
            background: #FFFFFF;
            border: 1px solid #CCCCCC;
            box-sizing: border-box;
            border-radius: 10px;
            font-size: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666666;
            margin: 0 auto;
            text-decoration: none;
        }
        
        #return_home:hover, #return:hover {
            border: 1px solid #666666;
        }
        
        #return_home:active, #return:active {
            background: #666666;
            color: #FFFFFF;
        }
        
        @media screen and (max-width: 700px) {
            .alertsuccess, .alerterror {
                height: 63px;
                font-size: 16px;
                padding-right: 21px;
                padding-left: 21px;
            }

            #return_home, #return {
                height: 63px;
                font-size: 31.5px;
            }
        }';
        echo '</style>';
    }

    // TORNARE INDIETRO

    function historyReturn() {
        echo '<script type="text/javascript">';
        echo 'document.getElementById("return").addEventListener("click", function(){
                  window.history.back();
              });';
        echo '</script>';
    }

    // CONTROLLO UTENTE

    function isAdmin() {

        if ($_SESSION['id'] == 1) {

            return true;

        } else {

            return false;

        }

    }

    // FORMATTAZIONE STRINGA

    function formatString($string) {

        $string = trim($string);

        $string = strip_tags($string);

        $string = preg_replace('!\s+!', ' ', $string);

        return $string;

    }

    // NUMERO RIGHE NEL DATABASE

    function countRows($table, $condition, $type, $param) {

        global $link;

        $query = "SELECT COUNT(id) AS result FROM $table WHERE $condition = ? LIMIT 1";
                            
        $stmt = $link->prepare($query);

        $stmt->bind_param($type, $param);

        $stmt->execute();

        if (!$stmt->error) {

            $result = $stmt->get_result();

            $stmt->close();

            $result = $result->fetch_assoc();

            $total = $result['result'];

            if ($total > 0) {

                return true;

            } else {

                return false;

            }

        } else {

            return false;

        }
    }

    // NUMERO RIGHE DOPPIA CONDIZIONE

    function countRowsDouble($table, $first_condition, $second_condition, $type, $first_param, $second_param) {

        global $link;

        $query = "SELECT COUNT(id) AS result FROM $table WHERE $first_condition = ? AND $second_condition = ? LIMIT 1";
                            
        $stmt = $link->prepare($query);

        $stmt->bind_param($type, $first_param, $second_param);

        $stmt->execute();

        if (!$stmt->error) {

            $result = $stmt->get_result();

            $stmt->close();

            $result = $result->fetch_assoc();

            $total = $result['result'];

            if ($total > 0) {

                return true;

            } else {

                return false;

            }

        } else {

            return false;

        }
    }

    // VALIDAZIONE EMAIL

    function emailVal($email, $email_conf = NULL) {

        global $error;

        if (empty($email)) {
            
            $error[] = array(
                'err' => 'email_err',
                'text' => 'Inserisci Email'
            );
            
        }

        else if (strlen($email) > 50) {
            
            $error[] = array(
                'err' => 'email_err',
                'text' => 'Hai inserito una Email troppo lunga'
            );
            
        } 

        else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
  
            $error[] = array(
                'err' => 'email_err',
                'text' => 'Inserisci una Email valida'
            );

        }

        if ($email_conf !== NULL) {
            
            if ($email != $email_conf) {
            
                $error[] = array(
                    'err' => 'emailconf_err',
                    'text' => 'Le Email non sono uguali'
                );
                
            }
            
        }

        return $error;
        
    }

    // VALIDAZIONE PASSWORD

    function passwordVal($password, $password_conf = NULL, $password_old = NULL) {

        global $error;

        if (empty($password)) {
            
            $error[] = array(
                'err' => 'password_err',
                'text' => 'Inserisci Password'
            );
            
        }  

        else if (strlen($password) < 5) {
            
            $error[] = array(
                'err' => 'password_err',
                'text' => 'Password troppo corta minimo 5 caratteri'
            );
            
        } 
        
        else if (strlen($password) > 20) {
            
            $error[] = array(
                'err' => 'password_err',
                'text' => 'Password troppo lunga massimo 20 caratteri'
            );
            
        }
        
        else if (!ctype_alnum($password)) {
            
            $error[] = array(
                'err' => 'password_err',
                'text' => 'La Password può contenere solo caratteri alfanumerici'
            );
            
        } 

        if ($password_conf !== NULL) {
            
            if ($password_conf != $password) {
            
                $error[] = array(
                    'err' => 'passwordconf_err',
                    'text' => 'Le Password non sono uguali'
                );
                
            }
            
        }

        if ($password_old !== NULL) {
            
            if (empty($password_old)) {
                
                $error[] = array(
                    'err' => 'passwordold_err',
                    'text' => 'Inserisci Password'
                );
                
            }  

            else if (strlen($password_old) < 5) {
                
                $error[] = array(
                    'err' => 'passwordold_err',
                    'text' => 'Password troppo corta minimo 5 caratteri'
                );
                
            } 
            
            else if (strlen($password_old) > 20) {
                
                $error[] = array(
                    'err' => 'passwordold_err',
                    'text' => 'Password troppo lunga massimo 20 caratteri'
                );
                
            }
            
            else if (!ctype_alnum($password_old)) {
                
                $error[] = array(
                    'err' => 'passwordold_err',
                    'text' => 'La Password può contenere solo caratteri alfanumerici'
                );
                
            } 
            
        }

        return $error;

    }

    // CREAZIONE ARRAY DA POST

    function arrayOfPost($name, $array) {

        $array = array();

        $i = 1;

        while ($_POST["$name".'_'."$i"]) {

            $array[] = formatString($_POST["$name".'_'."$i"]);

            $i++;

        }

        return $array;

    }

    // VALIDAZIONE RICETTA

    function recipeVal($category, $title, $image, $person, $time, $difficult, $quantitys, $ingredients, $methods, $id) {

        global $link;

        global $error;

        // Validazione Categoria

        if ($category != 'biscotti' && 
            $category != 'ciambelle' && 
            $category != 'marmellate' &&
            $category != 'muffin' &&
            $category != 'torte') {

            $error[] = 'Seleziona Categoria';

        }

        // Validazione Titolo

        if ($id === NULL && countRows('recipes', 'title', 's', $title)) {

            $error[] = 'Esiste già una ricetta con questo titolo';

        } else {

            $query = "SELECT title FROM recipes WHERE id = ? LIMIT 1";

            $stmt = $link->prepare($query);

            $stmt->bind_param('s', $id);

            $stmt->execute();

            if (!$stmt->error) {
                
                $result = $stmt->get_result();

                $stmt->close();

                $result = $result->fetch_assoc();

                $old_title = $result['title'];

                if ($old_title != $title && countRows('recipes', 'title', 's', $title)) {

                    $error[] = 'Esiste già una ricetta con questo titolo';

                }

            }
        }

        if (empty($title)) {
        
            $error[] = 'Inserisci Titolo';
            
        }

        else if (strlen($title) < 3) {
        
            $error[] = 'Hai inserito un Titolo troppo corto, deve contenere almeno 3 caratteri alfabetici';
            
        } 
        
        else if (strlen($title) > 40) {
            
            $error[] = 'Hai inserito un Titolo troppo lungo, può contenere al massimo 40 caratteri';
            
        }
        
        else if (!ctype_alpha(str_replace(' ', '', $title))) {
            
            $error[] = 'Il Titolo può contenere solo caratteri alfabetici';
            
        } 

        // Validazione Immagine

        if (empty($image)) {
        
            $error[] = 'Inserisci Link immagine';
            
        }

        else if (strlen($image) > 2000) {
        
            $error[] = 'Hai inserito un Link troppo lungo';
            
        } 

        else if (filter_var($image, FILTER_VALIDATE_URL) === false) {

            $error[] = 'Inserisci un Link valido';

        }

        // Validazione Persone

        if (!ctype_digit($person)) {
            
            $error[] = 'Devi inserire un numero per le indicare Persone';
            
        }

        else if ($person < 1) {
        
            $error[] = 'Inserisci per quante Persone è la ricetta';
            
        }
        
        else if ($person > 99) {
            
            $error[] = 'Hai inserito un numero di Persone troppo grande';
            
        }
        

        // Validazione Tempo

        if (!ctype_digit($time)) {
            
            $error[] = 'Devi inserire un numero per indicare il Tempo';
            
        }

        else if ($time < 1) {
        
            $error[] = 'Inserisci il Tempo neccessario per preparare la ricetta';
            
        }
        
        else if ($time > 999) {
            
            $error[] = 'Hai inserito un Tempo troppo grande';
            
        }
        

        // Validazione Difficoltà

        if ($difficult != 'facile' && 
            $difficult != 'medio' && 
            $difficult != 'difficile') {

            $error[] = 'Seleziona Difficoltà';

        }

        // Validazione Quantità e Ingredienti

        $quantitys_length = count($quantitys);

        $ingredients_length = count($ingredients);

        if ($quantitys_length != $ingredients_length) {

            $error[] = 'Il numero di Quantità e di Ingredienti deve essere uguale';

        } else {

            if ($quantitys_length == 0) {

                $error[] = 'Devi inserire almeno un Ingrediente';

            } 
            
            else if ($quantitys_length > 50) {

                $error[] = 'Hai inserito troppi Ingredienti';

            } else {

                for ($q = 0; $q < $quantitys_length; $q++) {

                    $quantity = $q + 1;

                    if (strlen($quantitys[$q]) < 1) {
                
                        $error[] = "La Quantità dell'Ingrediente &nbsp <b>$quantity</b> &nbsp deve contenere almeno 1 carattere";
                    
                    }

                    else if (strlen($quantitys[$q]) > 20) {
                
                        $error[] = "La Quantità dell'Ingrediente &nbsp <b>$quantity</b> &nbsp può contenere al massimo 20 caratteri";
                    
                    }

                }

                for ($i = 0; $i < $ingredients_length; $i++) {

                    $ingredient = $i + 1;

                    if (strlen($ingredients[$i]) < 3) {
            
                        $error[] = "L'Ingrediente &nbsp <b>$ingredient</b> &nbsp deve contenere almeno 3 caratteri";
                    
                    }
    
                    else if (strlen($ingredients[$i]) > 40) {
                
                        $error[] = "L'Ingrediente &nbsp <b>$ingredient</b> &nbsp può contenere al massimo 40 caratteri";
                    
                    }

                }

            }

        }

        // Validazione Procedimento

        $methods_length = count($methods);

        if ($methods_length == 0) {

            $error[] = 'Inserisci almeno un Procedimento';

        } 
        
        else if ($methods_length > 50) {

            $error[] = 'Hai inserito troppi Procedimenti';

        } else {

            for ($m = 0; $m < $methods_length; $m++) {

                $method = $m + 1;

                if (strlen($methods[$m]) < 10) {
            
                    $error[] = "Il Procedimento &nbsp <b>$method</b> &nbsp deve contenere almeno 10 caratteri";
                
                }

                else if (strlen($methods[$m]) > 500) {
            
                    $error[] = "Il Procedimento &nbsp <b>$method</b> &nbsp può contenere al massimo 500 caratteri";
                
                }

            }

        }

    }

    // CREAZIONE ARRAY INGREDIENTE

    function ingredientPost($quantitys, $ingredients) {

        $array = array();

        $quantitys_length = count($quantitys);

        for ($i = 0; $i < $quantitys_length; $i++) {

            $array[] = '<li><strong>'.$quantitys[$i].'</strong>&nbsp';

            $array[] = '<span>'.$ingredients[$i].'</span></li>';

        }

        $ingredients = '<ul>'.implode('',$array).'</ul>';

        return $ingredients;

    }

    // CREAZIONE ARRAY PROCEDIMENTO

    function methodPost($methods) {

        $array = array();

        $methods_length = count($methods);

        for ($i = 0; $i < $methods_length; $i++) {

            $array[] = '<li><p>'.$methods[$i].'</p></li>';

        }

        $methods = '<ol>'.implode('',$array).'</ol>';

        return $methods;

    }

    // CREAZIONE ARRAY FILTRI

    function filterPost($vegetariano, $glutine, $lattosio) {

        $array = array();

        if ($vegetariano == 'on') {

            $array[] = 'vegetariano';

        }

        if ($glutine == 'on') {

            $array[] = 'glutine';

        }

        if ($lattosio == 'on') {

            $array[] = 'lattosio';

        }

        $filter = implode(',', $array);

        return $filter;

    }

?>