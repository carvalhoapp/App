<?php

declare(strict_types=1);

require __DIR__ . '/db.php';
require __DIR__ . '/lib/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$courseId = (int) ($_POST['course_id'] ?? 0);
$courseStmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id AND is_active = 1');
$courseStmt->execute(['id' => $courseId]);
$course = $courseStmt->fetch();

if (!$course) {
    exit('Curso inválido.');
}

$data = [
    'course_id' => $courseId,
    'full_name' => trim((string) ($_POST['full_name'] ?? '')),
    'birth_date' => (string) ($_POST['birth_date'] ?? ''),
    'gender' => trim((string) ($_POST['gender'] ?? '')),
    'rg' => trim((string) ($_POST['rg'] ?? '')),
    'cpf' => trim((string) ($_POST['cpf'] ?? '')),
    'whatsapp' => trim((string) ($_POST['whatsapp'] ?? '')),
    'education' => trim((string) ($_POST['education'] ?? '')),
    'address' => trim((string) ($_POST['address'] ?? '')),
    'city' => trim((string) ($_POST['city'] ?? '')),
    'shirt_size' => (string) ($_POST['shirt_size'] ?? ''),
    'payment_method' => (string) ($_POST['payment_method'] ?? ''),
    'accepted_terms' => isset($_POST['accepted_terms']) ? 1 : 0,
];

$photoPath = null;
if (!empty($_POST['cropped_photo'])) {
    $photoPath = saveBase64Image((string) $_POST['cropped_photo']);
}

$insert = $pdo->prepare('INSERT INTO registrations (course_id, full_name, birth_date, gender, rg, cpf, whatsapp, education, address, city, shirt_size, photo_path, payment_method, accepted_terms)
VALUES (:course_id, :full_name, :birth_date, :gender, :rg, :cpf, :whatsapp, :education, :address, :city, :shirt_size, :photo_path, :payment_method, :accepted_terms)');

$insert->execute($data + ['photo_path' => $photoPath]);
$registrationId = (int) $pdo->lastInsertId();

$extraStmt = $pdo->prepare('SELECT * FROM course_extra_fields WHERE course_id = :course_id');
$extraStmt->execute(['course_id' => $courseId]);
$extraFields = $extraStmt->fetchAll();

$extraInsert = $pdo->prepare('INSERT INTO registration_extra_values (registration_id, field_id, value_text) VALUES (:registration_id, :field_id, :value_text)');
foreach ($extraFields as $field) {
    $postName = 'extra_' . (int) $field['id'];
    $value = trim((string) ($_POST[$postName] ?? ''));
    if ($field['is_required'] && $value === '') {
        continue;
    }
    $extraInsert->execute([
        'registration_id' => $registrationId,
        'field_id' => $field['id'],
        'value_text' => $value,
    ]);
}

$settings = $pdo->query('SELECT whatsapp_number FROM settings WHERE id = 1')->fetch();
$whatsapp = $settings['whatsapp_number'] ?? '5500000000000';

$message = "Olá! Finalizei uma inscrição.\n" .
    "Curso: {$course['title']}\n" .
    "Cidade do curso: {$course['city']}\n" .
    "Valor: " . formatCurrency((float) $course['price']) . "\n" .
    "Inscrito(a): {$data['full_name']}\n" .
    "WhatsApp: {$data['whatsapp']}\n" .
    "Pagamento selecionado: {$data['payment_method']}\n" .
    "Por favor, enviar instruções de pagamento.";

redirect(whatsappLink($whatsapp, $message));
