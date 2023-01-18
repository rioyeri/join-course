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

    $("#teacher_id").select2({
        templateResult: formatText,
        templateSelection: formatText,
    });

    function formatText (obj) {
        if($(obj.element).data('text') == 1){
            return $('<span>'+obj.text+' <span class="instant-label">Bisa langsung pilih jadwal</span></span>');
        }else{
            return $('<span>'+obj.text+'</span>');
        }
    }

    $('#package_id').select2({
        templateResult: formatPrice,
        templateSelection: formatPrice,
    })

    function formatPrice (obj) {
        if(obj.text == "Paket"){
            return $('<span>'+obj.text+'</span>');
        }else{
            if($(obj.element).data('discount') != 0){
                final_price = $(obj.element).data('price') - ($(obj.element).data('price')/100 * $(obj.element).data('discount'));
                return $('<span>'+obj.text+' <del class="realprice-label">Rp '+number_format($(obj.element).data('price'), 0,",",".")+'</del><span class="discountrate-label">-'+number_format($(obj.element).data('discount'),1,",",".")+'%</span><span class="finalprice-label">Rp '+number_format(final_price, 0,",",".")+'</span></span>');
            }else{
                return $('<span>'+obj.text+' <span class="finalprice-label">Rp '+number_format($(obj.element).data('price'), 0,",",".")+'</span></span>')
            }
        }
    }

    function getOptionByValue (select, value) {
        var options = select.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value == value) {
                console.log(options[i].value, value)
                return options[i];
            }
        }
        return null
    }

    function get_teacher(params) {
        var jenisdata = "get_teacher";
        $.ajax({
            // url : "{{route('getDatas')}}",
            url: "http://127.0.0.1:8000/api/getDatas",
            type : "get",
            dataType: 'json',
            data:{
                params: params,
                jenisdata: jenisdata,
            },
        }).done(function (data) {
            console.log(data);
            $('#teacher_id').html(data.data.append);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function get_package(params){
        var jenisdata = "get_package";
        // get_schedule(params);
        $.ajax({
            url : "{{route('getDatas')}}",
            type : "get",
            dataType: 'json',
            data:{
                params: params,
                jenisdata: jenisdata,
            },
        }).done(function (data) {
            $('#package_id').html(data.append);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function get_schedule(params) {
        var jenisdata = "get_schedule";
        var element_teacher = document.getElementById('teacher_id');
        var select_value = element_teacher.options[element_teacher.selectedIndex].getAttribute('data-text');

        var line_schedule = document.getElementById('line_schedule');

        if(select_value == 1){
            line_schedule.style.display = "block";
            $.ajax({
                url : "{{route('getDatas')}}",
                type : "get",
                dataType: 'json',
                data:{
                    params: params,
                    jenisdata: jenisdata,
                },
            }).done(function (data) {
                $('#teacher_schedules').html(data.append);
            }).fail(function (msg) {
                alert('Gagal menampilkan data, silahkan refresh halaman.');
            });
        }else{
            line_schedule.style.display = "none";
        }
    }
</script>