<?php
include '../includes/public_header.php';
?>

<div class="container mt-5">
    <h2>Welkom bij KEESS</h2>
    <br>
    <p><strong>KEESS</strong> staat voor <em>Keurig Efficiënt Essentieel Sleutel Systeem</em>. Dit innovatieve systeem is perfect voor mensen met meerdere auto's of voor diegenen die heel vergeetachtig zijn. Met KEESS kunt u eenvoudig de onderhoudsgeschiedenis van uw voertuigen bijhouden, zien wanneer u voor het laatst in een auto heeft gereden, APK-datums beheren en nog veel meer!</p>
    
    <p><strong>KEESS</strong> bestaat uit de volgende onderdelen:</p>
    <ul>
De website, hier bevindt u zich momenteel. Via deze website kunt u al uw voertuiggegevens en meldingen beheren.
Het sleutelkastje, in dit sleutelkastje zit een  Arduino ingebouwd die diverse functionaliteiten biedt die in combinatie met het systeem de volgende functies mogelijk maken:
        <ul>
            <li>Meldingen ontvangen wanneer de APK-datum bijna verloopt of een schorsing op het punt staat af te lopen.</li>
            <li>Waarschuwingen krijgen wanneer u uw auto al een bepaalde tijd niet heeft gebruikt, standaard ingesteld op 2 weken.</li>
            <li>Uw onderhoudsstatus via een online overzicht bijhouden.</li>
            <li>Beheer van meerdere auto's met een duidelijk overzicht van alle informatie over uw voertuigen.</li>
        </ul>
    </ul>
</div>

<div class="container">
    <div class="row mb-1 justify-content-center">
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="row g-0 border rounded overflow-hidden flex-md-row shadow-sm position-relative">
          <div class="col p-3 d-flex flex-column position-static">
            <h4 class="mb-0">Website</h4>
            <p class="card-text mb-auto">De website maakt het makkelijk om de gegevens van uw auto's te beheren. De website staat in verbinding met het sleutelkastje hierdoor is er van alles mogelijk.</p>
            <a href="#" class="icon-link gap-1 icon-link-hover stretched-link" data-bs-toggle="modal" data-bs-target="#websiteModal">
              Meer informatie
              <svg class="bi"><use xlink:href="#chevron-right"></use></svg>
            </a>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 mb-4">
        <div class="row g-0 border rounded overflow-hidden flex-md-row shadow-sm position-relative">
          <div class="col p-3 d-flex flex-column position-static">
            <h4 class="mb-0">Sleutelkast</h4>
            <p class="card-text mb-auto">Dit is de plek waar u uw autosleutel opslaat. In het kastje zit een Arduino waarmee er allemaal functies mogelijk zijn om uw auto te beheren.</p>
            <a href="#" class="icon-link gap-1 icon-link-hover stretched-link" data-bs-toggle="modal" data-bs-target="#keyboxModal">
              Meer informatie
              <svg class="bi"><use xlink:href="#chevron-right"></use></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Website Modal -->
<div class="modal fade" id="websiteModal" tabindex="-1" aria-labelledby="websiteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="websiteModalLabel">Website</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Placeholder for photo -->
        <div class="text-center mb-3">
          <img src="https://via.placeholder.com/600x400" class="img-fluid" alt="Product Photo">
        </div>
        <p>De website heeft de volgende functies:</p>
        <ul>
          <li>Bijhouden van laatste rit van de auto</li>
          <li>APK/schorsing afloop melding</li>
          <li>Mogelijkheid om meerdere auto's toe te voegen</li>
        </ul>
        <p>Mogelijkheid om de volgende informatie bij te houden:</p>
        <ul>
          <li>APK datum</li>
          <li>KM stand</li>
          <li>Model/Merk</li>
          <li>Laatst gebruikt</li>
          <li>Reparaties/onderhoud</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
      </div>
    </div>
  </div>
</div>

<!-- Sleutelkast Modal -->
<div class="modal fade" id="keyboxModal" tabindex="-1" aria-labelledby="keyboxModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="keyboxModalLabel">Sleutelkast</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Placeholder for photo -->
        <div class="text-center mb-3">
          <img src="https://via.placeholder.com/600x400" class="img-fluid" alt="Keybox Photo">
        </div>
        <p>Het sleutelkastje heeft de volgende functies:</p>
        <ul>
          <li>Auto's in- en uitklokken met een RF tag</li>
          <li>Temperatuur- en vochtigheidsmeter</li>
          <li>Een lamp die instelbaar is, dit houdt in dat hij aangaat door in de handen te klappen of wanneer het donker is.</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
      </div>
    </div>
  </div>
</div>


  <!-- Bootstrap Bundle with Popper -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Optional: Add JavaScript if needed to handle modal data or additional functionality
    });
  </script>


<!-- Product specificatie van website en het sleutelkastje -->