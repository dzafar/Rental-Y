<?php
namespace App\Application;
require_once 'InputSanitizer.php';

use App\Application\InputSanitizer; 

class OrderMailer
{
    private $senderEmail;
    private $senderName;

    public function __construct($senderEmail = 'default@example.com', $senderName = 'Default Sender')
    {
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
    }

    public function sendOrderEmail()
    {
        $days = isset($_POST['days']) ? $_POST['days'] : 0;
        $phone = isset($_POST['phone']) ? $_POST['phone'] : 0;
        $totalPrice = isset($_POST['totalPrice']) ? $_POST['totalPrice'] : 0;
        $product = isset($_POST['product']) ? $_POST['product'] : 0;
        $servicesPrice = isset($_POST['servicesPrice']) ? $_POST['servicesPrice'] : [];
        $serviceName = isset($_POST['serviceName']) ? $_POST['serviceName'] : [];

        $sanitizer = new InputSanitizer();
        $days = $sanitizer->SanitizeInput($days)[0];
        $phone = $sanitizer->SanitizeInput($phone)[0];
        $totalPrice = $sanitizer->SanitizeInput($totalPrice)[0];
        $product = $sanitizer->SanitizeInput($product)[0];
        $servicesPrice = $sanitizer->SanitizeInput($servicesPrice);
        $serviceName = $sanitizer->SanitizeInput($serviceName);

        $message = "Заказ оформлен успешно!\n\n";
        $message .= "Товар: {$product}\n";
        $message .= "Срок: {$days} дней\n";

        if (!empty($serviceName)) {
            $message .= "Услуги: \n";
            foreach ($serviceName as $key => $name) {
                $message .= " - {$name} ({$servicesPrice[$key]})\n";
            }
        }

        $message .= "\n";
        $message .= "Телефон: {$phone}\n";
        $message .= "Итоговая стоимость: {$totalPrice}\n\n";

        $headers = "From: {$this->senderName} <{$this->senderEmail}>\r\n";
        $headers .= "Reply-To: {$this->senderEmail}\r\n";
        $headers .= "Content-type: text/plain; charset=utf-8\r\n";

        if (mail('recipient@example.com', 'Новый заказ', $message, $headers)) {
            echo 'Заказ успешно оформлен!';
        } else {
            echo 'Ошибка при оформлении заказа.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instance = new OrderMailer();
    $instance->sendOrderEmail();
}
