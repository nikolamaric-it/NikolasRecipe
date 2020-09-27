        <div class="alert_cookie">
            <p>Questo sito fa uso di cookie per migliorare l’esperienza di navigazione degli utenti e per raccogliere informazioni sull’utilizzo del sito stesso. &nbsp<a href="?page=privacy" target="_blank">Leggi di più</a></p>
            <button class="accept_cookie">Accetta e prosegui</button>
        </div>
        <footer class="footer">
            <a href="?page=privacy" target="_blank">Condizioni d’uso - Cookie Policy - Privacy Policy</a>
            <p>Copyright 2020 © Nikola’s Recipe – Tutti i diritti riservati.</p>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
            <script src="script/script.js"></script>
            <script type="text/javascript">
                $(document).ready( function() {
                    var availableTags = [
                        <?php 
                            $result = $link->query("SELECT title, image FROM recipes");
                            while ($row = $result->fetch_assoc()) {
                                echo '{';

                                echo "label:";
                                echo '"';
                                echo $row['title'];
                                echo '",';

                                echo "icon:";
                                echo '"';
                                echo $row['image'];
                                echo '"';

                                echo '},';
                            }   
                        ?>
                    ];
                    if($("#tags")[0] != undefined) {
                        $("#tags").autocomplete({
                            source: availableTags
                        }).autocomplete("instance")._renderItem = function(ul, item) {
                            return $("<li>").append("<img class='ui-image' src='"+item.icon+"' alt='"+item.label+"'><div class='ui-label'>"+item.label+"</div>").appendTo(ul);
                        };
                    };
                });
                </script>
        </footer>
    </body>
</html>