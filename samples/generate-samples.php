<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->fromArray([
    'label',
    'type',
    'key',
    'placeholder',
    'required',
    'options',
], null, 'A1');
$sheet->fromArray(['First Name', 'text', 'first_name', 'Enter your first name', true, ''], null, 'A2');
$sheet->fromArray(['Email Address', 'email', 'email', 'Enter your email', true, ''], null, 'A3');
$sheet->fromArray(['Contact Preference', 'radio', 'contact_preference', '', false, 'Email|Phone'], null, 'A4');
$sheet->fromArray(['Subscribe to newsletter', 'checkbox', 'newsletter_subscription', '', false, 'Yes|No'], null, 'A5');
$sheet->fromArray(['Feedback', 'textarea', 'feedback', 'Share feedback here', false, ''], null, 'A6');

$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__ . '/import-sample.xlsx');

$phpWord = new PhpWord();
$section = $phpWord->addSection();
$section->addText('Customer Feedback Form');
$section->addText('First Name?');
$section->addText('Email Address?');
$section->addText('Contact Preference: Email, Phone');
$section->addText('Subscribe to newsletter: Yes, No');
$section->addText('Feedback');

$objWriter = WordIOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/import-sample.docx');

echo "created samples/import-sample.xlsx and samples/import-sample.docx\n";
