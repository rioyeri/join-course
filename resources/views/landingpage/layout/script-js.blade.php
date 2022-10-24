<script>
    $(document).click(function(e) {
        var element_id = $(e.target).attr("id");
        var loginform = document.getElementById('login-form').style.display;
        if(element_id != "login" && element_id != "loginForm" && element_id != "login_id" && element_id != "password" && element_id != "button-login"){
            hideLoginForm();
        }
    });

    $(".select2").select2({
        templateResult: formatState,
        templateSelection: formatState,
        // height: "80px",
    });

    // getting all required elements
    const searchWrapper = document.querySelector(".search-input");
    const inputBox = searchWrapper.querySelector("input");
    const suggBox = searchWrapper.querySelector(".autocom-box");
    const icon = searchWrapper.querySelector(".icon");
    let linkTag = searchWrapper.querySelector("a");
    let webLink;


    function searchTeacherOrSubject(){
        var token = $("meta[name='csrf-token']").attr("content");
        var word = $('#searchbox').val();
        let data = [];
        $.ajax({
            url : "{{route('searchTeacherOrSubject')}}",
            type : "get",
            dataType: 'json',
            data: {
                "_token": token,
                "word": word,
            },
        }).done(function (data) {
            // console.log(data);
            if(data && data.length!=0){
                // icon.onclick = ()=>{
                //     webLink = `https://www.google.com/search?q=${userData}`;
                //     linkTag.setAttribute("href", webLink);
                //     linkTag.click();
                // }
                data = data.map((data)=>{
                    return data = '<li>'+data+'</li>';
                });
                console.log(data.length);
                searchWrapper.classList.add("active");
                showSuggestions(data);
                let allList = suggBox.querySelectorAll("li");
                for (let i = 0; i < allList.length; i++) {
                    //adding onclick attribute in all li tag
                    allList[i].setAttribute("onclick", "select(this)");
                }
            }else{
                searchWrapper.classList.remove("active"); //hide autocomplete box
            }
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function select(element){
        let selectData = element.textContent;
        inputBox.value = selectData;
        // icon.onclick = ()=>{
        //     webLink = `https://www.google.com/search?q=${selectData}`;
        //     linkTag.setAttribute("href", webLink);
        //     linkTag.click();
        // }
        searchWrapper.classList.remove("active");
    }

    function showSuggestions(list){
        let listData;
        if(!list.length){
            userValue = inputBox.value;
            listData = '<li>'+userValue+'</li>';
        }else{
            listData = list.join('');
        }
        suggBox.innerHTML = listData;
    }

    function formatState (opt) {
        if (!opt.id) {
            // return opt.text.toUpperCase();
            return opt.text;
        }

        var optimage = $(opt.element).attr('data-image');
        if(!optimage){
        // return opt.text.toUpperCase();
        return opt.text;
        } else {
            var $opt = $(
            '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
            );
            return $opt;
        }
    }

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
    
    function showDetail(id){
        console.log(id);
        $.ajax({
            url : "/showTeacherDetail/"+id,
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            $('#view-form').html(data);
            $('#myModal').modal('show');
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function closeDetail(){
        $('#myModal').modal('hide');
    }
</script>