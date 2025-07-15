@include('layouts.app')
@include('layouts.header');

<section class="BlogDetails_Hero">
            <div class="container">
                <div class="d-flex flex-column gap-md-5 gap-4 position-relative pb-0 m-0" data-aos="zoom-in-up">
                    <h2>Contact Us</h2>
                    <div>
                        <iframe
                            src="https://maps.google.com/maps?width=100%25&amp;height=550&amp;hl=en&amp;q=Evonicsoft%20Pvt.%20Ltd,%202nd%20Floor,%20Evonicsoft%20(Pvt)%20Ltd,%20Commercial%20Area%20Cavalry%20Ground,%20Plaza%2059,%20Extension%20Cavalry%20Ground,%20Lahore,%20Punjab%2054000+(Evonicsoft)&amp;t=&amp;z=17&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                    </div>
                </div>
            </div>
        </section>
        @include('components.contact');

         <!-- ======== 9.2. Contact section ======== -->
         <section class="Contact">
            <div class="container">
                <div class="d-flex justify-content-center align-items-center text-center flex-column">
                    <h2 data-aos="fade-up">Let’s Get In Touch </h2>
                    <form class="mx-auto d-flex flex-column" data-aos="fade-up">
                        <div class="d-flex w-100">
                            <input type="text" placeholder="Name" required>
                            <input type="email" placeholder="Email" required>
                        </div>
                        <div class="d-flex w-100">
                            <input type="number" placeholder="Phone" required>
                            <input type="text" placeholder="Address" required>
                        </div>
                        <textarea name="messege" rows="11" placeholder="Messege" required></textarea>
                        <button type="submit" class="hover2">Submit</button>
                    </form>
                    <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <img src="assets/images/icons/Confirmation_1.gif" alt="submitIcon">
                                <div>
                                    <h4>Response Submitted</h4>
                                    <h6>Your details have been successfully submitted. Thanks</h6>
                                </div>
                                <button type="button" class="hover2 m-auto" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ======== End of 9.2. Contact section ======== -->


@include('layouts.footer');