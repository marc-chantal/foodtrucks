<div class="page-header">
  <h2>Bonjour <?= $_SESSION['user']['firstname'] ?></h2>
</div>

<div class="row">
  <div class="col-md-12">

    <dl class="dl-horizontal">

      <dt>Nom :</dt>
      <dd><?= $_SESSION['user']['lastname'] ?></dd>

      <dt>Prénom :</dt>
      <dd><?= $_SESSION['user']['firstname'] ?></dd>

      <dt>Email :</dt>
      <dd><?= $_SESSION['user']['email'] ?></dd>

      <dt>Mot de passe :</dt>
      <dd>********** (<a href="index.php?page=renewpwd">Modifier</a>)</dd>

    </dl>

  </div>
</div>
