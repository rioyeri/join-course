<script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            "loop": true,
            "freeDrag": true,
            "dotsEach":true,
            "autoplay":true,
            "autoplayTimeout":3000,
            "responsiveClass":true,
            "responsive":{
                0:{
                    items:1,
                    nav:true
                },
                1000:{
                    items:4,
                    nav:true,
                }
            }
        });
    });
</script>
