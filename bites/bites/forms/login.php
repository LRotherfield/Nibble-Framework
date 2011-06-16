<?php

$form = NibbleForm::getInstance('/admin', 'Submit me', 'post', true, 'flash', 'list');
$form->username = new Text('Please enter your username');
$form->username->errorMessage('Dont be silly buddy');
$form->email = new password('Please enter your email', 6, true, false);
$form->email->addConfirmation('Please confirm your email', '', '</ul></li>');
$form->email->customHtml('<li><ul class="inline-list">', '');
$form->image = new File('Please upload an image', 'image', true, 1000000, 1600, 1600, 300, 300);
$form->image->errorMessage('Duh, monkey man!');
$form->addData(array(
  'username' => 'Luke'
));