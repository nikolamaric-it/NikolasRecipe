<?php
    
    include('function.php');

    // LOGIN

    if ($_GET['action'] == 'login') {

        if (empty($_SESSION['id'])) {
        
            $error = array();

            $email = trim($_POST['email']);

            $password = trim($_POST['password']);

            // Validazione Email
            
            emailVal($email, NULL);

            // Validazione Password
            
            passwordVal($password, NULL, NULL);

            // Resoconto Errori

            $countError = count($error);

            if ($countError == 0) { 
                
                // Controllo presenza Utente

                $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
                
                $stmt = $link->prepare($query);

                $stmt->bind_param('s', $email);

                $stmt->execute();           

                if (!$stmt->error) {

                    // Login
                
                    $result = $stmt->get_result();

                    $stmt->close();

                    $row = $result->fetch_assoc();
                        
                    if (password_verify($password, $row['password'])) {

                        if ($_POST['remember'] == 1) {

                            setcookie('id', $row['id'], time()+60*60*24*365);
                
                        } else {

                            $_SESSION['id'] = (int)$row['id'];

                        }
                            
                    } else { 
                        
                        // Utente non trovato
                        
                        $error[] = array(
                            'err' => 'email_err',
                            'text' => 'Hai sbagliato Email o Password'
                        );
                            
                    }

                }

            }

        }

        echo json_encode($error);

    }       

    // REGISTRAZIONE

    if ($_GET['action'] == 'register') {

        if (empty($_SESSION['id'])) {

            $error = array();

            $email = trim($_POST['email']);

            $password = trim($_POST['password']);

            $password_conf = trim($_POST['passwordconf']);

            $terms = $_POST['terms'];

            // Validazione Email
            
            emailVal($email, NULL);

            // Validazione Password
            
            passwordVal($password, $password_conf, NULL);

            // Validazione Termini

            if ($terms != 1) {

                $error[] = array(
                    'err' => 'terms_err',
                    'text' => 'Devi accettare tutti i termini e le condizioni'
                );

            } 
            
            // Resoconto Errori

            $countError = count($error);

            if ($countError == 0) { 
                
                // Controllo presenza Email nel Database
                
                if (countRows('users', 'email', 's', $email)) {

                    $error[] = array(
                        'err' => 'email_err',
                        'text' => 'Esiste già un utente con questa Email'
                    );

                } else { 
                    
                    // Inserimento Utente e codifica Password

                    $passwordhash = password_hash($password, PASSWORD_DEFAULT);
                    
                    $query = "INSERT INTO users (email, password) VALUES (?, ?)";
                    
                    $stmt = $link->prepare($query);

                    $stmt->bind_param('ss', $email, $passwordhash);

                    $stmt->execute();
                    
                    if (!$stmt->error) {

                        $_SESSION['id'] = $stmt->insert_id;

                        $stmt->close();

                    }
                    
                }

            }

        }

        echo json_encode($error);
        
    }

    // CHANGE EMAIL

    if ($_GET['action'] == 'emailchange') {
            
        if (!empty($_SESSION['id'])) {

            $error = array();

            $email = trim($_POST['email']);

            $email_conf = trim($_POST['emailconf']);

            $password = trim($_POST['password']);

            // Validazione Email
        
            emailVal($email, $email_conf);

            // Validazione Password
        
            passwordVal($password, NULL, NULL);

            // Resoconto Errori

            $countError = count($error);

            if ($countError == 0) { 
                
                // Controllo presenza Email nel Database
                
                if (countRows('users', 'email', 's', $email)) {

                    $error[] = array(
                        'err' => 'email_err',
                        'text' => 'Esiste già un utente con questa Email'
                    );
    
                } else { 
                    
                    // Validazione Conferma Password

                    $query = "SELECT password FROM users WHERE id = ? LIMIT 1";
            
                    $stmt = $link->prepare($query);

                    $stmt->bind_param('i', $_SESSION['id']);

                    $stmt->execute(); 

                    if (!$stmt->error) {
            
                        $result = $stmt->get_result();

                        $stmt->close();

                        $row = $result->fetch_assoc();
                    
                        if (!password_verify($password, $row['password'])) {

                            $error[] = array(
                                'err' => 'password_err',
                                'text' => 'Hai sbagliato Password'
                            );

                        } else { 
                            
                            // Aggiornamento Email

                            $query = "UPDATE users SET email = ? WHERE id = ? LIMIT 1";
                        
                            $stmt = $link->prepare($query);

                            $stmt->bind_param('si', $email, $_SESSION['id']);

                            $stmt->execute();

                            if (!$stmt->error) {

                                $stmt->close();

                            }

                        }

                    }

                }

            }

        }

        echo json_encode($error);

    }    

    // CHANGE PASSWORD

    if ($_GET['action'] == 'passwordchange') {
        
        if (!empty($_SESSION['id'])) {

            $error = array();

            $password = trim($_POST['password']);

            $password_conf = trim($_POST['passwordconf']);

            $password_old = trim($_POST['passwordold']);

            // Validazione Password
        
            passwordVal($password, $password_conf, $password_old);

            // Resoconto Errori

            $countError = count($error);

            if ($countError == 0) { 
                
                // Validazione Conferma Password

                $query = "SELECT password FROM users WHERE id = ? LIMIT 1";
            
                $stmt = $link->prepare($query);

                $stmt->bind_param('i', $_SESSION['id']);

                $stmt->execute(); 

                if (!$stmt->error) {
            
                    $result = $stmt->get_result();

                    $stmt->close();

                    $row = $result->fetch_assoc();
                    
                    if (!password_verify($password_old, $row['password'])) {

                        $error[] = array(
                            'err' => 'passwordold_err',
                            'text' => 'Hai sbagliato Password'
                        );

                    } else { 
                        
                        // Aggiornamento Password

                        $passwordhash = password_hash($password, PASSWORD_DEFAULT);

                        $query = "UPDATE users SET password = ? WHERE id = ? LIMIT 1";
                        
                        $stmt = $link->prepare($query);

                        $stmt->bind_param('si', $passwordhash, $_SESSION['id']);

                        $stmt->execute();

                        if (!$stmt->error) {

                            $stmt->close();

                        }

                    } 
                    
                }

            }

        }

        echo json_encode($error);
  
    }    

    // POST RECIPE

    if ($_GET['action'] == 'postrecipe') {

        if (isAdmin()) {

            $error = array();

            $category = formatString($_POST['category']);

            $title = formatString($_POST['title']);

            $image = formatString($_POST['image']);

            $person = formatString($_POST['person']);

            $time = formatString($_POST['time']);

            $difficult = formatString($_POST['difficult']);

            $quantitys = arrayOfPost('q', $quantitys);

            $ingredients = arrayOfPost('i', $ingredients);

            $methods = arrayOfPost('m', $methods);

            $filters = filterPost($_POST['vegetariano'], $_POST['glutine'], $_POST['lattosio']);

            // Validazione Ricetta

            recipeVal($category, $title, $image, $person, $time, $difficult, $quantitys, $ingredients, $methods, NULL);

            // Resoconto Errori

            $countError = count($error);

            if ($countError != 0) { 
                
                alertStyle();
                for ($i = 0; $i < $countError; $i++) {

                    echo '<div class="alerterror">';
                    echo $error[$i];
                    echo '</div>';
        
                }
                echo '<a id="return">Torna Indietro</a>';
                historyReturn();
            
            } else { 

                $ingredients = ingredientPost($quantitys, $ingredients);

                $methods = methodPost($methods);
                
                // Post

                $query = "INSERT INTO recipes (category, title, image, person, time, difficult, ingredients, method, filter) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                
                $stmt = $link->prepare($query);

                $stmt->bind_param('sssiissss', $category, $title, $image, $person, $time, $difficult, $ingredients, $methods, $filters);

                $stmt->execute();

                if (!$stmt->error) {

                    $stmt->close();
                    alertStyle();
                    echo '<div class="alertsuccess">Ricetta postata con successo</div>';
                    echo '<a id="return_home" href="index.php">Torna alla Home</a>';

                }

            }

        }

    }

    // EDIT RECIPE

    if ($_GET['action'] == 'editrecipe') {

        $id = $_POST['id'];

        if (isAdmin() && countRows('recipes', 'id', 'i', $id)) {

            $error = array();

            $category = formatString($_POST['category']);

            $title = formatString($_POST['title']);

            $image = formatString($_POST['image']);

            $person = formatString($_POST['person']);

            $time = formatString($_POST['time']);

            $difficult = formatString($_POST['difficult']);

            $quantitys = arrayOfPost('q', $quantitys);

            $ingredients = arrayOfPost('i', $ingredients);

            $methods = arrayOfPost('m', $methods);

            $filters = filterPost($_POST['vegetariano'], $_POST['glutine'], $_POST['lattosio']);

            echo $filter;
            
            // Validazione Ricetta

            recipeVal($category, $title, $image, $person, $time, $difficult, $quantitys, $ingredients, $methods, $id);

            // Resoconto Errori

            $countError = count($error);

            if ($countError != 0) {
                
                alertStyle();
                for ($i = 0; $i < $countError; $i++) {

                    echo '<div class="alerterror">';
                    echo $error[$i];
                    echo '</div>';
        
                }
                echo '<a id="return">Torna Indietro</a>';
                historyReturn();
            
            } else { 

                $ingredients = ingredientPost($quantitys, $ingredients);

                $methods = methodPost($methods);
                
                // Edit

                $query = "UPDATE recipes SET category = ?, title = ?, image = ?, person = ?, time = ?, difficult = ?, ingredients = ?, method = ?, filter = ? WHERE id = ? LIMIT 1";
                                
                $stmt = $link->prepare($query);

                $stmt->bind_param('sssiissssi', $category, $title, $image, $person, $time, $difficult, $ingredients, $methods, $filters, $id);

                $stmt->execute();

                if (!$stmt->error) {

                    $stmt->close();
                    alertStyle();
                    echo '<div class="alertsuccess">Ricetta modificata con successo</div>';
                    echo '<a id="return_home" href="index.php">Torna alla Home</a>';

                }

            }

        }

    }

    // DELETE RECIPE

    if ($_GET['action'] == 'deleterecipe') {

        $id = $_POST['id'];

        if (isAdmin() && countRows('recipes', 'id', 'i', $id)) {

            $query = "DELETE FROM recipes WHERE id = ? LIMIT 1";
            
            $stmt = $link->prepare($query);

            $stmt->bind_param('i', $id);

            $stmt->execute();

            if (!$stmt->error) {

                $stmt->close();

                alertStyle();

                echo '<div class="alertsuccess">Ricetta eliminata con successo</div>';

                echo '<a id="return_home" href="index.php">Torna alla Home</a>';

            } else {

                alertStyle();

                echo '<div class="alerterror">Ricetta non eliminata</div>';

                echo '<a id="return">Torna Indietro</a>';

                historyReturn();

            }

        }

    }   

    // FOLLOW

    if ($_GET['action'] == 'recipefollow') {

        $user_id = $_SESSION['id'];
        
        $recipe_id = $_POST['recipeid'];

        // Controllo presenza ricetta

        if (countRows('recipes', 'id', 'i', $recipe_id)) {

            // Controllo se la ricetta è già tra i preferiti

            if (countRowsDouble('follows', 'user_id', 'recipe_id', 'ii', $user_id, $recipe_id)) { 

                $query = "DELETE FROM follows WHERE recipe_id = ? LIMIT 1";

                $stmt = $link->prepare($query);

                $stmt->bind_param('i', $recipe_id);
        
                $stmt->execute();

                if (!$stmt->error) {

                    $stmt -> close();

                    echo '0';

                }

            } else {

                $query = "INSERT INTO follows (user_id, recipe_id) VALUES ( ?, ? )";

                $stmt = $link->prepare($query);

                $stmt->bind_param('ii', $user_id, $recipe_id);
        
                $stmt->execute();

                if (!$stmt->error) {

                    $stmt -> close();

                    echo '1';

                }

            }

        }

    }

    // VOTE

    if ($_GET['action'] == 'recipevote') {

        $user_id = $_SESSION['id'];
        
        $recipe_id = $_POST['recipeid'];

        $vote = $_POST['vote'];

        // Validazione voto e controllo esistenza ricetta

        if (($vote == 1 || $vote == 2 || $vote == 3 || $vote == 4 || $vote == 5) && 
            (countRows('recipes', 'id', 'i', $recipe_id))) {

            if (countRowsDouble('votes', 'user_id', 'recipe_id', 'ii', $user_id, $recipe_id)) { 
                
                // Aggiorno votes con il nuovo voto

                $query = "UPDATE votes SET vote = ? WHERE user_id = ? AND recipe_id = ? LIMIT 1";

                $stmt = $link->prepare($query);

                $stmt->bind_param('iii', $vote, $user_id, $recipe_id);

                $stmt->execute();

                if (!$stmt->error){

                    $stmt->close();

                    echo 'Voto aggiornato';

                }

            } else { 
                
                // Aggiungo il voto a votes se l'utente non ha votato

                $query = "INSERT INTO votes (user_id, recipe_id, vote) VALUES (? , ? , ?)";

                $stmt = $link->prepare($query);

                $stmt->bind_param('iii', $user_id, $recipe_id, $vote);

                $stmt->execute();

                if (!$stmt->error){

                    $stmt->close();

                    echo 'Grazie di aver votato';

                }

            }

        }

    }

    // LOGOUT

    if ($_GET['action'] == 'logout')  {

        setcookie('id', ' ', time() - 3600);
    
        session_unset();
    
        header('location: index.php');
    
    } 
    
?>