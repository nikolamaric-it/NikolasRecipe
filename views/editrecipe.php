<!-- Form Modifica Ricetta -->
<?php

    $recipe_id = $_GET['id'];
        
    $query = "SELECT * FROM recipes WHERE id = ? LIMIT 1";

    $stmt = $link->prepare($query);

    $stmt->bind_param('i', $recipe_id);

    $stmt->execute();

    if (!$stmt->error) {

        $result = $stmt->get_result();

        $stmt->close();

        $row = $result->fetch_assoc();

        if (empty($row)) {

            echo '<div class="alerterror">';
            echo 'Ricetta non trovata'; 
            echo '</div>';
            echo '<a id="return">Torna Indietro</a>';

        } else {

?>

<section class="post">
    <div class="post_top">
        <div class="post_title">
            <h1 class=post_h1>MODIFICA RICETTA</h1>
            <hr class="post_hr">
        </div>
    </div>
    <form id="editrecipe_form" class="post_body" action="action.php?action=editrecipe" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <div class="flex_inline">
            <select class="input_category" name="category" required>
                <option value="">Seleziona Categoria</option>
                <option value="biscotti" <?php echo ($row['category'] == 'biscotti') ? 'selected' : ''; ?> >Biscotti</option>
                <option value="ciambelle" <?php echo ($row['category'] == 'ciambelle') ? 'selected' : ''; ?> >Ciambelle</option>
                <option value="marmellate" <?php echo ($row['category'] == 'marmellate') ? 'selected' : ''; ?> >Marmellate</option>
                <option value="muffin" <?php echo ($row['category'] == 'muffin') ? 'selected' : ''; ?> >Muffin</option>
                <option value="torte" <?php echo ($row['category'] == 'torte') ? 'selected' : ''; ?> >Torte</option>
            </select>
        </div>
        <div class="flex_inline">
            <input class="input_title" name="title" type="text" placeholder="Titolo" minlength="3" maxlength="40" required value="<?php echo $row['title']; ?>">
            <input class="input_image" name="image" type="url" placeholder="Link Immagine" minlength="10" maxlength="2000" required value="<?php echo $row['image']; ?>">
        </div>
        <div class="flex_inline">
            <input class="input_person" name="person" type="text" placeholder="Persone" pattern="[0-9]{1,2}" minlength="1" maxlength="2" required value="<?php echo $row['person']; ?>" >
            <input class="input_time" name="time" type="text" placeholder="Tempo" pattern="[0-9]{1,3}" minlength="1" maxlength="3" required value="<?php echo $row['time']; ?>">
            <select class="input_difficult" name="difficult" required>
                <option value="">Difficoltà</option>
                <option value="facile" <?php echo ($row['difficult'] == 'facile') ? 'selected' : ''; ?> >Facile</option>
                <option value="medio" <?php echo ($row['difficult'] == 'medio') ? 'selected' : ''; ?> >Medio</option>
                <option value="difficile" <?php echo ($row['difficult'] == 'difficile') ? 'selected' : ''; ?> >Difficile</option>
            </select>
        </div>
        <hr class="post_separator">
        <div class="ingredients" >
            <?php
                $ingredients  = $row['ingredients'];  
                $slice_ul = str_replace(['<ul>', '</ul>'], ['', ''], $ingredients);
                $slice_outer_li = substr($slice_ul, 4, -5);
                $ingredients_array = explode('</li><li>', $slice_outer_li);
                $ingredients_array_length = count($ingredients_array);
                for ($i = 0; $i < $ingredients_array_length; $i++) {
                    $single_ingredient = explode('&nbsp', strip_tags($ingredients_array[$i]));
                    $quantity = $single_ingredient[0];
                    $ingredient = $single_ingredient[1];
                    $div_num = $i + 1;
                    echo "<div class='ingredients_inline' id='ing_$div_num'>";
                    echo "<input class='input_q' type='text' placeholder='Quantità' name='q_$div_num' minlength='1' maxlength='20' required value='$quantity'>";
                    echo "<input class='input_i' type='text' placeholder='Ingrediente' name='i_$div_num' minlength='3' maxlength='40' required value='$ingredient'>";
                    if ($div_num != 1) {
                        echo "<button id='removei_$div_num' class='ingredients_remove'>X</button>";
                        echo "</div>";
                    } else {
                        echo "</div>";
                    }
                }         
            ?>
            <p class='ingredients_add'>+</p>
        </div>
        <hr class="post_separator">
        <div class="method">
            <?php
                $methods  = $row['method'];  
                $slice_ol = str_replace(['<ol>', '</ol>'], ['', ''], $methods);
                $slice_outer_li = substr($slice_ol, 4, -5);
                $methods_array = explode('</li><li>', $slice_outer_li);
                $methods_array_length = count($methods_array);
                for ($i = 0; $i < $methods_array_length; $i++) {
                    $single_method = strip_tags($methods_array[$i]);
                    $div_num = $i + 1;
                    echo "<div class='method_inline' id='met_$div_num'>";
                    echo "<textarea class='input_m' placeholder='Procedimento' name='m_$div_num' minlength='10' maxlength='500' required>$single_method</textarea>";
                    if ($div_num != 1) {
                        echo "<button id='removem_$div_num' class='method_remove'>X</button>";
                        echo "</div>";
                    } else {
                        echo "</div>";
                    }
                }         
            ?>      
            <p class='method_add'>+</p>
        </div>
        <hr class="post_separator">
        <div class="post_filter">
            <div class="post_label">
                <label class="post_checkbox_label">
                    <input type="checkbox" name="vegetariano" id="vegetariano" <?php echo (strpos($row['filter'], 'vegetariano') !== false) ? 'checked' : ''; ?> >
                    <span class="post_checkbox_custom"></span>
                </label>
                <label class="post_checkbox_p" for="vegetariano">Vegetariano</label>
            </div>
            <div class="post_label">
                <label class="post_checkbox_label">
                    <input type="checkbox" name="glutine" id="glutine" <?php echo (strpos($row['filter'], 'glutine') !== false) ? 'checked' : ''; ?> >
                    <span class="post_checkbox_custom"></span>
                </label>
                <label class="post_checkbox_p" for="glutine">Senza Glutine</label>
            </div>
            <div class="post_label">
                <label class="post_checkbox_label">
                    <input type="checkbox" name="lattosio" id="lattosio" <?php echo (strpos($row['filter'], 'lattosio') !== false) ? 'checked' : ''; ?> >
                    <span class="post_checkbox_custom"></span>
                </label>
                <label class="post_checkbox_p" for="lattosio">Senza Lattosio</label>
            </div>
        </div>
        <hr class="post_separator">
        <input class="edit_submit" name="submit" type="submit" value="Aggiorna">
    </form>
    <form action="action.php?action=deleterecipe" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input id="editrecipe_submit" class="delete_submit" name="submit" type="submit" value="Elimina" onclick="return confirm('Sei sicuro di voler eliminare la ricetta?')">
    </form>
</section>
<?php }} ?>