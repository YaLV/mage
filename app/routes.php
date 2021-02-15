<?php

$this->get('admin', [\App\AdminController::class, 'index']);
$this->get('subscribe', [\App\SubscribeController::class, 'save']);