<?php
require_once 'App/Infrastructure/sdbh.php'; use sdbh\sdbh;
$dbh = new sdbh();
?>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
    <script src="https://unpkg.com/imask"></script>
</head>
<body>
    <div class="container">
        <div class="row row-header">
            <div class="col-12" id="count">
                <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
                <h1>Прокат Y</h1>
            </div>
        </div>

        <div class="row row-form">
            <div class="col-12">
                <form action="App/calculate.php" method="POST" id="form">

                    <?php $products = $dbh->make_query('SELECT * FROM a25_products');
                    if (is_array($products)) { ?>
                        <label class="form-label" for="product">Выберите продукт:</label>
                        <select class="form-select" name="product" id="product">
                            <?php foreach ($products as $product) {
                                $name = $product['NAME'];
                                $price = $product['PRICE'];
                                $tarif = $product['TARIFF'];
                                ?>
                                <option value="<?= $product['ID']; ?>"><?= $name; ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>

                    <label for="customRange1" class="form-label" id="count">Количество дней:</label>
                    <input type="number" name="days" class="form-control" id="customRange1" min="1" max="30" value="1">

                    <?php $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                    if (is_array($services)) {
                        ?>
                        <label for="customRange1" class="form-label">Дополнительно:</label>
                        <?php
                        $index = 0;
                        foreach ($services as $name => $price) {
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" data-name="<?= $name ?>" type="checkbox" name="services[]" value="<?= $price; ?>" id="flexCheck<?= $index; ?>">
                                <label class="form-check-label" for="flexCheck<?= $index; ?>">
                                    <?= $name ?>: <?= $price ?>
                                </label>
                            </div>
                            <?php $index++; } ?>
                        <?php } ?>

                        <button type="submit" class="btn btn-primary" id="Calculate">Рассчитать</button>
                    </form>


                    <button type="button" id="modalBtn1" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      Оставить заявку
                  </button>

                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Оставить заявку</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                        </div>
                        <div class="modal-body">
                            <form action="?" method="POST" id="orders-form">
                              <label class="form-label" for="phone">Номер телефона</label>
                              <input type="text" id="phone" class="form-control" name="phone">
                              <button type="submit" class="btn btn-primary" id="send-phone">
                                Отправить
                                <span id="countdown"></span>
                              </button>
                          </form>
                          <pre id="orders-success"></pre>
                      </div>
                  </div>
              </div>
          </div>

          <h5>Итоговая стоимость: <span id="total-price"></span></h5>
      </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>