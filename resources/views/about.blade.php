@include('layouts.app');

@include('layouts.header');

<section class="Subpage_Hero">
    <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center text-center" data-aos="zoom-in-up">
            <h2>About Us</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                the
                industry's standard dummy text ever since the 1500s. </p>
        </div>
    </div>
</section>

@include('components.services')

<section class="Mission">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="d-flex flex-column position-relative text-lg-start text-center h-100 justify-content-center"
                    data-aos="fade-up">
                    <h5>Our Mission</h5>
                    <h2>Empowering Financial Freedom</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec
                        ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur
                        adipiscing
                        elit. Ut elit tellus, luctus nec . Lorem ipsum dolor sit, consectetur adipiscing elit.
                        Ut
                        elit tellus, luctus nec luctus nec . Lorem ipsum dolor sit amet, consectetur adipiscing
                        elit. Ut elit tellus, luctus nec </p>
                    <div>
                        <a href="Contact.html" class="hover2">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <figure data-aos="zoom-in-up">
                    <img class="w-100" src="assets/images/About/mission_img.webp" alt="mission_img">
                </figure>
            </div>
        </div>
    </div>
</section>


<section class="Online">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-4">
                <div class="d-flex flex-column text-md-start text-center justify-content-center" data-aos="fade-up">
                    <h2>Global Online <span>Money Transfer</span> Service</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur
                        elit. Ut elit tellus, luctus nec ullamcorper mattis,
                        dapibus leo.
                    </p>
                    <div>
                        <a href="Contact.html" class="hover2">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-5" data-aos="zoom-in-up">
                <figure class="position-relative">
                    <img class="w-100 h-100" src="assets/images/About/onlineTransfer.webp" alt="onlineTransfer">
                </figure>
            </div>
            <div class="col-xl-2 col-lg-3">
                <div class="d-flex flex-lg-column justify-content-center h-100" data-aos="fade-down">
                    <div class="d-flex flex-column text-center">
                        <h2><span class="number text-white" data-final-value="93"></span>k+</h2>
                        <p>Satisfied user</p>
                    </div>
                    <div class="d-flex flex-column text-center">
                        <h2><span class="number d-lg-inline text-white" data-final-value="4.9"></span>/5</h2>
                        <p>Client Rating</p>
                    </div>
                    <div class="d-flex flex-column text-center">
                        <h2><span class="number text-white" data-final-value="99"></span>%</h2>
                        <p>Secure Payments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    @include('components.testmoniale')



@include('layouts.footer');