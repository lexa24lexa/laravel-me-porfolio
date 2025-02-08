<x-layout.main>
    <div class="main-container">
        <section class="main-text">
            <h1 class="h1">DevOps 3<strong class="h1-2"> Security 3 </strong> & Testing</h1>
            <h2>DISCOVER</h2>
            <div class="scroll-indicator">
                <img src="{{ asset('image/arrowScroll.png' )}}" alt="Scroll Indicator Image">
            </div>
        </section>
        <section>
            <div class="container reveal">
                <h3>Caption</h3>
                <div class="text-container">
                    <div class="text-box">
                        <h4>Section Text</h4>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore
                            eius molestiae perferendis eos provident vitae iste.
                        </p>
                    </div>
                    <div class="text-box">
                        <h4>Section Text</h4>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore
                            eius molestiae perferendis eos provident vitae iste.
                        </p>
                    </div>
                    <div class="text-box">
                        <h4>Section Text</h4>
                        <p>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore
                            eius molestiae perferendis eos provident vitae iste.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function reveal() {
                var reveals = document.querySelectorAll(".reveal");

                for (var i = 0; i < reveals.length; i++) {
                    var windowHeight = window.innerHeight;
                    var elementTop = reveals[i].getBoundingClientRect().top;
                    var elementVisible = 150;

                    if (elementTop < windowHeight - elementVisible) {
                        reveals[i].classList.add("active");
                    } else {
                        reveals[i].classList.remove("active");
                    }
                }
            }

            window.addEventListener("scroll", reveal);
        });
    </script>
</x-layout.main>
