

@include('components.section');




 <!-- ======== 1.3. Partners section ======== -->
 <section class="Partners">
      <div class="container">
        <div class="d-flex gap-3 align-items-center">
          <hr class="flex-grow-1">
          <h5>Our Solution Partners</h5>
          <hr class="flex-grow-1">
        </div>
      </div>
     
    </section>

     <section class="Features">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="d-flex flex-column gap-4 gap-md-5 pe-lg-5">
                            <h2 data-aos="fade-up">{{__('web.presenting')}} <span>{{__('web.presenting_services')}}</span></h2>
                            <div class="position-relative" data-aos="zoom-in-up">
                                <figure class="position-relative"><img class="w-100"
                                        src="assets/images/Services/features.webp" alt="features"></figure>
                                <div class="position-absolute" data-bs-toggle="modal" data-bs-target="#featuresVideo">
                                    <a href="#"><i class="fa-solid fa-play"></i></a>
                                </div>
                            </div>
                            <div class="modal fade" id="featuresVideo" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered h-100 my-0 mx-auto">
                                    <div
                                        class="modal-content d-flex align-items-center justify-content-center border-0">
                                        <div class="modal-body">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            <iframe src="https://www.youtube.com/embed/u31qwQUeGuM?si=o1Nt3XrgyYRKwJ13"
                                                title="YouTube video player"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                referrerpolicy="strict-origin-when-cross-origin"
                                                allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex flex-column flex-md-row flex-lg-column flex-wrap pt-lg-0 pt-md-5 pt-4">
                            <div class="d-flex align-items-center gap-3" data-aos="flip-left">
                                <figure>
                                    <img src="assets/images/icons/bills.svg" alt="bill">
                                </figure>
                                <div class="d-flex flex-column gap-2">
                                    <h5>{{__('web.pay_bill')}}</h5>
                                    <p>{{__('web.description_pay_bill')}}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3" data-aos="flip-left">
                                <figure>
                                    <img src="assets/images/icons/debit.svg" alt="card">
                                </figure>
                                <div class="d-flex flex-column gap-2">
                                    <h5>{{__('web.debit_card')}}</h5>
                                    <p>{{__('web.description_debit_card')}}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3" data-aos="flip-left">
                                <figure>
                                    <img src="assets/images/icons/funds.svg" alt="funds">
                                </figure>
                                <div class="d-flex flex-column gap-2">
                                    <h5>{{__('web.online_funds_transfer')}}</h5>
                                    <p>{{__('web.description_online_funds_transfer')}}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3" data-aos="flip-left">
                                <figure>
                                    <img src="assets/images/icons/invest.svg" alt="invertment">
                                </figure>
                                <div class="d-flex flex-column gap-2">
                                    <h5>{{__('web.money_investment')}}</h5>
                                    <p>{{__('web.description_money_investment')}}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3" data-aos="flip-left">
                                <figure>
                                    <img src="assets/images/icons/loan.svg" alt="loan">
                                </figure>
                                <div class="d-flex flex-column gap-2">
                                    <h5>{{__('web.loans_instant')}}</h5>
                                    <p>{{__('web.description_loans_instant')}}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3" data-aos="flip-left">
                                <figure>
                                    <img src="assets/images/icons/wallet.svg" alt="wallet">
                                </figure>
                                <div class="d-flex flex-column gap-2">
                                    <h5>{{__('web.digital_wallet')}}</h5>
                                    <p>{{__('web.description_digital_wallet')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- ======== End of 1.3. Partners section ======== -->

    @include('components.services')

    <!-- ========  1.5. Registration section ========  -->
    <section class="Registration">
      <div class="container">
        <div class="row gy-lg-0 gy-md-5 gy-4">
          <div class="col-lg-6">
            <div class="mt-md-4" data-aos="fade-up">
              <h2>Nos étapes faciles <span>pour l'inscription</span></h2>
              <p>{{__('web.text_description')}}</p>
              <div class="d-flex gap-sm-5  gap-4 flex-sm-row flex-column">
                <ul class="d-flex flex-column">
                  <li>
                    <h6>{{__('web.remplissez_informations')}}</h6>
                  </li>
                  <li>
                    <h6>{{__('web.verifiez_identite')}}</h6>
                  </li>
                  <li>
                    <h6>{{__('web.activez_compte')}}</h6>
                  </li>
                </ul>
                <ul class="d-flex flex-column">
                  <li>
                    <h6>{{__('web.toutes_donnees_protegees')}}</h6>
                  </li>
                  <li>
                    <h6>{{__('web.televersez_piece_identite')}}</h6>
                  </li>
                  <li>
                    <h6>{{__('web.gerer_compte_app')}}</h6>
                  </li>
                </ul>
              </div>
              <div class="d-flex  align-items-center gap-md-4 gap-3">
                <div>
                  <a href="Contact.html" class="hover1">{{__('web.register')}}</a>
                </div>
                <div>
                  <a href="Contact.html" class="hover2">{{__('web.get_started')}}</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex justify-content-lg-start justify-content-center">
              <figure class="position-relative" data-aos="zoom-in-up" data-aos-delay="200">
                <img class="w-100" src="assets/images/index/registration_mobile.webp" alt="registration_mobile"
                  data-aos="fade-up">
              </figure>
              <div class="d-flex flex-column gap-lg-4 gap-3 ps-lg-4 ps-3 pt-lg-2">
                <div class="d-flex flex-column gap-lg-3" data-aos="flip-left">
                  <figure class="d-flex justify-content-center align-items-center">
                    <img src="assets/images/icons/income_icon.svg" alt="income_icon">
                  </figure>
                  <div>
                    <p class="p-0">Incomes</p>
                    <p class="pt-1">$<span class="number" data-final-value="2750.50"></span></p>
                  </div>
                </div>
                <div class="d-flex flex-column gap-lg-3" data-aos="flip-left">
                  <figure class="d-flex justify-content-center align-items-center"><img
                      src="assets/images/icons/icon_expenses.svg" alt="icon_expenses"></figure>
                  <div>
                    <p class="p-0">Expenses</p>
                    <p class="pt-1">$<span class="number" data-final-value="1240.75"></span></p>
                  </div>
                </div>
                <figure data-aos="flip-left">
                  <img class="w-100" src="assets/images/index/monthly_Graph.webp" alt="monthly_Graph">
                </figure>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== End of 1.5. Registration section ========  -->

     <!-- ======== 1.6. Download section ========  -->
     <section class="Download">
      <div class="container">
        <div class="row flex-md-row flex-column-reverse">
          <div class="col-lg-5 col-md-5">
            <figure class="d-flex h-100 position-relative" data-aos="zoom-in-up">
              <img src="assets/images/index/download_Img.webp" alt="download_Img">
            </figure>
          </div>
          <div class="col-lg-7 col-md-7">
            <div
              class="d-flex flex-column h-100 justify-content-md-center justify-content-end text-md-start text-center"
              data-aos="fade-up">
              <h2>{{__('web.Vantexcard_la_solution_bancaire_mobile')}} <span>{{__('web.rapide_et_securisee')}}</span></h2>
              <p>{{__('web.ouvrez_un_compte_envoyez_argent')}}</p>
              <div class="d-flex gap-lg-4 gap-md-3 align-items-center">
                <h5>{{__('web.download_app')}}</h5>
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
          </div>
        </div>
      </div>
    </section>
    <!-- ======== End of 1.6. Download section ========  -->

     <!-- ======== 1.7. Benefits section ======== -->
     <section class="Benefits">
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <div
              class="d-flex flex-column h-100 gap-md-5 gap-4 justify-content-between justify-content-xl-start pb-lg-0 pb-5"
              data-aos="fade-up">
              <h2>{{__('web.youcansend')}} <span>{{__('web.quelqueclics')}}</span></h2>
              <div class="d-flex gap-0 position-relative">
                <div class="d-flex flex-column gap-5">
                  <div class="d-flex flex-column gap-3">
                    <h5>John Smith {{__('web.Sending')}}</h5>
                    <div class="d-flex gap-4 align-items-center">
                      <figure>
                        <img src="assets/images/index/sender.webp" alt="sender">
                      </figure>
                      <p>$<span class="number" data-final-value="466.53"></span></p>
                    </div>
                  </div>
                  <div class="d-flex flex-column gap-3">
                    <h5>William {{__('web.Recieving')}}</h5>
                    <div class="d-flex gap-4 align-items-center">
                      <figure>
                        <img src="assets/images/index/reciever.webp" alt="reciever">
                      </figure>
                      <p>$<span class="number" data-final-value="466.53"></span></p>
                    </div>
                  </div>
                </div>
                <div class="d-flex flex-column py-4">
                  <figure>
                    <img src="assets/images/icons/flag1.svg" alt="flag">
                  </figure>
                  <figure class="d-flex align-items-center justify-content-center">
                    <img src="assets/images/icons/telegram.png" alt="telegram">
                  </figure>
                  <figure>
                    <img src="assets/images/icons/flag2.svg" alt="flag">
                  </figure>
                </div>
                <div class="position-absolute p-4 d-flex align-items-center gap-3">
                  <div>
                    <figure>
                      <i class="fa-solid fa-check"></i>
                    </figure>
                  </div>
                  <p>{{__('web.yourpayments')}}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <figure class="position-relative pt-5 pt-lg-0" data-aos="zoom-in-up">
              <img class="w-100" src="assets/images/index/benefitsMobile.webp" alt="mobile">
            </figure>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== End of 1.7. Benefits section ======== -->


    @include('components.testmoniale')

    <section class="Pricing">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-lg-6">
            <figure class="position-relative d-flex align-items-center" data-aos="zoom-in-up">
              <img src="assets/images/index/credit_cards.webp" alt="cards">
            </figure>
          </div>
          <div class="col-md-6 col-lg-6">
            <div class="d-flex flex-column justify-content-center gap-lg-4 gap-2 h-100" data-aos="fade-up">
              <h2 class="pb-2">{{__('web.pricing')}}</h2>
              <p>{{__('web.pracing_description')}}</p>
              <p>{{__('web.pricing_description')}}</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-6">
            <div class="d-flex flex-column h-100 gap-4" data-aos="flip-left">
              <h4>{{__('web.pricing_plans')}}</h4>
              <p class="pb-2 flex-grow-1">{{__('web.description_pricing')}}
              </p>
              <div class="d-flex gap-4 align-items-center">
                <h2>€<span class="number d-inline" data-final-value="15"></span></h2>
                <p>0.00/0.00</p>
              </div>
              <div><a href="Pricing.html" class="hover2">Get Started</a></div>
            </div>
          </div>
          <div class="col-md-6 col-lg-6">
            <div class="d-flex flex-column h-100 gap-4" data-aos="flip-left">
              <h4>{{__('web.personal')}}</h4>
              <p class="pb-2 flex-grow-1">{{__('web.description_personal')}}
              </p>
              <div class="d-flex gap-4 align-items-center">
                <h2>€<span class="number d-inline" data-final-value="15"></span></h2>
                <p>10.95/ 131.4</p>
              </div>
              <div><a href="Pricing.html" class="hover2">Get Started</a></div>
            </div>
          </div>
        </div>
      </div>
    </section>