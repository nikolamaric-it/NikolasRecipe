$(document).ready(function(){ // CARICAMENTO PAGINA

// ACCETTAZIONE COOKIE

    var cookieAlert = document.querySelector(".alert_cookie");
    var acceptCookies = document.querySelector(".accept_cookie");
    if (!cookieAlert) {
       return;
    }

    cookieAlert.offsetHeight;
    if (!getCookie("acceptCookies")) {
        cookieAlert.classList.add("show");
    }
    acceptCookies.addEventListener("click", function () {
        setCookie("acceptCookies", true, 365);
        cookieAlert.classList.remove("show");
    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

// FRECCIE/INVIO FORM

    $('form').on('keydown', 'input, select', function(e) {
        if (e.keyCode === 13 || e.keyCode === 38 || e.keyCode === 40) {
            e.preventDefault();
            e.stopPropagation();
            focusable = $(this).parents('form:eq(0)')
                               .find('input, select, textarea')
                               .filter(':visible:not([readonly]):enabled:not(:checkbox)');
            next = focusable.eq(focusable.index(this)+1);
            prev = focusable.eq(focusable.index(this)-1);
            last = focusable.last();
            switch (e.keyCode) {
                case 13:
                    if ($(this)[0] == last[0]) {
                        $('.form_input_submit').trigger('click');
                    } else {
                        next.focus().select();
                    }
                break;
                case 38:
                    prev.focus().select();
                break;
                case 40:
                    next.focus().select();
            }
        }
    });

// MENU

    $("#linkmenu").on('click', function(){
        $(".menu").css("width", "272px");
        setTimeout(function() {
            $(".menu a").css("font-size", "21px");
        }, 100)
    });

    $("#menu_close").on('click', function(){
        setTimeout(function() {
            $(".menu").css("width", "0");
        }, 100)
        $(".menu a").css("font-size", "0");   
    });

// FILTER OVERLAY

    $("#filter").on('click', function(){
        $('#overlay').fadeIn('fast');
        $('#boxfilter').fadeIn('slow');
    });
        
    $("#boxfilter_close").on('click', function(){ 
        $('#overlay').fadeOut('fast'); 
        $('#boxfilter').hide();   
    });

// SLIDE LOGIN / REGISTRAZIONE

    $("#link_register").on('click', function() {
        $("#form_login").css("display", "none");
        $("#form_register").css("display", "block");
        $("#link_login").css("color", "#CCC");
        $("#link_register").css("color", "#666");
        $('.alert').removeClass('alert');
        $('.email_err').html('');
        $('.password_err').html('');
        $('.passwordconf_err').html('');
        $('.terms_err').html('');
    });

    $("#link_login").on('click', function() {
        $("#form_register").css("display", "none");
        $("#form_login").css("display", "block");
        $("#link_register").css("color", "#CCC");
        $("#link_login").css("color", "#666");
        $('.alert').removeClass('alert');
        $('.email_err').html('');
        $('.password_err').html('');
    });

// AGGIUNTA INGREDIENTE

    $(".ingredients_add").on('click', function(){
        let total_element = $(".ingredients_inline").length;
        let lastid = $(".ingredients_inline:last").attr("id");
        let split_id = lastid.split("_");
        let nextindex = Number(split_id[1]) + 1;
        let max = 50;
        if(total_element < max ){
            $(".ingredients_inline:last").after("<div class='ingredients_inline' id='ing_"+ nextindex +"'></div>");
            $("#ing_" + nextindex).append("<input class='input_q' type='text' placeholder='Quantità' name='q_"+ nextindex +"' minlength='1' maxlength='20' required><input class='input_i' type='text' placeholder='Ingrediente' name='i_"+ nextindex +"' minlength='3' maxlength='40' required><button id='removei_" + nextindex + "' class='ingredients_remove'>X</button>");
        }
    });

    $('.ingredients').on('click', '.ingredients_remove', function(){
        let id = this.id;
        let split_id = id.split("_");
        let deleteindex = split_id[1];
        $("#ing_" + deleteindex).remove();
    }); 

// AGGIUNTA PROCEDIMENTO

    $(".method_add").on('click', function(){
        let total_element = $(".method_inline").length;
        let lastid = $(".method_inline:last").attr("id");
        let split_id = lastid.split("_");
        let nextindex = Number(split_id[1]) + 1;
        let max = 50;
        if(total_element < max ){
            $(".method_inline:last").after("<div class='method_inline' id='met_"+ nextindex +"'></div>");
            $("#met_" + nextindex).append("<textarea class='input_m' placeholder='Procedimento' name='m_"+ nextindex +"' minlength='10' maxlength='500' required></textarea><button id='removem_" + nextindex + "' class='method_remove'>X</button>");
        }
    });

    $('.method').on('click','.method_remove', function(){
        let id = this.id;
        let split_id = id.split("_");
        let deleteindex = split_id[1];
        $("#met_" + deleteindex).remove();
    }); 

// FOLLOW AJAX

    $(".recipe_favourite").on('click', function() {
        let recipeid = $(this).attr("data-recipeid");
        $.ajax({
            type: "POST",
            url: "action.php?action=recipefollow",
            data: {
                recipeid: recipeid
            },
            success: function(result) {
                if (result == "0") {
                    $("button[data-recipeid='" + recipeid + "']").html('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35" height="32" viewBox="0 0 35 32" fill="none"><path d="M17.4387 32L14.9101 29.6981C5.92916 21.5542 0 16.1831 0 9.59128C0 4.22016 4.22016 0 9.59128 0C12.6256 0 15.5379 1.41253 17.4387 3.64469C19.3395 1.41253 22.2518 0 25.2861 0C30.6572 0 34.8774 4.22016 34.8774 9.59128C34.8774 16.1831 28.9482 21.5542 19.9673 29.7155L17.4387 32Z" fill="#CCCCCC"/></svg>');
                } else if (result == "1") {
                    $("button[data-recipeid='" + recipeid + "']").html('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35" height="32" viewBox="0 0 35 32" fill="none"><path d="M17.4387 32L14.9101 29.6981C5.92916 21.5542 0 16.1831 0 9.59128C0 4.22016 4.22016 0 9.59128 0C12.6256 0 15.5379 1.41253 17.4387 3.64469C19.3395 1.41253 22.2518 0 25.2861 0C30.6572 0 34.8774 4.22016 34.8774 9.59128C34.8774 16.1831 28.9482 21.5542 19.9673 29.7155L17.4387 32Z" fill="#FF0000"/></svg>');
                }
            } 
        });
    });

// VOTE

    $(".recipe_vote_active").on('click', function() {
        $(".recipe_vote").toggle();
        $(".recipe_vote_input").toggle();
    });

// VOTE AJAX

    $("input[name='rating']").on('click', function() {
        let vote = $("input[name='rating']:checked").val();
        let recipeid = $(this).attr("data-recipeid");
        $.ajax({
            type: "POST",
            url: "action.php?action=recipevote",
            data: {
                recipeid: recipeid,
                vote: vote
            },
            success: function(result) {
                alert(result);
            } 
        });
    });

// VISUALIZZARE PASSWORD

    $('.form_eye_icon').on('click', function() {
        if ($(this).next().attr('type') == 'password') {
            $(this).next().attr('type', 'text');
            $(this).html('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" height="21" viewBox="0 0 36 21" fill="none"><path d="M27.8296 0.170632C27.6021 -0.0568772 27.2322 -0.0568772 27.0047 0.170632L7.17063 20.0048C6.94312 20.2323 6.94312 20.6021 7.17063 20.8296C7.28499 20.9428 7.43429 21 7.58365 21C7.73301 21 7.88231 20.9428 7.99552 20.8296L27.8296 0.995515C28.0571 0.768006 28.0571 0.398141 27.8296 0.170632Z" fill="#666666"/><path d="M22.9225 0.864034C21.2155 0.289488 19.5594 0 18.0008 0C8.75892 0 0.523599 9.6064 0.177085 10.0159C-0.0419466 10.2739 -0.0599473 10.649 0.135107 10.9294C0.238611 11.078 2.71821 14.5986 7.01588 17.4532C7.14336 17.5387 7.28589 17.5792 7.42989 17.5792C7.67143 17.5792 7.90993 17.4622 8.05394 17.2417C8.28345 16.8982 8.18895 16.4317 7.84391 16.2037C4.68781 14.1051 2.52772 11.5715 1.71917 10.541C3.44576 8.66586 10.529 1.50011 18.0008 1.50011C19.3973 1.50011 20.8929 1.76414 22.444 2.28461C22.8369 2.42559 23.263 2.20811 23.3935 1.81357C23.5255 1.42058 23.3155 0.996086 22.9225 0.864034Z" fill="#666666"/><path d="M35.8222 10.0151C35.6527 9.81406 31.5951 5.0784 25.9984 2.17881C25.6353 1.98832 25.1778 2.13233 24.9873 2.50134C24.7968 2.86888 24.9408 3.32185 25.3098 3.51241C29.621 5.74449 33.0651 9.22763 34.2427 10.4997C32.4546 12.4363 25.4178 19.5001 18.0001 19.5001C15.483 19.5001 12.9464 18.8925 10.4578 17.6925C10.0888 17.511 9.63726 17.6685 9.45726 18.042C9.27577 18.414 9.43328 18.8625 9.80679 19.0425C12.4994 20.3431 15.2565 21.0001 18.0001 21.0001C27.242 21.0001 35.4773 11.3937 35.8239 10.9842C36.0607 10.7051 36.0593 10.2956 35.8222 10.0151Z" fill="#666666"/><path d="M19.0462 4.60253C18.7057 4.54255 18.3577 4.49902 18.0007 4.49902C14.6916 4.49902 12.0005 7.19013 12.0005 10.4992C12.0005 10.8562 12.044 11.2043 12.1055 11.5448C12.1699 11.9078 12.4865 12.1643 12.842 12.1643C12.8855 12.1643 12.929 12.1613 12.974 12.1523C13.3805 12.0803 13.6535 11.6902 13.5815 11.2837C13.535 11.0287 13.5005 10.7692 13.5005 10.4992C13.5005 8.01816 15.5196 5.99906 18.0007 5.99906C18.2707 5.99906 18.5302 6.03358 18.7852 6.07859C19.1842 6.15959 19.5817 5.87755 19.6537 5.47106C19.7257 5.06457 19.4528 4.67453 19.0462 4.60253Z" fill="#666666"/><path d="M22.2428 6.25747C21.9503 5.96496 21.4748 5.96496 21.1823 6.25747C20.8897 6.54998 20.8897 7.027 21.1823 7.31803C22.0313 8.16709 22.5008 9.29663 22.5008 10.4997C22.5008 12.9807 20.4817 14.9998 18.0006 14.9998C16.7976 14.9998 15.6681 14.5318 14.819 13.6813C14.5265 13.3888 14.051 13.3888 13.7584 13.6813C13.4659 13.9723 13.4659 14.4493 13.7584 14.7418C14.8895 15.8759 16.397 16.4999 18.0006 16.4999C21.3097 16.4999 24.0008 13.8088 24.0008 10.4997C24.0008 8.89605 23.3769 7.38849 22.2428 6.25747Z" fill="#666666"/></svg>');
        } else {
            $(this).next().attr('type', 'password');
            $(this).html('<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" height="21" viewBox="0 0 36 21" fill="none"><path d="M35.8661 10.074C35.5826 9.66149 28.7696 0 17.9996 0C8.7581 0 0.523107 9.60601 0.176607 10.0155C-0.0588691 10.2945 -0.0588691 10.704 0.176607 10.9845C0.523107 11.394 8.7581 21 17.9996 21C27.2411 21 35.476 11.394 35.8225 10.9845C36.0401 10.7265 36.0596 10.353 35.8661 10.074ZM17.9996 19.5C10.5911 19.5 3.5471 12.435 1.75765 10.5C3.54415 8.56356 10.5807 1.50005 17.9996 1.50005C26.6681 1.50005 32.7866 8.55456 34.2806 10.4595C32.5555 12.333 25.4711 19.5 17.9996 19.5Z" fill="#666"/><path d="M18 4.5C14.691 4.5 12 7.191 12 10.5C12 13.809 14.691 16.5 18 16.5C21.309 16.5 24 13.809 24 10.5C24 7.191 21.309 4.5 18 4.5ZM18 15.0001C15.519 15.0001 13.5 12.981 13.5 10.5001C13.5 8.01907 15.519 6.00005 18 6.00005C20.481 6.00005 22.5 8.01907 22.5 10.5001C22.5 12.981 20.481 15.0001 18 15.0001Z" fill="#666"/></svg>');
        }
    });

// LOGIN AJAX

    $("#login").on('click', function() {
        var login = true;
        if(login) {
            login = false;
            $('#login').html("<div class='loading_submit'></div>");
            $('.alert').removeClass('alert');
            $('.email_err').html('');
            $('.password_err').html('');
            let email = $("input[name='email']").val();
            let password = $("input[name='password']").val();
            if($("input[name='remember']").is(':checked')) {
                var remember = 1;
            } else {
                var remember = 0;
            }
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "action.php?action=login",
                    dataType: 'json',
                    data: {
                        email: email,
                        password: password,
                        remember: remember
                    },
                    success: function(result) {
                        if (result.length == 0) {
                            location.href = "index.php";
                        } else {
                            result.forEach(function(e) {
                                $('.' + e.err).addClass('alert');
                                $('.' + e.err).html(e.text);
                            });
                            $('#login').html("Accedi");
                        }
                    },
                    error: function(a, b, c) {
                        login = true;
                        console.error(a.responseText);
                    } 
                });
            }, 500);
        }
    });

// REGISTER AJAX

    $("#register").on('click', function() {
        var register = true;
        if(register) {
            register = false;
            $('#register').html("<div class='loading_submit'></div>");
            $('.alert').removeClass('alert');
            $('.email_err').html('');
            $('.password_err').html('');
            $('.passwordconf_err').html('');
            $('.terms_err').html('');
            let emailr = $("input[name='emailr']").val();
            let passwordr = $("input[name='passwordr']").val();
            let passwordconf = $("input[name='passwordconf']").val();
            if($("input[name='terms']").is(':checked')) {
                var terms = 1;
            } else {
                var terms = 0;
            }
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "action.php?action=register",
                    dataType: 'json',
                    data: {
                        email: emailr,
                        password: passwordr,
                        passwordconf: passwordconf,
                        terms: terms
                    },
                    success: function(result) {
                        if (result.length == 0) {
                            location.href = "index.php";
                        } else {
                            result.forEach(function(e) {
                                $('.' + e.err).addClass('alert');
                                $('.' + e.err).html(e.text);
                            });
                            $('#register').html("Registrati");
                        }
                    },
                    error: function(a, b, c) {
                        register = true;
                        console.error(a.responseText);
                    } 
                });
            }, 500);
        }

    });

// PASSWORD CHANGE AJAX

    $("#passwordchange").on('click', function() {
        var passwordchange = true;
        if(passwordchange) {
            passwordchange = false;
            $('#passwordchange').html("<div class='loading_submit'></div>");
            $('.alert').removeClass('alert');
            $('.password_err').html('');
            $('.passwordconf_err').html('');
            $('.passwordold_err').html('');
            let password = $("input[name='password']").val();
            let passwordconf = $("input[name='passwordconf']").val();
            let passwordold = $("input[name='passwordold']").val();
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "action.php?action=passwordchange",
                    dataType: 'json',
                    data: {
                        password: password,
                        passwordconf: passwordconf,
                        passwordold: passwordold,
                    },
                    success: function(result) {
                        if (result.length == 0) {
                            alert("Password Cambiata");
                            location.href = "index.php";
                        } else {
                            result.forEach(function(e) {
                                $('.' + e.err).addClass('alert');
                                $('.' + e.err).html(e.text);
                            });
                            $('#passwordchange').html("Conferma");
                        }
                    },
                    error: function(a, b, c) {
                        passwordchange = true;
                        console.error(a.responseText);
                    } 
                });
            }, 500);
        }

    });

// EMAIL CHANGE AJAX

    $("#emailchange").on('click', function() {
        var emailchange = true;
        if(emailchange) {
            emailchange = false;
            $('#emailchange').html("<div class='loading_submit'></div>");
            $('.alert').removeClass('alert');
            $('.email_err').html('');
            $('.emailconf_err').html('');
            $('.password_err').html('');
            let email = $("input[name='email']").val();
            let emailconf = $("input[name='emailconf']").val();
            let password = $("input[name='password']").val();
            setTimeout(function() {
                $.ajax({
                    type: "POST",
                    url: "action.php?action=emailchange",
                    dataType: 'json',
                    data: {
                        email: email,
                        emailconf: emailconf,
                        password: password
                    },
                    success: function(result) {
                        if (result.length == 0) {
                            alert("Email Cambiata");
                            location.href = "index.php";
                        } else {
                            result.forEach(function(e) {
                                $('.' + e.err).addClass('alert');
                                $('.' + e.err).html(e.text);
                            });
                            $('#emailchange').html("Conferma");
                        }
                    },
                    error: function(a, b, c) {
                        emailchange = true;
                        console.error(a.responseText);
                    } 
                });
            }, 500);
        }

    });

// HISTORY BACK

    $("#return").on('click', function(){
        window.history.back();
    });

});