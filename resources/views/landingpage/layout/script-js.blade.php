<script>
    $(document).click(function(e) {
        var element_id = $(e.target).attr("id");
        var loginform = document.getElementById('login-form').style.display;
        if(element_id != "login" && element_id != "loginForm" && element_id != "login_id" && element_id != "password" && element_id != "button-login"){
                hideLoginForm();
        }
    });

    function showLoginForm(){
        var element = document.getElementById('loginForm');
        element.style.opacity=1;
        element.style.top="100%";
        element.style.visibility="visible";
        element.style.display = "block";
        // console.log(document.getElementById('loginForm'))
    }

    function hideLoginForm(){
        var element = document.getElementById('loginForm');
        element.style.opacity=0;
        element.style.top="0%";
        element.style.visibility = "hidden";
        element.style.display = "none";
        // console.log(document.getElementById('loginForm'))
    }
</script>