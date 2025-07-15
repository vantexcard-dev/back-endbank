    <!-- ======== 1.12. Footer section ======== -->
    <footer>
      <div class="container">
        <div class="d-flex flex-column align-items-center justify-content-center gap-lg-5 gap-md-4">
          <h2>Get Latest Updates</h2>
          <form class="d-flex justify-content-center align-items-center gap-lg-4 w-100">
            <input type="email" placeholder="Enter Your Email" required name="footerEmail">
            <button type="submit" class="hover1">Subscribe</button>
          </form>
          <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <img src="assets/images/icons/Confirmation_1.gif" alt="submitIcon">
                <div>
                  <h4>Response Submitted</h4>
                  <h6>Your details have been successfully submitted. Thanks</h6>
                </div>
                <button type="button" class="hover1 m-auto" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-between w-100">
          <div class="z-1 d-flex align-items-md-start align-items-center text-md-start text-center flex-column">
            <figure>
              <a href="index-2.html">
                <img src="assets/images/logo.svg" alt="logo">
              </a>
            </figure>
            <p class="m-0">{{__('web.text_policy') }}</p>
            <div class="d-flex gap-lg-4 gap-md-3 flex-row">
              <a href="#">
                <figure class="d-flex justify-content-center align-items-center">
                  <i class="fa-brands fa-facebook-f"></i>
                </figure>
              </a>
              <a href="#">
                <figure class="d-flex justify-content-center align-items-center">
                  <i class="fa-brands fa-twitter"></i>
                </figure>
              </a>
              <a href="#">
                <figure class="d-flex justify-content-center align-items-center"><i class="fa-brands fa-instagram"></i>
                </figure>
              </a>
              <a href="#">
                <figure class="d-flex justify-content-center align-items-center"><i class="fa-brands fa-linkedin"></i>
                </figure>
              </a>
            </div>
          </div>
          <div
            class="d-flex flex-column gap-lg-4 d-flex align-items-md-start align-items-center text-md-start text-center ms-0">
            <h5>Contact US</h5>
            <ul>
              <li><a href="#">120 Genève NR21, West Lancashire, England</a></li>
              <li>
                Need help? Call us
                <a href="#">
                  <span class="d-block">(+122) 34 5678 9100</span></a>
              </li>
              <li><a href="#"><span class="__cf_email__" data-cfemail="5c2f292c2c332e281c39243d312c3039723f3331">[email&#160;protected]</span></a></li>
            </ul>
          </div>
          <div
            class="d-flex gap-md-3 flex-column p-0 d-flex align-items-md-start align-items-center text-md-start text-center">
            <h5>{{__('web.dowload_app') }}</h5>
            <p>{{__('web.download_text') }}</p>
            <div class="d-flex gap-lg-3 gap-md-3">
              <a href="#">
                <div class="d-flex gap-lg-3 gap-2 justify-content-center align-items-center">
                  <figure><img src="assets/images/icons/apple.svg" alt="apple"></figure>
                  <div>
                    <p>Download on the </p>
                    <h6>App Store</h6>
                  </div>
                </div>
              </a>
              <a href="#">
                <div class="d-flex gap-lg-3 gap-2 justify-content-center align-items-center">
                  <figure><img src="assets/images/icons/playstore.svg" alt="playstore"></figure>
                  <div>
                    <p>GET IT ON</p>
                    <h6>Google Play</h6>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
        <div class="w-100 text-center py-lg-4 py-3">
          <p>Copyright &copy; <span id="year">2025</span> VANTEXCARD By PDG-G. All Rights Reserved.</p>
        </div>
      </div>
      <!-- scroll to top  -->
      <div class="scrollToTop">
        <div class="arrowUp">
          <i class="fa-solid fa-arrow-up"></i>
        </div>
        <div class="water">
          <svg viewBox="0 0 560 20" class="water_wave water_wave_back">
            <use xlink:href="#wave"></use>
          </svg>
          <svg viewBox="0 0 560 20" class="water_wave water_wave_front">
            <use xlink:href="#wave"></use>
          </svg>
          <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 0 560 20" style="display: none;">
            <symbol id="wave">
              <path
                d="M420,20c21.5-0.4,38.8-2.5,51.1-4.5c13.4-2.2,26.5-5.2,27.3-5.4C514,6.5,518,4.7,528.5,2.7c7.1-1.3,17.9-2.8,31.5-2.7c0,0,0,0,0,0v20H420z">
              </path>
              <path
                d="M420,20c-21.5-0.4-38.8-2.5-51.1-4.5c-13.4-2.2-26.5-5.2-27.3-5.4C326,6.5,322,4.7,311.5,2.7C304.3,1.4,293.6-0.1,280,0c0,0,0,0,0,0v20H420z">
              </path>
              <path
                d="M140,20c21.5-0.4,38.8-2.5,51.1-4.5c13.4-2.2,26.5-5.2,27.3-5.4C234,6.5,238,4.7,248.5,2.7c7.1-1.3,17.9-2.8,31.5-2.7c0,0,0,0,0,0v20H140z">
              </path>
              <path
                d="M140,20c-21.5-0.4-38.8-2.5-51.1-4.5c-13.4-2.2-26.5-5.2-27.3-5.4C46,6.5,42,4.7,31.5,2.7C24.3,1.4,13.6-0.1,0,0c0,0,0,0,0,0l0,20H140z">
              </path>
            </symbol>
          </svg>
        </div>
      </div>
    </footer>
    <!-- ======== End of 1.12. Footer section ======== -->
  </div>

  <!-- bootstrap min javascript -->
  <script data-cfasync="false" src="{{asset('cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js')}}"></script><script src="{{asset('assets/js/javascript-lib/bootstrap.min.js')}}" type="e6a2bd62ac9a1c6d21597346-text/javascript"></script>
  <!-- j Query -->
  <script src="{{asset('assets/js/jquery.js')}}" type="e6a2bd62ac9a1c6d21597346-text/javascript"></script>
  <!-- slick slider js -->
  <script src="{{asset('assets/js/slick.min.js')}}" type="e6a2bd62ac9a1c6d21597346-text/javascript"></script>
  <!-- main javascript -->
  <script src="{{asset('assets/js/custom.js')}}" type="e6a2bd62ac9a1c6d21597346-text/javascript"></script>
  <!-- animation from javascript -->
  <script src="{{asset('assets/js/aos.js')}}" type="e6a2bd62ac9a1c6d21597346-text/javascript"></script>

<script src="{{asset('cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js')}}" data-cf-settings="e6a2bd62ac9a1c6d21597346-|49" defer></script><script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'943a18c4fec14ebb',t:'MTc0Nzg5MzY2NC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='../cdn-cgi/challenge-platform/h/g/scripts/jsd/6fab0cec561d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script><script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"943a18c4fec14ebb","version":"2025.4.0-1-g37f21b1","r":1,"serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"afef85246d6f4aa98a1a0a5c34b94c75","b":1}' crossorigin="anonymous"></script>
</body>


<!-- Mirrored from html-templates.evonicmedia.com/finteezy/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 22 May 2025 06:01:29 GMT -->
</html>