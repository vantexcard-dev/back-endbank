@include('layouts.app');
@include('layouts.header');


<section class="BlogDetails_Hero">
            <div class="container">
                <div class="d-flex flex-column gap-md-5 gap-4 position-relative pb-0 m-0" data-aos="zoom-in-up">
                    <div>
                        
                    </div>
                </div>
            </div>
        </section>

@include('components.contact');

<section class="Contact">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center text-center flex-column">
        <h2 data-aos="fade-up">Demande de prêt en ligne</h2>
        <p class="text-muted">Remplissez ce formulaire pour faire une demande de crédit.</p> <br>
            <form class="mx-auto"  method="POST" action="" data-aos="fade-up">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Nom complet" required>
                    </div>
                    <div class="col-md-6">
                        <input type="email" name="email" class="form-control" placeholder="Adresse email" required>
                    </div>
                    <div class="col-md-6">
                        <input type="tel" name="phone" class="form-control" placeholder="Téléphone" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="address" class="form-control" placeholder="Adresse complète" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="number" name="amount" class="form-control" placeholder="Montant demandé (€)"
                            required>
                    </div>
                    <div class="col-md-6">
                        <select name="loan_type" class="form-select" required>
                            <option value="" disabled selected>Type de prêt</option>
                            <option value="immobilier">Crédit immobilier</option>
                            <option value="auto">Crédit auto</option>
                            <option value="personnel">Crédit personnel</option>
                            <option value="étudiant">Crédit étudiant</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="number" name="duration" class="form-control" placeholder="Durée (en mois)"
                            required>
                    </div>
                    <div class="col-md-6">
                        <input type="number" name="income" class="form-control" placeholder="Revenu mensuel (€)"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <textarea name="message" rows="4" class="form-control"
                        placeholder="Informations complémentaires..."></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-5 py-2">Envoyer la demande</button>
                </div>
            </form>
            <div class="modal fade" id="loanSuccessModal" data-bs-backdrop="static" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
          <img src="assets/images/icons/Confirmation_1.gif" alt="Soumis" class="mx-auto" width="100">
          <h4 class="mt-3">Demande envoyée</h4>
          <p>Votre demande a bien été enregistrée. Nous vous contacterons sous peu.</p>
          <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</section>


@include('layouts.footer');