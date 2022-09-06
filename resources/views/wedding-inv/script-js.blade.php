<script>
    $(document).ready(function() {
      element = document.getElementById("hero");
      element.scrollIntoView();
      count_event = $('#count_event').val();
      countdown_to_event(count_event);

      // Responsive Datatable
      $('#responsive-datatable').DataTable({
            "ordering":false,
            "searching":false,
            "lengthChange":false,
            "paging":false,
            "info":false,
            "scrollY" : 300,
            "columnDefs": [
                { "width": "5%", "targets": 0 },
                { "width": "20%", "targets": 1 },
                { "width": "5%", "targets": 2 },
                { "width": "60%", "targets": 3 },
            ]
        });

    });

    function music()
    {
        $("#judul").css("display","block");
        $("#mute").css("display","block");
        $("body").css("overflow","auto");
        $("body").css("height","auto");
        var myAudio = $("#audio")[0];
        if (myAudio.duration > 0 && !myAudio.paused) {
        }
        else myAudio.play();
    }

    function mute()
    {
      music();
      
      var myAudio = document.getElementById("audio");
      var mute = document.getElementById("mute");
      if(myAudio.muted == true) {
        myAudio.muted = false;
        mute.innerHTML = '<i class="fa fa-volume-up"></i>';
      }
        else if(myAudio.muted == false) {
          myAudio.muted = true;
          mute.innerHTML = '<i class="fa fa-volume-off"></i>';
      }
    }

    function copyNumber(norek) {
      /* Copy the text inside the text field */
      navigator.clipboard.writeText(norek);

      // $('[data-toggle="popover"]').popover({content: norek+" berhasil di salin",animation: true});
      $('#norek'+norek).popover({content: norek+" berhasil di salin",animation: true});

      // Set the date we're counting down to (dalam kasus ini ditampilkan selama 5 detik)
      var hide = new Date(new Date().getTime() + 5000).getTime();

      var x = setInterval(function() {
        // code goes here

        // Get today's date and time
        var now = new Date().getTime();
        
        var distance = hide - now;

        if(distance < 0){
          clearInterval(x);
          $('[data-toggle="popover"]').popover("hide");
        }
      }, 1000)
      /* Alert the copied text */
      // alert("Copied the text: " + norek);
    }

    function countdown_to_event(count){
      // Update the count down every 1 second
      var x = setInterval(function() {
        for(i=1; i<=count; i++){
          // Set the date we're counting down to
          var date_point = $("#date"+i).val();
          var hour_point = $("#hour"+i).val();
          // var event_time = date_point+" "+hour_point.substring(0,2)+":"+hour_point.substring(3,5);
          var event_time = date_point+" "+hour_point;
          
          var countDownDate = new Date(event_time).getTime();
        
          // Get today's date and time
          var now = new Date().getTime();
          // Find the distance between now and the count down date
          var distance = countDownDate - now;

          // Time calculations for days, hours, minutes and seconds
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // Display the result in the element with id="demo"
          // document.getElementById("countdown_pemberkatan").innerHTML = days + " Hari " + hours + " Jam " + minutes + " Menit " + seconds + " Detik";
          document.getElementById("countdown_hari"+i).innerHTML = days + " Hari";
          document.getElementById("countdown_waktu"+i).innerHTML = hours + " Jam " + minutes + " Menit " + seconds + "Detik";

          // If the count down is finished, write some text
          if (distance < 0) {
            clearInterval(x);
            // document.getElementById("countdown_pemberkatan").innerHTML = "EXPIRED";
            document.getElementById("countdown_hari"+i).innerHTML = "0 Hari";
            document.getElementById("countdown_waktu"+i).innerHTML = "0 Jam 0 Menit 0 Detik";
          }
        }
        
      }, 1000);
    }

    // function countdown_to_event(param){
    //   // Set the date we're counting down to
    //   if(param == "Pemberkatan"){
    //     var countDownDate = new Date("Feb 12, 2022 08:30:00").getTime();
    //   }else{
    //     var countDownDate = new Date("Feb 12, 2022 18:00:00").getTime();
    //   }

    //   // Update the count down every 1 second
    //   var x = setInterval(function() {

    //     // Get today's date and time
    //     var now = new Date().getTime();

    //     // Find the distance between now and the count down date
    //     var distance = countDownDate - now;

    //     // Time calculations for days, hours, minutes and seconds
    //     var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    //     var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    //     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    //     var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    //     // Display the result in the element with id="demo"
    //     if(param == "Pemberkatan"){
    //       // document.getElementById("countdown_pemberkatan").innerHTML = days + " Hari " + hours + " Jam " + minutes + " Menit " + seconds + " Detik";
    //       document.getElementById("countdown_pemberkatan_hari").innerHTML = days + " Hari";
    //       document.getElementById("countdown_pemberkatan_waktu").innerHTML = hours + " Jam " + minutes + " Menit " + seconds + "Detik";
    //     }else{
    //       // document.getElementById("countdown_resepsi").innerHTML = days + " Hari " + hours + " Jam " + minutes + " Menit " + seconds + " Detik";
    //       document.getElementById("countdown_resepsi_hari").innerHTML = days + " Hari";
    //       document.getElementById("countdown_resepsi_waktu").innerHTML = hours + " Jam " + minutes + " Menit " + seconds + "Detik";
    //     }

    //     // If the count down is finished, write some text
    //     if (distance < 0) {
    //       clearInterval(x);
    //       if(param == "Pemberkatan"){
    //         // document.getElementById("countdown_pemberkatan").innerHTML = "EXPIRED";
    //         document.getElementById("countdown_pemberkatan_hari").innerHTML = "0 Hari";
    //         document.getElementById("countdown_pemberkatan_waktu").innerHTML = "0 Jam 0 Menit 0 Detik";
    //       }else{
    //         // document.getElementById("countdown_resepsi").innerHTML = "EXPIRED";
    //         document.getElementById("countdown_resepsi_hari").innerHTML = "0 Hari";
    //         document.getElementById("countdown_resepsi_waktu").innerHTML = "0 Jam 0 Menit 0 Detik";
    //       }
    //     }
    //   }, 1000);
    // }

    $("form").submit(function(){
        var name = $('#name').val();
        var message = $('#message').val();

        if(name == "" || message == ""){
            event.preventDefault();
        }else{
            // document.getElementById("form").submit();
        }
    });
</script>