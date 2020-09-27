<!-- Titolo -->
<h1 id="link_mail" class="form_title">Cambia Email</h1>

<!-- Form Cambio Email -->
<section class="form_mailchange"> 
    <form>
        <div class="email_err"></div>
        <input class="form_input_email" name="email" type="email" placeholder="Nuova Email" minlength="5" maxlength="50" autofocus required>
        <div class="emailconf_err"></div>
        <input class="form_input_email" name="emailconf" type="email" placeholder="Conferma Email" minlength="5" maxlength="50" required>
        <div class="password_err"></div>
        <div class="form_eye">
            <div class="form_eye_icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" height="21" viewBox="0 0 36 21" fill="none">
                    <path d="M35.8661 10.074C35.5826 9.66149 28.7696 0 17.9996 0C8.7581 0 0.523107 9.60601 0.176607 10.0155C-0.0588691 10.2945 -0.0588691 10.704 0.176607 10.9845C0.523107 11.394 8.7581 21 17.9996 21C27.2411 21 35.476 11.394 35.8225 10.9845C36.0401 10.7265 36.0596 10.353 35.8661 10.074ZM17.9996 19.5C10.5911 19.5 3.5471 12.435 1.75765 10.5C3.54415 8.56356 10.5807 1.50005 17.9996 1.50005C26.6681 1.50005 32.7866 8.55456 34.2806 10.4595C32.5555 12.333 25.4711 19.5 17.9996 19.5Z" fill="#666"/>
                    <path d="M18 4.5C14.691 4.5 12 7.191 12 10.5C12 13.809 14.691 16.5 18 16.5C21.309 16.5 24 13.809 24 10.5C24 7.191 21.309 4.5 18 4.5ZM18 15.0001C15.519 15.0001 13.5 12.981 13.5 10.5001C13.5 8.01907 15.519 6.00005 18 6.00005C20.481 6.00005 22.5 8.01907 22.5 10.5001C22.5 12.981 20.481 15.0001 18 15.0001Z" fill="#666"/>
                </svg>
            </div>
            <input class="form_input_password" name="password" type="password" placeholder="Password" pattern="[a-zA-z0-9]{5,20}" minlength="5" maxlength="20" required>
        </div>
        <div id="emailchange" class="form_input_submit">Conferma</div>
    </form>
</section>