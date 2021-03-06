<?php 

if ($_POST['title']) {

    $title = $_POST['title'];

    $query = "SELECT id FROM recipes WHERE title = ? LIMIT 1";

    $stmt = $link->prepare($query);

    $stmt->bind_param('s', $title);

    $stmt->execute();

    if (!$stmt->error) {

        $result = $stmt->get_result();
        
        $stmt->close();

        $row = $result->fetch_assoc();

        header('location: ./?page=recipe&id='.$row['id']);

    }
      
}

?>
<section class="home_search">
    <form class="ui" action="" method="POST">
        <input class="ui-input" name="title" type="text" id="tags" placeholder="Cerca ricetta..."><button class="ui-search">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21" height="21" viewBox="0 0 21 21" fill="none">
                <path d="M20.7429 19.5086L15.6943 14.46C16.9457 12.9343 17.7 10.98 17.7 8.85C17.7 3.96429 13.7357 0 8.85 0C3.96 0 0 3.96429 0 8.85C0 13.7357 3.96 17.7 8.85 17.7C10.98 17.7 12.93 16.95 14.4557 15.6986L19.5043 20.7429C19.8471 21.0857 20.4 21.0857 20.7429 20.7429C21.0857 20.4043 21.0857 19.8471 20.7429 19.5086ZM8.85 15.9386C4.93714 15.9386 1.75714 12.7586 1.75714 8.85C1.75714 4.94143 4.93714 1.75714 8.85 1.75714C12.7586 1.75714 15.9429 4.94143 15.9429 8.85C15.9429 12.7586 12.7586 15.9386 8.85 15.9386Z" fill="#666666"/>
            </svg>
        </button>
    </form>
</section>
<section class="home">
    <div class="home_top">
        <h2 class="home_h2">ULTIME&nbsp USCITE</h2>
        <hr class="home_hr">
    </div>
    <article class="home_body">
        <div class="category_body_card">
            <?php 

                $result = $link->query("SELECT id, image, title, difficult FROM recipes ORDER BY id DESC LIMIT 12");

                while ($row = $result->fetch_assoc()) {

                    $recipe_id = $row['id'];

                    $vote_result = $link->query("SELECT SUM(vote) AS vote FROM votes WHERE recipe_id = $recipe_id LIMIT 1");

                    $vote = $vote_result->fetch_assoc();

                    $voters_result = $link->query("SELECT COUNT(id) as voters FROM votes WHERE recipe_id = $recipe_id LIMIT 1");

                    $voters = $voters_result->fetch_assoc();

                    echo "<a class='category_body_recipe'".'href=?page=recipe&id='.$row['id'].">";
                    echo '<div class="category_body_recipe_hover"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="124" height="84" viewBox="0 0 124 84" fill="none"><path d="M61.6 25.2C52.332 25.2 44.8 32.7319 44.8 42C44.8 51.2681 52.332 58.8 61.6 58.8C70.8681 58.8 78.4 51.2681 78.4 42C78.4 32.7319 70.8679 25.2 61.6 25.2Z" fill="white"/><path d="M61.6001 0C33.6 0 9.68809 17.4158 0 42C9.68809 66.5839 33.6 84 61.6001 84C89.628 84 113.512 66.5839 123.2 42C113.512 17.4158 89.628 0 61.6001 0ZM61.6001 69.9998C46.1441 69.9998 33.6 57.4557 33.6 41.9997C33.6 26.5437 46.1441 13.9999 61.6001 13.9999C77.0561 13.9999 89.6002 26.544 89.6002 42C89.6002 57.456 77.0561 69.9998 61.6001 69.9998Z" fill="white"/></svg></div>';
                    echo "<img class='category_body_recipe_image' src=".$row['image']." alt=".$row['title'].">";
                    echo "<p class='category_body_recipe_title'>".$row['title']."</p>";
                    echo "<div class='category_body_recipe_icons'>";
                    echo "<div class='category_body_recipe_vote_div'>";
                    echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22" height="21" viewBox="0 0 22 21" fill="none"><path d="M21.443 9.51719C21.8744 9.09672 22.0267 8.47962 21.8406 7.90597C21.6541 7.33233 21.1682 6.92283 20.5713 6.83593L15.2641 6.06477C15.0381 6.03185 14.8428 5.89009 14.7418 5.68512L12.3691 0.876052C12.1027 0.335761 11.562 0 10.9593 0C10.3572 0 9.81643 0.335761 9.55002 0.876052L7.17686 5.68512C7.07592 5.89009 6.88017 6.03185 6.65413 6.06477L1.3469 6.83636C0.750435 6.92283 0.264568 7.33233 0.0780338 7.90597C-0.108061 8.47962 0.0442382 9.09672 0.475681 9.51719L4.31565 13.2602C4.47936 13.4199 4.55441 13.6499 4.51579 13.8746L3.60945 19.1603C3.50763 19.7542 3.74683 20.3428 4.23445 20.6974C4.72164 21.0525 5.35585 21.0985 5.89 20.8172L10.6363 18.3216C10.8386 18.2154 11.08 18.2154 11.2824 18.3216L16.0291 20.8172C16.2609 20.9392 16.5119 20.9993 16.7616 20.9993C17.086 20.9993 17.409 20.898 17.6847 20.6974C18.1723 20.3428 18.4115 19.7542 18.3097 19.1603L17.4029 13.8751C17.3643 13.6499 17.4393 13.4204 17.603 13.2606L21.443 9.51719ZM16.5382 14.0234L17.4446 19.3087C17.4902 19.5747 17.3871 19.8288 17.1685 19.9877C16.9495 20.1461 16.6769 20.1654 16.4377 20.0408L11.691 17.5447C11.4623 17.4249 11.2104 17.3643 10.9593 17.3643C10.7083 17.3643 10.4568 17.4249 10.2277 17.5452L5.48182 20.0408C5.24174 20.1654 4.96918 20.1461 4.7506 19.9877C4.53203 19.8288 4.42933 19.5751 4.47453 19.3087L5.38087 14.0234C5.46821 13.5134 5.29923 12.9929 4.9288 12.6321L1.08839 8.88868C0.894834 8.69995 0.828998 8.43397 0.912829 8.17722C0.996221 7.92002 1.20558 7.74358 1.47287 7.70452L6.77966 6.93336C7.29186 6.85919 7.73471 6.53791 7.96338 6.07355L10.3365 1.26448C10.4559 1.02221 10.689 0.877807 10.9589 0.877807C11.2293 0.877807 11.4619 1.02221 11.5817 1.26448L13.9549 6.07355C14.1835 6.53791 14.6259 6.85919 15.1381 6.93336L20.4454 7.70452C20.7127 7.74358 20.922 7.92002 21.0054 8.17722C21.0888 8.43397 21.0234 8.69995 20.8298 8.88868L16.9899 12.6316C16.6194 12.9929 16.4505 13.513 16.5382 14.0234Z" fill="#666666"/></svg>';
                    if ($voters['voters'] == 0) {
                        echo "<span class='category_body_recipe_vote'>Nessuno</span>";
                    } else {
                        echo "<span class='category_body_recipe_vote'>".number_format($vote['vote']/$voters['voters'], 1)."</span>";
                    }
                    echo "</div>";
                    echo "<div class='category_body_recipe_difficult_div'>";
                    echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <g clip-path="url(#clip0)">
                        <path d="M19.1879 17.7126L17.6846 15.2084C16.9916 14.0525 16.625 12.7295 16.625 11.3811V9.18749C16.625 5.81088 13.8766 3.0625 10.5 3.0625C7.12341 3.0625 4.37503 5.81088 4.37503 9.18749V11.3811C4.37503 12.7295 4.00839 14.0525 3.31539 15.2084L1.81213 17.7126C1.73075 17.8474 1.72899 18.0163 1.80602 18.1528C1.8839 18.2901 2.03 18.375 2.1875 18.375H18.8125C18.97 18.375 19.1161 18.2902 19.194 18.1528C19.271 18.0162 19.2693 17.8474 19.1879 17.7126ZM2.96015 17.5L4.06528 15.6581C4.84052 14.3666 5.25002 12.8879 5.25002 11.3811V9.18749C5.25002 6.29211 7.60464 3.93749 10.5 3.93749C13.3954 3.93749 15.75 6.29211 15.75 9.18749V11.3811C15.75 12.8879 16.1595 14.3666 16.9339 15.6581L18.0399 17.5H2.96015Z" fill="#666666"/>
                        <path d="M10.5 0C9.53488 0 8.75 0.784875 8.75 1.75001V3.50003C8.75 3.74149 8.94601 3.9375 9.18751 3.9375C9.42901 3.9375 9.62503 3.74149 9.62503 3.49999V1.75001C9.62503 1.26701 10.017 0.875027 10.5 0.875027C10.983 0.875027 11.375 1.26701 11.375 1.75001V3.50003C11.375 3.74149 11.571 3.9375 11.8125 3.9375C12.054 3.9375 12.25 3.74149 12.25 3.49999V1.75001C12.25 0.784875 11.4652 0 10.5 0Z" fill="#666666"/>
                        <path d="M12.3935 17.7161C12.2701 17.5079 12.0033 17.4397 11.7942 17.5595C11.585 17.682 11.515 17.9506 11.6375 18.1589C11.7513 18.3523 11.8134 18.5841 11.8134 18.8125C11.8134 19.5361 11.2245 20.125 10.5009 20.125C9.77725 20.125 9.18839 19.5361 9.18839 18.8125C9.18839 18.5841 9.25053 18.3523 9.36426 18.1589C9.48588 17.9497 9.4159 17.682 9.20763 17.5595C8.99676 17.4396 8.73074 17.5079 8.60826 17.7161C8.41487 18.0469 8.3125 18.4258 8.3125 18.8125C8.31254 20.0191 9.29339 21 10.5 21C11.7067 21 12.6875 20.0191 12.6893 18.8125C12.6893 18.4258 12.5869 18.0469 12.3935 17.7161Z" fill="#666666"/>
                        </g>
                        <defs>
                        <clipPath id="clip0">
                        <rect width="21" height="21" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>';
                    echo "<span class='category_body_recipe_difficult'>".$row['difficult']."</span>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";
                
                }

            ?>
        </div>
    </article>
    <div class="home_top">
        <h2 class="home_h2">RICETTE&nbsp FAMOSE</h2>
        <hr class="home_hr">
    </div>
    <article class="home_body">
        <div class="category_body_card">
            <?php 

                $result = $link->query("SELECT recipes.id, recipes.image, recipes.title, recipes.difficult, votes.recipe_id, COUNT(votes.recipe_id) AS result FROM recipes INNER JOIN votes WHERE recipes.id = votes.recipe_id GROUP BY id ORDER BY result DESC LIMIT 12");

                while ($row = $result->fetch_assoc()) {

                    $recipe_id = $row['id'];

                    $vote_result = $link->query("SELECT SUM(vote) AS vote FROM votes WHERE recipe_id = $recipe_id LIMIT 1");

                    $vote = $vote_result->fetch_assoc();

                    $voters_result = $link->query("SELECT COUNT(id) as voters FROM votes WHERE recipe_id = $recipe_id LIMIT 1");

                    $voters = $voters_result->fetch_assoc();

                    echo "<a class='category_body_recipe'".'href=?page=recipe&id='.$row['id'].">";
                    echo '<div class="category_body_recipe_hover"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="124" height="84" viewBox="0 0 124 84" fill="none"><path d="M61.6 25.2C52.332 25.2 44.8 32.7319 44.8 42C44.8 51.2681 52.332 58.8 61.6 58.8C70.8681 58.8 78.4 51.2681 78.4 42C78.4 32.7319 70.8679 25.2 61.6 25.2Z" fill="white"/><path d="M61.6001 0C33.6 0 9.68809 17.4158 0 42C9.68809 66.5839 33.6 84 61.6001 84C89.628 84 113.512 66.5839 123.2 42C113.512 17.4158 89.628 0 61.6001 0ZM61.6001 69.9998C46.1441 69.9998 33.6 57.4557 33.6 41.9997C33.6 26.5437 46.1441 13.9999 61.6001 13.9999C77.0561 13.9999 89.6002 26.544 89.6002 42C89.6002 57.456 77.0561 69.9998 61.6001 69.9998Z" fill="white"/></svg></div>';
                    echo "<img class='category_body_recipe_image' src=".$row['image']." alt=".$row['title'].">";
                    echo "<p class='category_body_recipe_title'>".$row['title']."</p>";
                    echo "<div class='category_body_recipe_icons'>";
                    echo "<div class='category_body_recipe_vote_div'>";
                    echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22" height="21" viewBox="0 0 22 21" fill="none"><path d="M21.443 9.51719C21.8744 9.09672 22.0267 8.47962 21.8406 7.90597C21.6541 7.33233 21.1682 6.92283 20.5713 6.83593L15.2641 6.06477C15.0381 6.03185 14.8428 5.89009 14.7418 5.68512L12.3691 0.876052C12.1027 0.335761 11.562 0 10.9593 0C10.3572 0 9.81643 0.335761 9.55002 0.876052L7.17686 5.68512C7.07592 5.89009 6.88017 6.03185 6.65413 6.06477L1.3469 6.83636C0.750435 6.92283 0.264568 7.33233 0.0780338 7.90597C-0.108061 8.47962 0.0442382 9.09672 0.475681 9.51719L4.31565 13.2602C4.47936 13.4199 4.55441 13.6499 4.51579 13.8746L3.60945 19.1603C3.50763 19.7542 3.74683 20.3428 4.23445 20.6974C4.72164 21.0525 5.35585 21.0985 5.89 20.8172L10.6363 18.3216C10.8386 18.2154 11.08 18.2154 11.2824 18.3216L16.0291 20.8172C16.2609 20.9392 16.5119 20.9993 16.7616 20.9993C17.086 20.9993 17.409 20.898 17.6847 20.6974C18.1723 20.3428 18.4115 19.7542 18.3097 19.1603L17.4029 13.8751C17.3643 13.6499 17.4393 13.4204 17.603 13.2606L21.443 9.51719ZM16.5382 14.0234L17.4446 19.3087C17.4902 19.5747 17.3871 19.8288 17.1685 19.9877C16.9495 20.1461 16.6769 20.1654 16.4377 20.0408L11.691 17.5447C11.4623 17.4249 11.2104 17.3643 10.9593 17.3643C10.7083 17.3643 10.4568 17.4249 10.2277 17.5452L5.48182 20.0408C5.24174 20.1654 4.96918 20.1461 4.7506 19.9877C4.53203 19.8288 4.42933 19.5751 4.47453 19.3087L5.38087 14.0234C5.46821 13.5134 5.29923 12.9929 4.9288 12.6321L1.08839 8.88868C0.894834 8.69995 0.828998 8.43397 0.912829 8.17722C0.996221 7.92002 1.20558 7.74358 1.47287 7.70452L6.77966 6.93336C7.29186 6.85919 7.73471 6.53791 7.96338 6.07355L10.3365 1.26448C10.4559 1.02221 10.689 0.877807 10.9589 0.877807C11.2293 0.877807 11.4619 1.02221 11.5817 1.26448L13.9549 6.07355C14.1835 6.53791 14.6259 6.85919 15.1381 6.93336L20.4454 7.70452C20.7127 7.74358 20.922 7.92002 21.0054 8.17722C21.0888 8.43397 21.0234 8.69995 20.8298 8.88868L16.9899 12.6316C16.6194 12.9929 16.4505 13.513 16.5382 14.0234Z" fill="#666666"/></svg>';
                    if ($voters['voters'] == 0) {
                        echo "<span class='category_body_recipe_vote'>Nessuno</span>";
                    } else {
                        echo "<span class='category_body_recipe_vote'>".number_format($vote['vote']/$voters['voters'], 1)."</span>";
                    }
                    echo "</div>";
                    echo "<div class='category_body_recipe_difficult_div'>";
                    echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <g clip-path="url(#clip0)">
                        <path d="M19.1879 17.7126L17.6846 15.2084C16.9916 14.0525 16.625 12.7295 16.625 11.3811V9.18749C16.625 5.81088 13.8766 3.0625 10.5 3.0625C7.12341 3.0625 4.37503 5.81088 4.37503 9.18749V11.3811C4.37503 12.7295 4.00839 14.0525 3.31539 15.2084L1.81213 17.7126C1.73075 17.8474 1.72899 18.0163 1.80602 18.1528C1.8839 18.2901 2.03 18.375 2.1875 18.375H18.8125C18.97 18.375 19.1161 18.2902 19.194 18.1528C19.271 18.0162 19.2693 17.8474 19.1879 17.7126ZM2.96015 17.5L4.06528 15.6581C4.84052 14.3666 5.25002 12.8879 5.25002 11.3811V9.18749C5.25002 6.29211 7.60464 3.93749 10.5 3.93749C13.3954 3.93749 15.75 6.29211 15.75 9.18749V11.3811C15.75 12.8879 16.1595 14.3666 16.9339 15.6581L18.0399 17.5H2.96015Z" fill="#666666"/>
                        <path d="M10.5 0C9.53488 0 8.75 0.784875 8.75 1.75001V3.50003C8.75 3.74149 8.94601 3.9375 9.18751 3.9375C9.42901 3.9375 9.62503 3.74149 9.62503 3.49999V1.75001C9.62503 1.26701 10.017 0.875027 10.5 0.875027C10.983 0.875027 11.375 1.26701 11.375 1.75001V3.50003C11.375 3.74149 11.571 3.9375 11.8125 3.9375C12.054 3.9375 12.25 3.74149 12.25 3.49999V1.75001C12.25 0.784875 11.4652 0 10.5 0Z" fill="#666666"/>
                        <path d="M12.3935 17.7161C12.2701 17.5079 12.0033 17.4397 11.7942 17.5595C11.585 17.682 11.515 17.9506 11.6375 18.1589C11.7513 18.3523 11.8134 18.5841 11.8134 18.8125C11.8134 19.5361 11.2245 20.125 10.5009 20.125C9.77725 20.125 9.18839 19.5361 9.18839 18.8125C9.18839 18.5841 9.25053 18.3523 9.36426 18.1589C9.48588 17.9497 9.4159 17.682 9.20763 17.5595C8.99676 17.4396 8.73074 17.5079 8.60826 17.7161C8.41487 18.0469 8.3125 18.4258 8.3125 18.8125C8.31254 20.0191 9.29339 21 10.5 21C11.7067 21 12.6875 20.0191 12.6893 18.8125C12.6893 18.4258 12.5869 18.0469 12.3935 17.7161Z" fill="#666666"/>
                        </g>
                        <defs>
                        <clipPath id="clip0">
                        <rect width="21" height="21" fill="white"/>
                        </clipPath>
                        </defs>
                    </svg>';
                    echo "<span class='category_body_recipe_difficult'>".$row['difficult']."</span>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";
                
                }

            ?>
        </div>
    </article>
</section>