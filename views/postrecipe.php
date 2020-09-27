<!-- Form Post Ricetta -->
<section class="post">
    <div class="post_top">
        <div class="post_title">
            <h1 class=post_h1>NUOVA RICETTA</h1>
            <hr class="post_hr">
        </div>
    </div>
    <form class="post_body" action="action.php?action=postrecipe" method="post">
        <div class="flex_inline">
            <select class="input_category" name="category" required>
                <option value="">Seleziona Categoria</option>
                <option value="biscotti">Biscotti</option>
                <option value="ciambelle">Ciambelle</option>
                <option value="marmellate">Marmellate</option>
                <option value="muffin">Muffin</option>
                <option value="torte">Torte</option>
            </select>
        </div>
        <div class="flex_inline">
            <input class="input_title" name="title" type="text" placeholder="Titolo" minlength="3" maxlength="40" required>
            <input class="input_image" name="image" type="url" placeholder="Link Immagine" minlength="10" maxlength="2000" required>
        </div>
        <div class="flex_inline">
            <input class="input_person" name="person" type="text" placeholder="Persone" pattern="[0-9]{1,2}" minlength="1" maxlength="2" required>
            <input class="input_time" name="time" type="text" placeholder="Tempo" pattern="[0-9]{1,3}" minlength="1" maxlength="3" required>
            <select class="input_difficult" name="difficult" required>
                <option value="">Difficoltà</option>
                <option value="facile">Facile</option>
                <option value="medio">Medio</option>
                <option value="difficile">Difficile</option>
            </select>
        </div>
        <hr class="post_separator">
        <div class="ingredients" >
            <div class='ingredients_inline' id='ing_1'>
                <input class='input_q' type='text' placeholder='Quantità' name='q_1' minlength="1" maxlength="20" required>
                <input class='input_i' type='text' placeholder='Ingrediente' name='i_1' minlength="3" maxlength="40" required>
            </div>
            <p class='ingredients_add'>+</p>
        </div>
        <hr class="post_separator">
        <div class="method" >
            <div class='method_inline' id='met_1'>
                <textarea class='input_m' placeholder='Procedimento' name='m_1' minlength='10' maxlength='500' required></textarea>
            </div>
            <p class='method_add'>+</p>
        </div>
        <hr class="post_separator">
        <div class="post_filter">
            <div class="post_label">
                <label class="post_checkbox_label">
                    <input type="checkbox" name="vegetariano" id="vegetariano">
                    <span class="post_checkbox_custom"></span>
                </label>
                <label class="post_checkbox_p" for="vegetariano">Vegetariano</label>
            </div>
            <div class="post_label">
                <label class="post_checkbox_label">
                    <input type="checkbox" name="glutine" id="glutine">
                    <span class="post_checkbox_custom"></span>
                </label>
                <label class="post_checkbox_p" for="glutine">Senza Glutine</label>
            </div>
            <div class="post_label">
                <label class="post_checkbox_label">
                    <input type="checkbox" name="lattosio" id="lattosio">
                    <span class="post_checkbox_custom"></span>
                </label>
                <label class="post_checkbox_p" for="lattosio">Senza Lattosio</label>
            </div>
        </div>
        <hr class="post_separator">
        <input class="post_submit" name="submit" type="submit" value="Pubblica">
    </form>
</section>