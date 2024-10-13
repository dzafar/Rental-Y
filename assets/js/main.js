$(document).ready(function() {

  // Маска для телефона
  IMask($('#phone')[0], { mask: '+{7}(000)000-00-00' });

  // Функция проверки номера телефона
  function validatePhone(phoneNumber) {
    return /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/.test(phoneNumber);
  }

  // Избежание пустого поля customRange1
  $('#customRange1').on('blur', function() {
    if ($(this).val() === '') {
      $(this).val(0);
    }
  });

  // Функция запуска обратного отсчета
  let count = 5;
  function startCountdown() {
    let currentCount = count;
    let countdown = setInterval(function() {
      if (currentCount <= 0) {
        clearInterval(countdown);
        $("#send-phone").removeAttr("disabled");
        $("#countdown").text("");
      } else {
        $("#countdown").text(currentCount);
        currentCount--;
      }
    }, 1000);
    count *= 2;
  }

  // Очистка текста при открытии модального окна
  $('#modalBtn1').on('click', function() {
    $("#orders-success").text('');
  });

  // Обработка отправки формы расчета
  $("#form").submit(function(event) {
    event.preventDefault();
    $("#Calculate").attr("disabled", true);
    $("#modalBtn1").show();

    $.post('App/calculate.php', $(this).serialize())
    .done(function(response) {
      $("#total-price").text(response);
      localStorage.setItem('totalPrice', response);
    })
    .fail(function() {
      $("#total-price").text('Ошибка при расчете');
    })
    .always(function() {
      setTimeout(() => {
        $("#Calculate").removeAttr("disabled");
      }, 1000);
    });
  });

  // Обработка отправки заказа
  $("#orders-form").submit(function(event) {
    event.preventDefault();
    $("#send-phone").attr("disabled", true);
    $("#orders-success").text('');

    let formData = {
      product: $("#product option:selected").text(),
      days: $("#customRange1").val(),
      servicesPrice: $('input[name="services[]"]:checked').map(function() {
        return $(this).val();
      }).get(),
      serviceName: $('input[name="services[]"]:checked').map(function() {
        return $(this).data('name');
      }).get(),
      phone: $("#phone").val(),
      totalPrice: localStorage.getItem('totalPrice')
    };

    if( localStorage.getItem('phone') === $("#phone").val() ){
      $("#orders-success").text('Заказ уже оформлен, мы с вами свяжемся !');
      $("#send-phone").removeAttr("disabled");
    }
    else if (validatePhone($("#phone").val())) {
      $.post('App/Application/OrderMailer.php', formData)
      .done(function(response) {
        $("#orders-success").text(response);
        startCountdown();
        localStorage.setItem('phone', $("#phone").val());
      })
      .fail(function() {
        $("#orders-success").text('Ошибка');
      });
    }
    else {
      $("#orders-success").text('Неверный номер телефона');
      $("#send-phone").removeAttr("disabled");
    }
  });

});